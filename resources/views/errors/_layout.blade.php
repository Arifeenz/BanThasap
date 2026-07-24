<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'เกิดข้อผิดพลาด') - ตำบลท่าสาป</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f5f0;
            font-family: 'Sarabun', 'Segoe UI', sans-serif;
            color: #2d5a27;
            text-align: center;
            padding: 24px;
        }
        .card { max-width: 420px; }
        .code { font-size: 72px; font-weight: 600; color: #2d5a27; line-height: 1; margin: 0; }
        h1 { font-size: 20px; font-weight: 500; margin: 16px 0 8px; }
        p { color: #5a6b57; font-size: 14px; margin: 0 0 24px; }
        a.btn {
            display: inline-block;
            background: #2d5a27;
            color: #fff;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 999px;
            font-size: 14px;
        }
        a.btn:hover { background: #234a1e; }
    </style>
</head>
<body>
    <div class="card">
        <p class="code">@yield('code')</p>
        <h1>@yield('title')</h1>
        <p>@yield('message')</p>
        <a href="/" class="btn">กลับหน้าแรก</a>
    </div>
</body>
</html>
