<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ورود به دسکتاپ ریموت</title>
    <style>
        :root {
            --bg-1: #0d1b2a;
            --bg-2: #1b263b;
            --card: #ffffff;
            --text: #1f2a44;
            --muted: #6b7280;
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --danger: #dc2626;
            --border: #dbe4f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Tahoma, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(1000px 500px at 95% -5%, rgba(14, 165, 233, 0.18), transparent 55%),
                radial-gradient(900px 480px at -5% 100%, rgba(56, 189, 248, 0.22), transparent 60%),
                linear-gradient(145deg, var(--bg-1), var(--bg-2));
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .card {
            width: min(460px, 100%);
            background: var(--card);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 18px;
            box-shadow: 0 24px 70px rgba(2, 6, 23, 0.35);
            padding: 28px;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 24px;
            line-height: 1.35;
        }

        .sub {
            margin: 0 0 18px;
            color: var(--muted);
            font-size: 14px;
        }

        .token {
            direction: ltr;
            text-align: left;
            font-size: 12px;
            color: #334155;
            background: #f8fafc;
            border: 1px dashed var(--border);
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 16px;
            word-break: break-all;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 7px;
            color: #334155;
        }

        .field {
            margin-bottom: 14px;
        }

        input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 12px;
            height: 46px;
            padding: 0 12px;
            font-size: 15px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.14);
        }

        .error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 10px;
            padding: 10px 12px;
            margin: 0 0 12px;
            font-size: 13px;
        }

        button {
            width: 100%;
            border: 0;
            border-radius: 12px;
            height: 48px;
            background: linear-gradient(90deg, var(--primary-dark), var(--primary));
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        button:hover {
            filter: brightness(1.03);
        }
    </style>
</head>

<body>
    <main class="card">
        <h1>ورود به دسکتاپ ریموت</h1>
        <p class="sub">با نام کاربری و رمز عبور خود وارد شوید تا به دسکتاپ اختصاصی خودتان هدایت شوید.</p>

        @if (!empty($token))
            <div class="token">Access Token: {{ $token }}</div>
        @endif

        @if ($errors->has('credentials'))
            <div class="error">{{ $errors->first('credentials') }}</div>
        @endif

        <form method="POST" action="{{ $actionUrl ?? route('remote.access.login', $token) }}">
            @csrf

            <div class="field">
                <label for="username">نام کاربری</label>
                <input id="username" name="username" type="text" value="{{ old('username', $username) }}" required
                    autocomplete="username" autofocus>
            </div>

            <div class="field">
                <label for="password">رمز عبور</label>
                <input id="password" name="password" type="password" required autocomplete="current-password">
            </div>

            <button type="submit">ورود به سشن</button>
        </form>
    </main>
</body>

</html>
