FROM lscr.io/linuxserver/webtop:ubuntu-openbox

USER root

RUN set -eux; \
    dpkg --add-architecture i386; \
    apt-get update; \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
    wine64 \
    winbind \
    cabextract \
    p7zip-full \
    wget \
    curl \
    unzip \
    pcmanfm \
    fonts-wqy-zenhei; \
    if apt-cache show wine32:i386 >/dev/null 2>&1; then \
    DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends wine32:i386; \
    fi; \
    rm -rf /var/lib/apt/lists/*

COPY installers/ /opt/mt-installers/
COPY rootfs/ /

RUN chmod +x /etc/cont-init.d/50-mt-provision \
    && chmod +x /etc/s6-overlay/s6-rc.d/svc-kasmvnc/run \
    && chmod +x /usr/local/bin/mt-session-tune \
    && chmod +x /usr/local/bin/ensure-mt-installed \
    && chmod +x /usr/local/bin/launch-mt4 \
    && chmod +x /usr/local/bin/launch-mt5

ENV HOME=/config
ENV WINEPREFIX=/config/.wine
