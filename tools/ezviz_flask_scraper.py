import os
import re
import time
import threading
import concurrent.futures
from flask import Flask, request, jsonify

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
from webdriver_manager.chrome import ChromeDriverManager

# ============================================================
# Konfigurasi
# ============================================================
LOGIN_URL  = "https://isgpopen.ezviz.com/console/login.html"
APPKEY_URL = "https://isgpopen.ezviz.com/console/appkey.html"
DEVICE_URL = "https://isgpopen.ezviz.com/console/device.html"
FLASK_PORT = int(os.environ.get("FLASK_PORT", 5055))
FLASK_HOST = os.environ.get("FLASK_HOST", "127.0.0.1")
CHROME_BIN  = os.environ.get("CHROME_BIN", "")  # e.g. /usr/bin/google-chrome in container

app = Flask(__name__)
scrape_lock = threading.Lock()  # satu request scrape sekaligus

# Resolve ChromeDriver path SEKALI saat startup — hindari network request tiap scraping
os.environ.setdefault("WDM_LOG", "0")   # matikan log webdriver_manager

# Prioritas 1: env var CHROMEDRIVER_PATH (diset oleh Docker di server)
# Prioritas 2: WDM cache lokal (development Windows)
# Prioritas 3: hardcoded cache WDM Windows
_HARDCODED_DRIVER_WIN = os.path.expanduser(
    r"~\.wdm\drivers\chromedriver\win64\145.0.7632.117\chromedriver-win32\chromedriver.exe"
)

print("[INIT] Resolving ChromeDriver path...")
_env_driver = os.environ.get("CHROMEDRIVER_PATH", "").strip()

if _env_driver and os.path.isfile(_env_driver):
    # Mode Docker/server — gunakan path dari env var langsung
    _CHROMEDRIVER_PATH = _env_driver
    print(f"[INIT] ChromeDriver (env): {_CHROMEDRIVER_PATH}")
else:
    # Mode development — pakai WDM dengan cache offline
    os.environ["WDM_OFFLINE"] = "true"
    try:
        _CHROMEDRIVER_PATH = ChromeDriverManager().install()
        print(f"[INIT] ChromeDriver (WDM cache): {_CHROMEDRIVER_PATH}")
    except Exception as _e:
        print(f"[INIT] WDM gagal ({_e}), coba hardcoded path...")
        if os.path.isfile(_HARDCODED_DRIVER_WIN):
            _CHROMEDRIVER_PATH = _HARDCODED_DRIVER_WIN
            print(f"[INIT] ChromeDriver (hardcoded): {_CHROMEDRIVER_PATH}")
        else:
            _CHROMEDRIVER_PATH = None
            print("[INIT] WARNING: ChromeDriver tidak ditemukan!")


# ============================================================
# Helper: Build Chrome driver
# ============================================================
def make_driver():
    opts = Options()
    opts.add_argument("--headless=new")
    opts.add_argument("--no-sandbox")
    opts.add_argument("--disable-dev-shm-usage")
    opts.add_argument("--disable-gpu")
    opts.add_argument("--disable-software-rasterizer")
    opts.add_argument("--disable-extensions")
    opts.add_argument("--disable-infobars")
    opts.add_argument("--no-first-run")
    # --no-zygote: hindari zygote process yang tidak kompatibel dengan beberapa kernel Docker
    # JANGAN pakai --single-process bersama --no-zygote: menyebabkan crash di Chrome 120+
    opts.add_argument("--no-zygote")
    opts.add_argument("--disable-setuid-sandbox")
    opts.add_argument("--disable-gpu-sandbox")    # disable GPU process sandbox
    opts.add_argument("--renderer-process-limit=2")
    opts.add_argument("--window-size=1280,900")
    opts.add_argument("--disable-blink-features=AutomationControlled")
    opts.add_argument("--disable-popup-blocking")
    opts.add_argument("--disable-notifications")
    opts.add_argument("--blink-settings=imagesEnabled=false")  # nonaktifkan gambar — mempercepat render SPA
    opts.add_experimental_option("excludeSwitches", ["enable-automation"])
    opts.add_experimental_option("useAutomationExtension", False)
    # eager: return setelah DOM siap, tidak tunggu resource eksternal (analytics dll)
    opts.page_load_strategy = "eager"
    opts.add_argument(
        "user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
        "AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36"
    )

    if CHROME_BIN:
        opts.binary_location = CHROME_BIN

    if _CHROMEDRIVER_PATH is None:
        raise RuntimeError("ChromeDriver tidak tersedia. Periksa instalasi webdriver-manager.")

    # Aktifkan verbose log ChromeDriver → /tmp/chromedriver.log
    # Berisi output stderr Chrome (crash reason, missing libs, dll)
    service = Service(
        _CHROMEDRIVER_PATH,
        log_output="/tmp/chromedriver.log",
        service_args=["--verbose", "--append-log"],
    )
    driver = webdriver.Chrome(service=service, options=opts)
    driver.set_page_load_timeout(30)  # eager: return setelah DOMContentLoaded, resource masih loading

    # Sembunyikan tanda otomasi
    driver.execute_cdp_cmd("Page.addScriptToEvaluateOnNewDocument", {
        "source": "Object.defineProperty(navigator, 'webdriver', {get: () => undefined})"
    })
    return driver


