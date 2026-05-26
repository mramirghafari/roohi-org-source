<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js');
        });
    }
</script>


<div id="pwaInstallBox" class="pwa-install-box" style="display:none">
    <div class="pwa-install-inner">
        <div class="pwa-install-text">
            <div class="pwa-title">نصب ROOHI AI</div>
            <div class="pwa-subtitle">برای دسترسی سریع‌تر، اپ را نصب کنید.</div>
        </div>

        <button id="installBtn" class="pwa-install-btn" type="button">
            نصب اپلیکیشن
        </button>
    </div>
</div>

<script>
    let deferredPrompt = null;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        const box = document.getElementById('pwaInstallBox');
        if (box) box.style.display = 'flex';
    });

    document.getElementById('installBtn')?.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        deferredPrompt.prompt();
        await deferredPrompt.userChoice;

        deferredPrompt = null;

        const box = document.getElementById('pwaInstallBox');
        if (box) box.style.display = 'none';
    });

    window.addEventListener('appinstalled', () => {
        deferredPrompt = null;
        const box = document.getElementById('pwaInstallBox');
        if (box) box.style.display = 'none';
    });
</script>


<style>
    .pwa-install-box {
        position: fixed;
        left: 16px;
        right: 16px;
        bottom: 16px;
        z-index: 99999;
        display: flex;
        justify-content: center;
    }

    .pwa-install-inner {
        background: #fff;
        border-radius: 14px;
        padding: 12px 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        direction: rtl;
    }

    .pwa-install-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .pwa-title {
        font-weight: 700;
        font-size: 14px;
        color: #111;
    }

    .pwa-subtitle {
        font-size: 12px;
        color: #666;
    }

    .pwa-install-btn {
        border: 0;
        padding: 10px 14px;
        border-radius: 10px;
        cursor: pointer;
        background: #6d28d9;
        color: #fff;
        font-weight: 700;
        white-space: nowrap;
    }
</style>
