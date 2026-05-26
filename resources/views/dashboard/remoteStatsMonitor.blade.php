<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مانیتور ریل‌تایم کاربر {{ $user->id }}</title>
    <style>
        body {
            font-family: Tahoma, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            margin: 0;
            padding: 16px;
        }

        .grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .card {
            background: #111827;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 12px;
        }

        .title {
            font-size: 14px;
            color: #93c5fd;
            margin-bottom: 8px;
        }

        .value {
            font-size: 22px;
            font-weight: 700;
        }

        .muted {
            color: #94a3b8;
            font-size: 12px;
        }

        .ok {
            color: #22c55e;
        }

        .warn {
            color: #f59e0b;
        }

        .err {
            color: #ef4444;
        }

        canvas {
            width: 100%;
            height: 160px;
            background: #020617;
            border-radius: 8px;
            border: 1px solid #334155;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .pill {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div>
            <div class="title">مانیتور ریل‌تایم ریموت</div>
            <div class="muted">کاربر: {{ $user->nam }} (ID: {{ $user->id }})</div>
        </div>
        <div id="state" class="pill">در حال اتصال...</div>
    </div>

    <div class="grid">
        <div class="card">
            <div class="title">CPU</div>
            <div id="cpu" class="value">-</div>
        </div>
        <div class="card">
            <div class="title">RAM</div>
            <div id="mem" class="value">-</div>
        </div>
        <div class="card">
            <div class="title">Container</div>
            <div id="container" class="value" style="font-size:14px;word-break:break-all;">-</div>
        </div>
        <div class="card">
            <div class="title">Network / PIDs</div>
            <div id="net" class="value" style="font-size:14px;">-</div>
            <div id="pids" class="muted">-</div>
        </div>
        <div class="card" style="grid-column:1/-1;">
            <div class="title">نمودار ۳ دقیقه اخیر (CPU / RAM %)</div>
            <canvas id="chart" width="1100" height="220"></canvas>
            <div id="updated" class="muted" style="margin-top:8px;">-</div>
        </div>

        <div class="card" style="grid-column:1/-1;">
            <div class="title">Top Processes (لحظه‌ای)</div>
            <div id="top-procs" class="muted">-</div>
        </div>
    </div>

    <script>
        (() => {
            const statsUrl = @json($statsUrl);
            const stateEl = document.getElementById('state');
            const cpuEl = document.getElementById('cpu');
            const memEl = document.getElementById('mem');
            const containerEl = document.getElementById('container');
            const netEl = document.getElementById('net');
            const pidsEl = document.getElementById('pids');
            const updatedEl = document.getElementById('updated');
            const topEl = document.getElementById('top-procs');
            const canvas = document.getElementById('chart');
            const ctx = canvas.getContext('2d');

            const maxPoints = 60;
            const cpuSeries = [];
            const memSeries = [];

            const setState = (text, klass) => {
                stateEl.textContent = text;
                stateEl.className = 'pill ' + (klass || '');
            };

            const draw = () => {
                const w = canvas.width;
                const h = canvas.height;
                ctx.clearRect(0, 0, w, h);

                ctx.strokeStyle = '#334155';
                ctx.lineWidth = 1;
                for (let i = 0; i <= 5; i++) {
                    const y = (h / 5) * i;
                    ctx.beginPath();
                    ctx.moveTo(0, y);
                    ctx.lineTo(w, y);
                    ctx.stroke();
                }

                const renderLine = (arr, color) => {
                    if (!arr.length) return;
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    arr.forEach((v, i) => {
                        const x = (i / Math.max(1, maxPoints - 1)) * w;
                        const y = h - (Math.max(0, Math.min(100, v)) / 100) * h;
                        if (i === 0) ctx.moveTo(x, y);
                        else ctx.lineTo(x, y);
                    });
                    ctx.stroke();
                };

                renderLine(cpuSeries, '#3b82f6');
                renderLine(memSeries, '#f59e0b');
            };

            const clamp = (n) => Math.max(0, Math.min(100, Number(n || 0)));

            let inflight = false;
            const poll = async () => {
                if (inflight || document.hidden) return;
                inflight = true;
                try {
                    const detailUrl = statsUrl + (statsUrl.includes('?') ? '&detail=1' : '?detail=1');
                    const res = await fetch(detailUrl, {
                        headers: {
                            'Accept': 'application/json'
                        },
                        cache: 'no-store'
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const data = await res.json();

                    if (!data.ok || !data.running) {
                        setState('غیرفعال / در دسترس نیست', 'warn');
                        updatedEl.textContent = 'آخرین بروزرسانی: ' + new Date().toLocaleTimeString('fa-IR');
                        return;
                    }

                    const cpu = clamp(data.cpu_percent);
                    const mem = clamp(data.memory_percent);

                    setState('فعال', 'ok');
                    cpuEl.textContent = cpu.toFixed(2) + '%';
                    memEl.textContent = (data.memory_usage || '-') + ' (' + mem.toFixed(2) + '%)';
                    containerEl.textContent = data.container_name || '-';
                    netEl.textContent = data.net_io || '-';
                    pidsEl.textContent = 'PIDs: ' + String(data.pids ?? '-');
                    updatedEl.textContent = 'آخرین بروزرسانی: ' + new Date().toLocaleTimeString('fa-IR');

                    if (Array.isArray(data.top_processes) && data.top_processes.length) {
                        topEl.innerHTML = data.top_processes
                            .map((p) =>
                                `${p.command} (PID ${p.pid}) - CPU ${Number(p.cpu_percent).toFixed(1)}% - MEM ${Number(p.mem_percent).toFixed(1)}%`
                                )
                            .join('<br>');
                    } else {
                        topEl.textContent = '-';
                    }

                    cpuSeries.push(cpu);
                    memSeries.push(mem);
                    if (cpuSeries.length > maxPoints) cpuSeries.shift();
                    if (memSeries.length > maxPoints) memSeries.shift();
                    draw();
                } catch (e) {
                    setState('خطا: ' + (e?.message || 'unknown'), 'err');
                } finally {
                    inflight = false;
                }
            };

            poll();
            setInterval(poll, 3000);
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) poll();
            });
        })();
    </script>
</body>

</html>
