<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Toko Arif</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
        :root {
            --bg:      #0d0f14;
            --bg2:     #13161e;
            --bg3:     #1a1e28;
            --border:  #252a38;
            --border2: #2e3447;
            --accent:  #f5a623;
            --accent2: #e8820c;
            --green:   #22c55e;
            --red:     #ef4444;
            --text:    #e8eaf0;
            --muted:   #6b7280;
        }
 
        body{
        font-family:'Plus Jakarta Sans',sans-serif;

        background:
            linear-gradient(
                180deg,
                rgba(13,15,20,.55),
                rgba(13,15,20,.85)
            ),
            url('{{ asset('images/toko-arif.jpeg') }}');

        background-size:cover;
        background-position:center;
        background-attachment: fixed;

        color:var(--text);

        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        }
 
        body::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(245,166,35,0.07) 0%, transparent 70%);
            top: -80px; left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
        }
 
        .grid-bg {
        display: none;
        }
 
        /* ── Login Card ── */
        .login-wrap {
            position: relative;
            z-index: 10;
            width: 90%;
            max-width: 420px;
            animation: slide-up 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
 
        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            transition: color 0.15s;
        }
        .back-link:hover { color: var(--text); }
 
        /* Card */
        .login-card {
            background: rgba(19,22,30,0.82);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        }
 
        /* Header strip */
        .card-header {
            background: linear-gradient(135deg, rgba(245,166,35,0.12), rgba(232,130,12,0.06));
            border-bottom: 1px solid var(--border);
            padding: 28px 32px 24px;
            text-align: center;
        }
        .logo-mini {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 14px;
            font-size: 26px;
            margin-bottom: 14px;
            box-shadow: 0 8px 20px rgba(245,166,35,0.25);
        }
        .card-header h2 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .card-header p {
            font-size: 13px;
            color: var(--muted);
        }
 
        /* Body */
        .card-body { padding: 28px 32px 32px; }
 
        /* Alert errors */
        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #f87171;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
 
        /* Form groups */
        .form-group { margin-bottom: 18px; }
 
        label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--muted);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
 
        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
            display: flex;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border2);
            border-radius: 10px;
            padding: 11px 13px 11px 40px;
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(245,166,35,0.12);
        }
        input.is-invalid { border-color: var(--red); }
        .field-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 5px;
        }
 
        /* Remember + forgot row */
        .form-row-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
            text-transform: none;
            letter-spacing: 0;
            font-weight: 500;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px;
            padding: 0;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .forgot-link {
            font-size: 13px;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.15s;
        }
        .forgot-link:hover { opacity: 0.75; }
 
        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #0d0f14;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14.5px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 5px 18px rgba(245,166,35,0.28);
            letter-spacing: 0.2px;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(245,166,35,0.4);
        }
        .btn-submit:active { transform: none; }
    </style>
</head>
<body>
 
<div class="grid-bg"></div>
 
<div class="login-wrap">
    <a href="{{ route('home') }}" class="back-link">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Beranda
    </a>
 
    <div class="login-card">
        <div class="card-header">
            <div class="logo-mini">🏪</div>
            <h2>Selamat Datang</h2>
            <p>Masuk ke Sistem Toko Arif</p>
        </div>
 
        <div class="card-body">
 
            {{-- Error from session --}}
            @if(session('error'))
                <div class="alert-error">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
 
            {{-- Validation errors --}}
            @if($errors->any())
                <div class="alert-error">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
                </div>
            @endif
 
            <form method="POST" action="{{ route('login') }}">
                @csrf
 
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                               required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
 
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                               required autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
 
                <div class="form-row-flex">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                </div>
 
                <button type="submit" class="btn-submit">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>
 
</body>
</html>