# ============================================================
# Helper: Tunggu SPA render (React #root terisi)
# ============================================================
def wait_spa(driver, selector, timeout=30):
    """ Tunggu elemen muncul dengan polling manual agar lebih andal di SPA. """
    deadline = time.time() + timeout
    while time.time() < deadline:
        try:
            el = driver.find_element(By.CSS_SELECTOR, selector)
            if el.is_displayed():
                return el
        except NoSuchElementException:
            pass
        time.sleep(0.7)
    raise TimeoutException(f"Elemen '{selector}' tidak muncul dalam {timeout}s")


def wait_body_text(driver, keyword, timeout=45):
    """ Tunggu hingga keyword muncul di body.text (SPA selesai render konten). """
    deadline = time.time() + timeout
    while time.time() < deadline:
        try:
            text = driver.find_element(By.TAG_NAME, "body").text
            if keyword.lower() in text.lower():
                return text
        except Exception:
            pass
        time.sleep(1)
    # Kembalikan apa pun yang ada
    try:
        return driver.find_element(By.TAG_NAME, "body").text
    except Exception:
        return ""


# ============================================================
# Helper: Login ke EZVIZ portal
# Returns (True, None) jika berhasil, atau (False, pesan_error)
# ============================================================
def do_login(driver, email: str, password: str):
    try:
        driver.get(LOGIN_URL)
    except TimeoutException:
        pass  # DOM sudah ada, resource eksternal (analytics dll) timeout — lanjutkan
    # eager sudah tunggu DOM; beri waktu React mount komponen login
    time.sleep(3)

    # eager sudah tunggu DOMContentLoaded; tunggu JS bundle React selesai mount
    # Polling #root sampai tidak kosong (react sudah render)
    deadline_root = time.time() + 25
    while time.time() < deadline_root:
        try:
            root_html = driver.find_element(By.CSS_SELECTOR, "#root").get_attribute("innerHTML")
            if root_html and len(root_html.strip()) > 50:
                break  # React sudah mount
        except Exception:
            pass
        time.sleep(0.8)
    else:
        # Debug jika #root masih kosong
        try:
            ss_path = os.path.join(os.path.dirname(__file__), "debug_login_page.png")
            driver.save_screenshot(ss_path)
            with open(os.path.join(os.path.dirname(__file__), "debug_login_page.html"), "w", encoding="utf-8") as _f:
                _f.write(driver.page_source)
            print(f"[DEBUG] #root kosong setelah 25s — screenshot: {ss_path}")
        except Exception:
            pass

    email_input = wait_spa(driver, "#register_email", timeout=20)
    time.sleep(0.5)

    email_input.clear()
    email_input.send_keys(email)

    pass_input = driver.find_element(By.CSS_SELECTOR, "#register_password")
    pass_input.clear()
    pass_input.send_keys(password)

    checkbox = driver.find_element(By.CSS_SELECTOR, "#register_agreement")
    if not checkbox.is_selected():
        driver.execute_script("arguments[0].click();", checkbox)

    time.sleep(0.5)

    sign_btn = driver.find_element(By.CSS_SELECTOR, "button.buttonOne")
    driver.execute_script("arguments[0].click();", sign_btn)
    time.sleep(5)

    if "login.html" in driver.current_url:
        # Simpan screenshot + page source untuk diagnosis
        try:
            ss_path = os.path.join(os.path.dirname(__file__), "debug_login_fail.png")
            driver.save_screenshot(ss_path)
            src_path = os.path.join(os.path.dirname(__file__), "debug_login_fail.html")
            with open(src_path, "w", encoding="utf-8") as f:
                f.write(driver.page_source)
            print(f"[DEBUG] Login gagal — screenshot: {ss_path}, source: {src_path}")
        except Exception as dbg_err:
            print(f"[DEBUG] Gagal simpan debug: {dbg_err}")
        try:
            err_el = driver.find_element(
                By.CSS_SELECTOR,
                ".ezd-form-item-explain-error, .errorTip, [class*='error-msg']"
            )
            err_msg = err_el.text.strip()
        except NoSuchElementException:
            err_msg = "Login gagal. Cek kembali email dan password."
        return False, err_msg

    return True, None


