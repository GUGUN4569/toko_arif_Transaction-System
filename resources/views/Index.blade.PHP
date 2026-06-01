<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Arif — Sistem Transaksi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
        :root {
            --bg:      #0d0f14;
            --bg2:     #13161e;
            --bg3:     #1a1e28;
            --border:  #252a38;
            --accent:  #f5a623;
            --accent2: #e8820c;
            --green:   #22c55e;
            --text:    #e8eaf0;
            --muted:   #6b7280;
        }
 
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
 
        /* ── Background glow ── */
        body::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(245,166,35,0.08) 0%, transparent 70%);
            top: -100px; left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(34,197,94,0.05) 0%, transparent 70%);
            bottom: -50px; right: 10%;
            pointer-events: none;
        }
 
        /* ── Floating particles ── */
        .particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .particle {
            position: absolute;
            width: 2px; height: 2px;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0;
            animation: float-up linear infinite;
        }
        @keyframes float-up {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 0.6; }
            90%  { opacity: 0.2; }
            100% { transform: translateY(-20px) scale(1); opacity: 0; }
        }
 
        /* ── Grid pattern ── */
        .grid-bg {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(37,42,56,0.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37,42,56,0.4) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }
 
        /* ── Main card ── */
        .landing-card {
            position: relative;
            z-index: 10;
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 56px 52px;
            max-width: 480px;
            width: 90%;
            text-align: center;
            box-shadow: 0 40px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.03);
            animation: card-in 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes card-in {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
 
        /* ── Logo ── */
        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px; height: 72px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 20px;
            font-size: 36px;
            margin-bottom: 24px;
            box-shadow: 0 12px 30px rgba(245,166,35,0.3);
            animation: logo-pulse 3s ease-in-out infinite;
        }
        @keyframes logo-pulse {
            0%, 100% { box-shadow: 0 12px 30px rgba(245,166,35,0.3); }
            50%       { box-shadow: 0 12px 40px rgba(245,166,35,0.5); }
        }
 
        /* ── Text ── */
        .app-name {
            font-family: 'Syne', sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }
        .app-tagline {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 36px;
        }
        .app-tagline span {
            color: var(--accent);
            font-weight: 600;
        }
 
        /* ── Features ── */
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 36px;
        }
        .feature-chip {
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: var(--muted);
            font-weight: 500;
            text-align: left;
            transition: border-color 0.2s;
        }
        .feature-chip:hover { border-color: rgba(245,166,35,0.3); color: var(--text); }
        .feature-icon {
            font-size: 16px;
            flex-shrink: 0;
        }
 
        /* ── Divider ── */
        .divider {
            height: 1px;
            background: var(--border);
            margin-bottom: 28px;
        }
 
        /* ── CTA Button ── */
        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 15px 24px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #0d0f14;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 6px 20px rgba(245,166,35,0.3);
            letter-spacing: 0.2px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(245,166,35,0.45);
            background: linear-gradient(135deg, #f7b73c, var(--accent));
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login svg { transition: transform 0.2s; }
        .btn-login:hover svg { transform: translateX(3px); }
 
        /* ── Footer note ── */
        .footer-note {
            margin-top: 20px;
            font-size: 12px;
            color: var(--muted);
        }
        .footer-note a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }
        .footer-note a:hover { text-decoration: underline; }
 
        /* ── Version badge ── */
        .version-badge {
            position: absolute;
            top: 18px; right: 18px;
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.2);
            color: var(--green);
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
 
<div class="grid-bg"></div>
 
<!-- Floating particles -->
<div class="particles" aria-hidden="true">
    @for($i = 0; $i < 20; $i++)
        <div class="particle" style="
            left: {{ rand(0, 100) }}%;
            animation-delay: {{ $i * 0.4 }}s;
            animation-duration: {{ rand(8, 16) }}s;
            width: {{ rand(1,3) }}px;
            height: {{ rand(1,3) }}px;
            opacity: {{ rand(3,8)/10 }};
        "></div>
    @endfor
</div>
 
<div class="landing-card">
    <div class="version-badge">v1.0</div>
 
    <div class="logo-wrap">🏪</div>
 
    <h1 class="app-name">Toko Arif</h1>
    <p class="app-tagline">
        Sistem manajemen transaksi untuk <span>Nota Penjualan</span><br>
        dan <span>Faktur Pembelian</span> secara terintegrasi.
    </p>
 
    <div class="features">
        <div class="feature-chip">
            <span class="feature-icon">📦</span>
            <span>Manajemen Barang</span>
        </div>
        <div class="feature-chip">
            <span class="feature-icon">👥</span>
            <span>Data Customer</span>
        </div>
        <div class="feature-chip">
            <span class="feature-icon">🧾</span>
            <span>Nota Penjualan</span>
        </div>
        <div class="feature-chip">
            <span class="feature-icon">📋</span>
            <span>Faktur Pembelian</span>
        </div>
    </div>
 
    <div class="divider"></div>
 
    <a href="{{ route('login') }}" class="btn-login">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        Masuk ke Sistem
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
 
 
</div>
 
</body>
</html>