Remote MT image scaffold

Purpose:
- Base image for per-user remote desktop with noVNC/KasmVNC
- Intended to expose only MetaTrader 4 and MetaTrader 5 apps

Finalize steps:
1. Put MT4 and MT5 installer files into installers/ (for example: mt4-setup.exe, mt5-setup.exe)
2. Build image:
   docker build -t roohitrade/mt-terminal:latest docker/remote-mt
3. Set .env values:
   REMOTE_DESKTOP_IMAGE=roohitrade/mt-terminal:latest
   REMOTE_DESKTOP_DEFAULT_PROFILE=balanced
4. Clear config cache:
   php artisan optimize:clear

How provisioning works:
- On first boot of each user container, /etc/cont-init.d/50-mt-provision runs once.
- Installer files are read from /opt/mt-installers.
- Wine prefix is persisted in /config/.wine.
- Launchers are available as:
  - /usr/local/bin/launch-mt4
  - /usr/local/bin/launch-mt5

Resource profile tuning (low RAM/CPU first):
- Use lightweight profile for most users; switch to balanced only when needed.
- Core envs for capacity control:
   - REMOTE_DESKTOP_MAX_RUNNING=25
   - REMOTE_DESKTOP_DEFAULT_PROFILE=balanced
   - REMOTE_DESKTOP_LIGHTWEIGHT_MEMORY=1200m
   - REMOTE_DESKTOP_LIGHTWEIGHT_CPUS=1.0
   - REMOTE_DESKTOP_BALANCED_MEMORY=1800m
   - REMOTE_DESKTOP_BALANCED_CPUS=1.5
- Log and tmpfs controls to reduce I/O overhead:
   - REMOTE_DESKTOP_LOG_MAX_SIZE=10m
   - REMOTE_DESKTOP_LOG_MAX_FILE=3
   - REMOTE_DESKTOP_TMPFS_SIZE=128m

Notes:
- MT installer binaries are proprietary and are not committed to git.
- If an installer ignores silent flags, first run may require one manual install in that user's desktop.