# ============================================================
# Core: Scrape AppKey + Secret
# ============================================================
def scrape_ezviz(email: str, password: str) -> dict:
    driver = make_driver()
    try:
        ok, err = do_login(driver, email, password)
        if not ok:
            return {"success": False, "message": err}

        # --------------------------------------------------
        # STEP 4: Buka halaman Appkey Management
        # --------------------------------------------------
        try:
            driver.get(APPKEY_URL)
        except TimeoutException:
            pass  # lanjutkan meskipun resource eksternal timeout
        time.sleep(2)
        # Tunggu konten "AppKey" muncul di halaman (SPA selesai render)
        body_text = wait_body_text(driver, "AppKey", timeout=45)

        # --------------------------------------------------
        # STEP 5: Scrape AppKey (plain text di halaman)
        # --------------------------------------------------
        app_key = None

        # Coba temukan baris AppKey lalu ambil nilai hex 32 karakter
        try:
            all_els = driver.find_elements(By.CSS_SELECTOR, "td, div, span, p")
            for el in all_els:
                txt = el.text.strip() if el.text else ""
                if txt.lower() == "appkey":
                    # Cari nilai di parent row
                    try:
                        row = el.find_element(
                            By.XPATH,
                            "ancestor::tr | ancestor::*[contains(@class,'row')] | "
                            "ancestor::*[contains(@class,'item')] | ancestor::*[contains(@class,'line')]"
                        )
                        cells = row.find_elements(By.CSS_SELECTOR, "td, span, div")
                        for cell in cells:
                            ct = cell.text.strip() if cell.text else ""
                            if re.match(r'^[a-f0-9]{32}$', ct, re.I):
                                app_key = ct
                                break
                    except Exception:
                        pass
                if app_key:
                    break
        except Exception:
            pass

        # Fallback regex dari body text
        if not app_key:
            m = re.search(r'AppKey\s+([a-f0-9]{32})', body_text, re.I)
            if m:
                app_key = m.group(1)

        # --------------------------------------------------
        # STEP 6: Klik "Check" untuk reveal Secret
        # Strategi: catat semua hex-32 yang ada SEBELUM klik Check,
        # lalu setelah klik, hex-32 BARU yang muncul itulah appSecret.
        # --------------------------------------------------
        app_secret = None
        try:
            # Kumpulkan hex-32 yang sudah ada (termasuk AppKey)
            body_before_check = driver.find_element(By.TAG_NAME, "body").text
            existing_hex = set(re.findall(r'[a-f0-9]{32}', body_before_check, re.I))

            # Cari tombol "Check" (untuk Secret — biasanya yang pertama)
            check_buttons = driver.find_elements(
                By.XPATH,
                "//*[normalize-space(text())='Check']"
            )

            if check_buttons:
                # Klik Check pertama (baris Secret)
                driver.execute_script("arguments[0].click();", check_buttons[0])

                # Tunggu hingga muncul hex-32 baru (max 6 detik)
                deadline = time.time() + 6
                while time.time() < deadline:
                    try:
                        body_after_check = driver.find_element(By.TAG_NAME, "body").text
                        new_hexes = set(re.findall(r'[a-f0-9]{32}', body_after_check, re.I)) - existing_hex
                        if new_hexes:
                            app_secret = list(new_hexes)[0]
                            break
                    except Exception:
                        pass
                    time.sleep(0.5)

        except Exception:
            pass  # Secret tetap None

        # --------------------------------------------------
        # STEP 6.5: Klik "Check" KEDUA untuk reveal AccessToken
        # Setelah Check pertama (AppSecret) diklik, Check berikutnya adalah AccessToken
        # --------------------------------------------------
        access_token = None
        try:
            # Re-fetch tombol Check yang masih ada (pertama sudah terganti setelah AppSecret di-reveal)
            check_buttons_2 = driver.find_elements(
                By.XPATH,
                "//*[normalize-space(text())='Check']"
            )
            if check_buttons_2:
                # Kumpulkan semua string panjang yang sudah ada
                body_pre_token = driver.find_element(By.TAG_NAME, "body").text
                existing_long = set(re.findall(r'[A-Za-z0-9_\-+/=.]{30,}', body_pre_token))

                driver.execute_script("arguments[0].click();", check_buttons_2[0])

                deadline = time.time() + 8
                while time.time() < deadline:
                    try:
                        body_post_token = driver.find_element(By.TAG_NAME, "body").text
                        new_long = set(re.findall(r'[A-Za-z0-9_\-+/=.]{30,}', body_post_token)) - existing_long
                        # Filter: bukan pure hex-32 (itu AppKey/Secret), bukan URL
                        candidates = [
                            s for s in new_long
                            if not re.match(r'^[a-f0-9]{32}$', s, re.I)
                            and 'http' not in s.lower()
                            and len(s) >= 30
                        ]
                        if candidates:
                            # Ambil kandidat terpanjang
                            access_token = max(candidates, key=len)
                            print(f"[SCRAPER] AccessToken ditemukan ({len(access_token)} chars)")
                            break
                    except Exception:
                        pass
                    time.sleep(0.5)
        except Exception as _e_tok:
            print(f"[SCRAPER] Gagal reveal AccessToken: {_e_tok}")

        # --------------------------------------------------
        # STEP 7: Scrape AccessToken expiry date
        # --------------------------------------------------
        token_expiry = None
        try:
            body_full = driver.find_element(By.TAG_NAME, "body").text
            m_exp = re.search(
                r'AccessToken valid period.*?(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})',
                body_full, re.I | re.S
            )
            if m_exp:
                token_expiry = m_exp.group(1)
        except Exception:
            pass

        # --------------------------------------------------
        # Hasil
        # --------------------------------------------------
        if app_key:
            return {
                "success": True,
                "appKey": app_key,
                "appSecret": app_secret,
                "accessToken": access_token,
                "tokenExpiry": token_expiry,
                "message": "Credential berhasil diambil dari EZVIZ Open Platform"
            }
        else:
            # Debug dump
            try:
                body_dump = driver.find_element(By.TAG_NAME, "body").text[:2000]
            except Exception:
                body_dump = ""
            return {
                "success": False,
                "message": "AppKey tidak ditemukan di halaman.",
                "debug": {"url": driver.current_url, "bodyText": body_dump}
            }

    except Exception as e:
        return {"success": False, "message": f"Scraper error: {str(e)}"}

    finally:
        try:
            driver.quit()
        except Exception:
            pass


