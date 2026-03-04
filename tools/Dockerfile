# ============================================================
# EZVIZ Flask Scraper — Docker Image (Production)
# Multi-arch: amd64 → Google Chrome Stable
#             arm64 → Chromium (dari Debian apt, tersedia native)
# Non-root user | modern apt-key via /etc/apt/keyrings/
# ============================================================
FROM python:3.10-slim

# TARGETARCH diisi otomatis oleh Docker BuildKit: "amd64" atau "arm64"
ARG TARGETARCH

# Install dependencies sistem (shared untuk semua arch)
RUN apt-get update && apt-get install -y --no-install-recommends \
        wget \
        gnupg \
        curl \
        unzip \
        jq \
        ca-certificates \
        fonts-liberation \
        libasound2 \
        libatk-bridge2.0-0 \
        libatk1.0-0 \
        libcairo2 \
        libcups2 \
        libdbus-1-3 \
        libexpat1 \
        libfontconfig1 \
        libgbm1 \
        libglib2.0-0 \
        libgtk-3-0 \
        libnspr4 \
        libnss3 \
        libpango-1.0-0 \
        libpangocairo-1.0-0 \
        libstdc++6 \
        libx11-6 \
        libx11-xcb1 \
        libxcb1 \
        libxcomposite1 \
        libxcursor1 \
        libxdamage1 \
        libxext6 \
        libxfixes3 \
        libxi6 \
        libxrandr2 \
        libxrender1 \
        libxss1 \
        libxtst6 \
        lsb-release \
        xdg-utils \
    && rm -rf /var/lib/apt/lists/*

# Install browser + driver sesuai arsitektur
RUN set -ex; \
    if [ "$TARGETARCH" = "arm64" ]; then \
        # arm64: Chromium dari Debian apt (Google Chrome tidak tersedia untuk arm64 Linux)
        echo "[BUILD] ARM64 terdeteksi — install Chromium dari Debian apt"; \
        apt-get update && apt-get install -y --no-install-recommends \
            chromium \
            chromium-driver \
        && rm -rf /var/lib/apt/lists/*; \
        ln -sf /usr/bin/chromium /usr/local/bin/google-chrome; \
        ln -sf /usr/bin/chromedriver /usr/local/bin/chromedriver; \
        echo "[BUILD] Chromium: $(chromium --version)"; \
        echo "[BUILD] ChromeDriver: $(chromedriver --version)"; \
    else \
        # amd64: Google Chrome Stable dari repo resmi Google
        echo "[BUILD] AMD64 terdeteksi — install Google Chrome Stable"; \
        install -d -m 0755 /etc/apt/keyrings; \
        curl -fsSL https://dl.google.com/linux/linux_signing_key.pub \
            | gpg --dearmor -o /etc/apt/keyrings/google-chrome.gpg; \
        echo "deb [arch=amd64 signed-by=/etc/apt/keyrings/google-chrome.gpg] \
            http://dl.google.com/linux/chrome/deb/ stable main" \
            > /etc/apt/sources.list.d/google-chrome.list; \
        apt-get update && apt-get install -y --no-install-recommends google-chrome-stable \
        && rm -rf /var/lib/apt/lists/*; \
        # Install ChromeDriver versi yang cocok dengan Chrome (via Chrome for Testing API)
        CHROME_VER=$(google-chrome --version | grep -oP '[\d.]+' | head -1); \
        CHROME_MAJOR=$(echo $CHROME_VER | cut -d. -f1); \
        echo "[BUILD] Chrome: $CHROME_VER (major: $CHROME_MAJOR)"; \
        DRIVER_URL=$(curl -s "https://googlechromelabs.github.io/chrome-for-testing/latest-versions-per-milestone-with-downloads.json" \
            | jq -r ".milestones[\"$CHROME_MAJOR\"].downloads.chromedriver[] | select(.platform==\"linux64\") | .url"); \
        echo "[BUILD] ChromeDriver URL: $DRIVER_URL"; \
        wget -q -O /tmp/chromedriver.zip "$DRIVER_URL"; \
        unzip -q /tmp/chromedriver.zip -d /tmp/chromedriver/; \
        mv /tmp/chromedriver/chromedriver-linux64/chromedriver /usr/local/bin/chromedriver; \
        chmod +x /usr/local/bin/chromedriver; \
        rm -rf /tmp/chromedriver*; \
        echo "[BUILD] ChromeDriver: $(chromedriver --version)"; \
        # Symlink agar ENV CHROME_BIN bisa pakai path yang sama di kedua arch
        ln -sf /usr/bin/google-chrome /usr/local/bin/google-chrome; \
    fi

WORKDIR /app

# Install Python packages
RUN pip install --no-cache-dir \
        flask==2.3.3 \
        selenium==4.36.0 \
        webdriver-manager==4.0.2

# Copy scraper script
COPY tools/ezviz_flask_scraper.py .

# Non-root user
RUN groupadd -r scraper && useradd -r -g scraper -s /sbin/nologin scraper \
    && chown -R scraper:scraper /app

USER scraper

ENV FLASK_HOST=0.0.0.0
ENV FLASK_PORT=5055
# Chromium di arm64 ada di /usr/bin/chromium, Google Chrome di amd64 ada di /usr/bin/google-chrome
# Keduanya di-symlink ke /usr/local/bin/google-chrome saat build
ENV CHROME_BIN=/usr/local/bin/google-chrome
ENV CHROMEDRIVER_PATH=/usr/local/bin/chromedriver
ENV WDM_OFFLINE=true

EXPOSE 5055

HEALTHCHECK --interval=30s --timeout=10s --start-period=20s --retries=3 \
    CMD curl -f http://localhost:5055/health || exit 1

CMD ["python", "ezviz_flask_scraper.py"]
