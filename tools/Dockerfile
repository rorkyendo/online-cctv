# ============================================================
# EZVIZ Flask Scraper — Docker Image
# Base: python:3.10-slim + Google Chrome Stable
# ChromeDriver di-install saat build (tidak butuh internet saat runtime)
# ============================================================
FROM python:3.10-slim

# Install dependencies untuk Chrome + tools
RUN apt-get update && apt-get install -y --no-install-recommends \
        wget \
        gnupg \
        curl \
        unzip \
        jq \
        ca-certificates \
        fonts-liberation \
        libappindicator3-1 \
        libasound2 \
        libatk-bridge2.0-0 \
        libatk1.0-0 \
        libc6 \
        libcairo2 \
        libcups2 \
        libdbus-1-3 \
        libexpat1 \
        libfontconfig1 \
        libgbm1 \
        libgcc1 \
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
    && wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" \
        > /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update && apt-get install -y --no-install-recommends google-chrome-stable \
    && rm -rf /var/lib/apt/lists/*

# Install ChromeDriver versi yang sesuai dengan Chrome yang baru saja di-install
# Menggunakan Chrome for Testing API (resmi dari Google)
RUN CHROME_VER=$(google-chrome --version | grep -oP '[\d.]+' | head -1) \
    && CHROME_MAJOR=$(echo $CHROME_VER | cut -d. -f1) \
    && echo "Chrome version: $CHROME_VER (major: $CHROME_MAJOR)" \
    && DRIVER_URL=$(curl -s "https://googlechromelabs.github.io/chrome-for-testing/latest-versions-per-milestone-with-downloads.json" \
        | jq -r ".milestones[\"$CHROME_MAJOR\"].downloads.chromedriver[] | select(.platform==\"linux64\") | .url") \
    && echo "ChromeDriver URL: $DRIVER_URL" \
    && wget -q -O /tmp/chromedriver.zip "$DRIVER_URL" \
    && unzip -q /tmp/chromedriver.zip -d /tmp/chromedriver/ \
    && mv /tmp/chromedriver/chromedriver-linux64/chromedriver /usr/local/bin/chromedriver \
    && chmod +x /usr/local/bin/chromedriver \
    && rm -rf /tmp/chromedriver* \
    && chromedriver --version

WORKDIR /app

# Install Python packages (webdriver-manager tetap ada sebagai fallback dev lokal)
RUN pip install --no-cache-dir \
        flask==2.3.3 \
        selenium==4.36.0 \
        webdriver-manager==4.0.2

# Copy scraper
COPY tools/ezviz_flask_scraper.py .

# Konfigurasi environment
ENV FLASK_HOST=0.0.0.0
ENV FLASK_PORT=5055
ENV CHROME_BIN=/usr/bin/google-chrome
# Path ChromeDriver sudah fix di container — tidak pakai webdriver-manager saat runtime
ENV CHROMEDRIVER_PATH=/usr/local/bin/chromedriver
ENV WDM_OFFLINE=true

EXPOSE 5055

HEALTHCHECK --interval=30s --timeout=10s --start-period=15s --retries=3 \
    CMD curl -f http://localhost:5055/health || exit 1

CMD ["python", "ezviz_flask_scraper.py"]
