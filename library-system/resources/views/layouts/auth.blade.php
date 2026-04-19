<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Welcome') — LibraryMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --navy: #0f1e35; --navy-2: #1a2f4a;
            --gold: #c9a84c; --gold-light: #e8c97a;
            --cream: #f7f4ef; --cream-2: #ede9e2;
            --white: #ffffff; --text: #1a1a2e; --text-muted: #6b7280;
            --danger: #dc2626; --success: #059669;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }
        .auth-left {
            width: 45%;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top right, rgba(201,168,76,0.18) 0%, transparent 60%),
                        radial-gradient(ellipse at bottom left, rgba(26,47,74,0.8) 0%, transparent 70%);
        }
        .auth-left > * { position: relative; z-index: 1; }
        .auth-logo {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 60px;
        }
        .logo-icon {
            width: 48px; height: 48px;
            background: var(--gold);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--navy); font-size: 22px;
        }
        .logo-name {
            font-family: 'DM Serif Display', serif;
            color: var(--white); font-size: 26px;
        }
        .auth-tagline {
            font-family: 'DM Serif Display', serif;
            font-size: 38px;
            color: var(--white);
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .auth-tagline em { color: var(--gold-light); font-style: italic; }
        .auth-desc {
            color: rgba(255,255,255,0.55);
            font-size: 15px;
            line-height: 1.7;
            max-width: 340px;
            margin-bottom: 48px;
        }
        .auth-features { display: flex; flex-direction: column; gap: 12px; }
        .auth-feature {
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,0.7); font-size: 14px;
        }
        .auth-feature i { color: var(--gold-light); width: 16px; }
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .auth-card {
            background: var(--white);
            border-radius: 20px;
            padding: 44px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 8px 40px rgba(15,30,53,0.10);
        }
        .auth-card h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            color: var(--navy);
            margin-bottom: 6px;
        }
        .auth-card .subtitle {
            color: var(--text-muted); font-size: 14px; margin-bottom: 28px;
        }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--navy); margin-bottom: 6px; }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%); color: var(--text-muted); font-size: 14px;
        }
        .form-control {
            width: 100%; padding: 11px 14px 11px 38px;
            border: 1.5px solid var(--cream-2); border-radius: 9px;
            font-size: 14px; font-family: inherit; color: var(--text);
            background: var(--cream); outline: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .form-control:focus { border-color: var(--navy); background: var(--white); box-shadow: 0 0 0 3px rgba(15,30,53,0.07); }
        .form-control.no-icon { padding-left: 14px; }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
        .btn-block {
            display: flex; width: 100%; align-items: center; justify-content: center; gap: 8px;
            padding: 12px; border-radius: 9px; font-size: 15px; font-weight: 600;
            font-family: inherit; cursor: pointer; border: none;
            background: var(--navy); color: var(--white);
            transition: background 0.2s; margin-top: 8px;
        }
        .btn-block:hover { background: var(--navy-2); }
        .auth-link {
            text-align: center; margin-top: 20px; font-size: 14px; color: var(--text-muted);
        }
        .auth-link a { color: var(--navy); font-weight: 600; text-decoration: none; }
        .auth-link a:hover { text-decoration: underline; }
        .alert { padding: 11px 14px; border-radius: 9px; margin-bottom: 18px; font-size: 13px; font-weight: 500; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .divider { display: flex; align-items: center; gap: 12px; margin: 18px 0; color: var(--text-muted); font-size: 12px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--cream-2); }
        @media (max-width: 800px) {
            .auth-left { display: none; }
            .auth-right { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="auth-left">
    <div class="auth-logo">
        <div class="logo-icon"><i class="fas fa-book-open"></i></div>
        <div class="logo-name">LibraryMS</div>
    </div>
    <h1 class="auth-tagline">Your knowledge,<br><em>organized.</em></h1>
    <p class="auth-desc">A modern library management system for discovering, borrowing, and tracking books with ease.</p>
    <div class="auth-features">
        <div class="auth-feature"><i class="fas fa-check"></i> Browse thousands of books</div>
        <div class="auth-feature"><i class="fas fa-check"></i> Reserve books in advance</div>
        <div class="auth-feature"><i class="fas fa-check"></i> Track borrows & returns</div>
        <div class="auth-feature"><i class="fas fa-check"></i> Manage fines online</div>
    </div>
</div>
<div class="auth-right">
    <div class="auth-card">
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif
        @yield('auth-content')
    </div>
</div>
</body>
</html>
