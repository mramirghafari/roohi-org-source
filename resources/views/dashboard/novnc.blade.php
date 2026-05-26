<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remote Access - noVNC</title>

    {{-- سبک ساده برای تمام صفحه --}}
    <style>
        html,
        body {
            margin: 0;
            height: 100%;
            overflow: hidden;
            background: #0e0e0e;
            color: #fff;
            font-family: 'Vazirmatn', Tahoma, sans-serif;
        }

        #vnc_container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        #vnc_screen {
            flex: 1;
            background-color: #000;
        }

        #toolbar {
            background: #1b1b1b;
            padding: 6px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
        }

        #status {
            font-size: 14px;
            color: #ccc;
        }

        button {
            background: #3498db;
            border: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="vnc_container">
        <div id="toolbar">
            <div id="status">در حال اتصال به سرور...</div>
            <button onclick="disconnect()">قطع اتصال</button>
        </div>
        <div id="vnc_screen"></div>
    </div>

    {{-- noVNC core library --}}
    <script src="https://cdn.jsdelivr.net/npm/@novnc/novnc/core/rfb.js"></script>

    <script>
        let rfb;
        const statusEl = document.getElementById('status');

        function connect() {
            // اگر زیر Nginx Reverse Proxy هستی، پورت ws فرق ندارد (پورت همان HTTPS)
            const host = window.location.hostname;
            const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';

            // اگر Nginx تو را روی /novnc یا /vnc پروکسی کرده، این آدرس را همان بگذار
            const wsUrl = `${protocol}://${host}/vncwebsocket`;
            console.log("Connecting to", wsUrl);

            const screen = document.getElementById('vnc_screen');

            rfb = new RFB(screen, wsUrl, {
                credentials: {
                    password: ''
                } // در صورت نیاز
            });
            rfb.viewOnly = false;
            rfb.scaleViewport = true;
            rfb.resizeSession = true;
            rfb.background = '#000';

            rfb.addEventListener("connect", () => statusEl.textContent = "اتصال برقرار شد ✅");
            rfb.addEventListener("disconnect", evt => {
                statusEl.textContent = "ارتباط قطع شد ❌";
                console.error(evt.detail ? evt.detail.clean : 'Disconnected');
            });
        }

        function disconnect() {
            if (rfb) {
                rfb.disconnect();
                statusEl.textContent = "قطع شد.";
            }
        }

        // اتصال خودکار بعد از لود صفحه
        window.onload = connect;
    </script>
</body>

</html>