# ============================================================
# Core: Scrape Device List
# ============================================================
def scrape_devices(email: str, password: str) -> dict:
    driver = make_driver()
    try:
        ok, err = do_login(driver, email, password)
        if not ok:
            return {"success": False, "message": err}

        # Buka halaman Equipment List
        try:
            driver.get(DEVICE_URL)
        except TimeoutException:
            pass  # lanjutkan meskipun resource eksternal timeout
        time.sleep(2)
        # Tunggu tabel device muncul
        wait_body_text(driver, "Device serial", timeout=45)

        devices = []

        # Tunggu tabel muncul
        try:
            wait_spa(driver, "table tbody tr", timeout=20)
        except TimeoutException:
            body_text = driver.find_element(By.TAG_NAME, "body").text
            return {
                "success": False,
                "message": "Tabel device tidak ditemukan. Halaman mungkin belum selesai dimuat.",
                "debug": body_text[:500]
            }

        rows = driver.find_elements(By.CSS_SELECTOR, "table tbody tr")

        for row in rows:
            cells = row.find_elements(By.CSS_SELECTOR, "td")
            if len(cells) < 4:
                continue

            serial      = cells[0].text.strip()
            name        = cells[1].text.strip().rstrip("\u270f").strip()   # buang ikon edit
            adding_time = cells[2].text.strip()
            status_raw  = cells[3].text.strip()
            channel_no  = cells[4].text.strip() if len(cells) >= 5 else "1"

            # Validasi serial (huruf kapital + angka, 6-20 karakter)
            if not serial or not re.match(r'^[A-Z0-9]{6,20}$', serial):
                continue

            # Normalkan status
            status = "online" if "online" in status_raw.lower() else "offline"

            # Channel number
            try:
                ch = int(re.search(r'\d+', channel_no).group())
            except Exception:
                ch = 1

            devices.append({
                "serial":       serial,
                "name":         name,
                "adding_time":  adding_time,
                "status":       status,
                "channel_no":   ch,
            })

        return {
            "success": True,
            "devices": devices,
            "total":   len(devices),
            "message": f"{len(devices)} device berhasil diambil dari EZVIZ portal"
        }

    except Exception as e:
        return {"success": False, "message": f"Scraper error: {str(e)}"}

    finally:
        try:
            driver.quit()
        except Exception:
            pass


# ============================================================
# Flask Routes
# ============================================================
@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok", "service": "ezviz-scraper"}), 200


@app.route("/debug-chrome", methods=["GET"])
def debug_chrome():
    """
    Jalankan Chrome dengan --dump-dom lalu kembalikan hasilnya.
    Berguna untuk memastikan Chrome bisa start di container.
    Juga kembalikan isi /tmp/chromedriver.log (berisi crash reason).
    """
    import subprocess

    # Baca ChromeDriver log jika ada
    chromedriver_log = ""
    try:
        with open("/tmp/chromedriver.log", "r") as f:
            chromedriver_log = f.read()[-3000:]  # 3000 karakter terakhir
    except Exception as e:
        chromedriver_log = f"(tidak ada log: {e})"

    # Coba jalankan Chrome langsung (bukan via Selenium)
    chrome_bin = CHROME_BIN or "google-chrome"
    cmd = [
        chrome_bin,
        "--headless=new", "--no-sandbox", "--disable-dev-shm-usage",
        "--disable-gpu", "--no-zygote", "--disable-setuid-sandbox",
        "--dump-dom", "about:blank",
    ]
    try:
        proc = subprocess.run(cmd, capture_output=True, text=True, timeout=15)
        chrome_stdout = proc.stdout[:500]
        chrome_stderr = proc.stderr[:2000]
        chrome_returncode = proc.returncode
    except Exception as e:
        chrome_stdout = ""
        chrome_stderr = str(e)
        chrome_returncode = -1

    # Cek versi Chrome dan ChromeDriver
    chrome_ver = ""
    try:
        r = subprocess.run([chrome_bin, "--version"], capture_output=True, text=True, timeout=5)
        chrome_ver = r.stdout.strip() or r.stderr.strip()
    except Exception as e:
        chrome_ver = str(e)

    driver_ver = ""
    try:
        r = subprocess.run([_CHROMEDRIVER_PATH or "chromedriver", "--version"], capture_output=True, text=True, timeout=5)
        driver_ver = r.stdout.strip() or r.stderr.strip()
    except Exception as e:
        driver_ver = str(e)

    return jsonify({
        "chrome_version": chrome_ver,
        "chromedriver_version": driver_ver,
        "chrome_returncode": chrome_returncode,
        "chrome_stderr": chrome_stderr,
        "chrome_stdout_snippet": chrome_stdout,
        "chromedriver_log_tail": chromedriver_log,
    })


@app.route("/debug-screenshot", methods=["GET"])
def debug_screenshot():
    """Buka halaman login, tunggu 15s, lalu kembalikan info debug (screenshot + snippets HTML)."""
    driver = make_driver()
    try:
        try:
            driver.get(LOGIN_URL)
        except TimeoutException:
            pass
        time.sleep(15)
        # Screenshot
        ss_path = os.path.join(os.path.dirname(__file__), "debug_screenshot.png")
        driver.save_screenshot(ss_path)
        # Cuplikan page source
        src = driver.page_source or ""
        # Info elemen
        has_root    = bool(driver.find_elements(By.CSS_SELECTOR, "#root"))
        has_email   = bool(driver.find_elements(By.CSS_SELECTOR, "#register_email"))
        root_html   = ""
        try:
            root_html = driver.find_element(By.CSS_SELECTOR, "#root").get_attribute("innerHTML")[:500]
        except Exception:
            pass
        body_text = ""
        try:
            body_text = driver.find_element(By.TAG_NAME, "body").text[:500]
        except Exception:
            pass
        return jsonify({
            "current_url":       driver.current_url,
            "page_title":        driver.title,
            "has_root":          has_root,
            "has_email_input":   has_email,
            "root_html_snippet": root_html,
            "body_text_snippet": body_text,
            "page_source_len":   len(src),
            "page_source_head":  src[:800],
            "screenshot_saved":  ss_path,
        })
    finally:
        driver.quit()


@app.route("/scrape", methods=["POST"])
def scrape():
    data = request.get_json(force=True, silent=True) or {}
    email    = data.get("email", "").strip()
    password = data.get("password", "").strip()

    if not email or not password:
        return jsonify({"success": False, "message": "email dan password wajib diisi"}), 400

    # Coba acquire lock — jika sedang sibuk, tolak langsung
    acquired = scrape_lock.acquire(blocking=True, timeout=10)
    if not acquired:
        return jsonify({"success": False, "message": "Scraper sedang sibuk memproses permintaan lain. Coba lagi dalam 30 detik."}), 503
    try:
        # Hard timeout 90s — jika Chrome hang, return error tanpa menunggu thread
        ex = concurrent.futures.ThreadPoolExecutor(max_workers=1)
        future = ex.submit(scrape_ezviz, email, password)
        ex.shutdown(wait=False)  # jangan block saat thread masih jalan
        try:
            result = future.result(timeout=90)
        except concurrent.futures.TimeoutError:
            result = {"success": False, "message": "Scraping timeout (>90s). Chrome mungkin tidak bisa memuat halaman EZVIZ."}
    finally:
        scrape_lock.release()

    return jsonify(result)


@app.route("/scrape-devices", methods=["POST"])
def scrape_devices_route():
    data     = request.get_json(force=True, silent=True) or {}
    email    = data.get("email", "").strip()
    password = data.get("password", "").strip()

    if not email or not password:
        return jsonify({"success": False, "message": "email dan password wajib diisi"}), 400

    acquired = scrape_lock.acquire(blocking=True, timeout=10)
    if not acquired:
        return jsonify({"success": False, "message": "Scraper sedang sibuk memproses permintaan lain. Coba lagi dalam 30 detik."}), 503
    try:
        ex = concurrent.futures.ThreadPoolExecutor(max_workers=1)
        future = ex.submit(scrape_devices, email, password)
        ex.shutdown(wait=False)
        try:
            result = future.result(timeout=90)
        except concurrent.futures.TimeoutError:
            result = {"success": False, "message": "Scraping timeout (>90s). Chrome mungkin tidak bisa memuat halaman EZVIZ."}
    finally:
        scrape_lock.release()

    return jsonify(result)


# ============================================================
# Main
# ============================================================
if __name__ == "__main__":
    print(f"=== EZVIZ Flask Scraper Server ===")
    print(f"Mendengarkan di http://{FLASK_HOST}:{FLASK_PORT}")
    print(f"Endpoint: POST /scrape          — Ambil AppKey + Secret")
    print(f"Endpoint: POST /scrape-devices  — Ambil daftar device")
    print(f"Endpoint: GET  /health")
    print("=" * 40)
    app.run(host=FLASK_HOST, port=FLASK_PORT, debug=False, threaded=True)
