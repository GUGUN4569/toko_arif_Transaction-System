<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Arif — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
        :root {
            --bg:        #0d0f14;
            --bg2:       #13161e;
            --bg3:       #1a1e28;
            --border:    #252a38;
            --border2:   #2e3447;
            --accent:    #f5a623;
            --accent2:   #e8820c;
            --green:     #22c55e;
            --red:       #ef4444;
            --blue:      #3b82f6;
            --text:      #e8eaf0;
            --muted:     #6b7280;
            --sidebar-w: 232px;
        }
 
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }
 
        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg2); }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }
 
        /* ════════════════════════════════
           SIDEBAR
        ════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }
 
        .sidebar-brand {
            padding: 20px 18px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
 
        .brand-logo {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }
 
        .brand-text {}
        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.1;
        }
        .brand-sub {
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
 
        .sidebar-nav {
            flex: 1;
            padding: 14px 10px;
        }
 
        .nav-section {
            margin-bottom: 20px;
        }
 
        .nav-section-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            padding: 0 8px;
            margin-bottom: 6px;
            font-weight: 600;
        }
 
        .nav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            border-radius: 7px;
            color: var(--muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.12s;
            margin-bottom: 2px;
        }
 
        .nav-item:hover {
            background: var(--bg3);
            color: var(--text);
        }
 
        .nav-item.active {
            background: rgba(245,166,35,0.13);
            color: var(--accent);
        }
 
        .nav-item.active .nav-icon { opacity: 1; }
 
        .nav-icon {
            width: 17px; height: 17px;
            flex-shrink: 0;
            opacity: 0.7;
        }
 
        /* ════════════════════════════════
           MAIN
        ════════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }
 
        .topbar {
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
 
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
        }
 
        .topbar-right {
            display: flex;
            gap: 8px;
            align-items: center;
        }
 
        .date-pill {
            font-size: 12px;
            color: var(--muted);
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 4px 10px;
        }
 
        .content {
            padding: 24px;
            flex: 1;
        }
 
        /* ════════════════════════════════
           ALERTS
        ════════════════════════════════ */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert svg { flex-shrink: 0; margin-top: 1px; }
        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.25);
            color: #4ade80;
        }
        .alert-danger {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            color: #f87171;
        }
 
        /* ════════════════════════════════
           BUTTONS
        ════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.12s;
            white-space: nowrap;
            line-height: 1;
        }
        .btn-primary { background: var(--accent); color: #0d0f14; }
        .btn-primary:hover { background: var(--accent2); }
        .btn-secondary { background: var(--bg3); color: var(--text); border: 1px solid var(--border2); }
        .btn-secondary:hover { background: var(--border); }
        .btn-danger { background: rgba(239,68,68,0.12); color: var(--red); border: 1px solid rgba(239,68,68,0.25); }
        .btn-danger:hover { background: rgba(239,68,68,0.22); }
        .btn-sm { padding: 5px 11px; font-size: 12px; }
 
        /* ════════════════════════════════
           CARDS / PANELS
        ════════════════════════════════ */
        .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title { font-weight: 700; font-size: 14px; }
        .card-body { padding: 18px; }
 
        /* ════════════════════════════════
           STAT CARDS
        ════════════════════════════════ */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
 
        .stat-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 18px 16px;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
            transition: border-color 0.15s, transform 0.15s;
        }
        .stat-card:hover {
            border-color: var(--border2);
            transform: translateY(-2px);
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 12px 12px 0 0;
        }
        .stat-card.gold::after   { background: var(--accent); }
        .stat-card.green::after  { background: var(--green); }
        .stat-card.blue::after   { background: var(--blue); }
        .stat-card.red::after    { background: var(--red); }
        .stat-card.teal::after   { background: #14b8a6; }
        .stat-card.purple::after { background: #a855f7; }
 
        .stat-label {
            font-size: 10.5px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.9px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-card.gold   .stat-value { color: var(--accent); }
        .stat-card.green  .stat-value { color: var(--green); }
        .stat-card.blue   .stat-value { color: var(--blue); }
        .stat-card.red    .stat-value { color: var(--red); }
        .stat-card.teal   .stat-value { color: #2dd4bf; }
        .stat-card.purple .stat-value { color: #c084fc; }
        .stat-sub {
            font-size: 11px;
            color: var(--muted);
        }
 
        /* ════════════════════════════════
           TABLE
        ════════════════════════════════ */
        .table-wrap { overflow-x: auto; }
 
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
 
        thead tr { border-bottom: 1px solid var(--border2); }
 
        th {
            padding: 10px 16px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            font-weight: 600;
            white-space: nowrap;
        }
 
        td {
            padding: 11px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }
 
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: rgba(255,255,255,0.025); }
 
        .td-mono {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
        }
        .td-amount { text-align: right; font-weight: 600; }
 
        /* ════════════════════════════════
           BADGES
        ════════════════════════════════ */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-green  { background: rgba(34,197,94,0.12);  color: #4ade80; }
        .badge-blue   { background: rgba(59,130,246,0.12); color: #60a5fa; }
        .badge-gold   { background: rgba(245,166,35,0.12); color: var(--accent); }
        .badge-muted  { background: var(--bg3); color: var(--muted); border: 1px solid var(--border); }
 
        /* ════════════════════════════════
           RANK BADGE
        ════════════════════════════════ */
        .rank-badge {
            width: 22px; height: 22px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700;
        }
        .rank-1 { background: rgba(245,166,35,0.2); color: var(--accent); }
        .rank-2 { background: rgba(148,163,184,0.15); color: #94a3b8; }
        .rank-3 { background: rgba(239,68,68,0.15); color: #f87171; }
        .rank-n { background: var(--bg3); color: var(--muted); }
 
        /* ════════════════════════════════
           MINI BAR
        ════════════════════════════════ */
        .bar-bg { height: 5px; background: var(--border); border-radius: 3px; overflow: hidden; min-width: 60px; }
        .bar-fill { height: 100%; border-radius: 3px; background: var(--green); }
 
        /* ════════════════════════════════
           FORMS
        ════════════════════════════════ */
        .form-group { margin-bottom: 18px; }
 
        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
 
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border2);
            border-radius: 8px;
            padding: 9px 13px;
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px;
            transition: border-color 0.15s;
            outline: none;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(245,166,35,0.1);
        }
        select option { background: var(--bg3); }
 
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
 
        .invalid-feedback { color: var(--red); font-size: 12px; margin-top: 5px; }
        .is-invalid { border-color: var(--red) !important; }
 
        /* ════════════════════════════════
           PAGINATION
        ════════════════════════════════ */
        .pagination {
            display: flex; gap: 5px;
            align-items: center; justify-content: center;
            padding: 14px;
        }
        .page-link {
            padding: 5px 11px;
            border-radius: 6px;
            border: 1px solid var(--border2);
            background: var(--bg3);
            color: var(--muted);
            text-decoration: none;
            font-size: 12.5px;
            transition: all 0.12s;
        }
        .page-link:hover { color: var(--text); border-color: var(--accent); }
        .page-link.active { background: var(--accent); color: #0d0f14; border-color: var(--accent); font-weight: 700; }
 
        /* ════════════════════════════════
           ITEM ROW (nota / faktur create)
        ════════════════════════════════ */
        .item-row {
            display: grid;
            grid-template-columns: 1fr 100px 130px auto;
            gap: 10px;
            align-items: end;
            margin-bottom: 10px;
            background: var(--bg3);
            padding: 13px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        #items-container .item-row:first-child .remove-item { display: none; }
 
        /* ════════════════════════════════
           UTILITIES
        ════════════════════════════════ */
        .text-muted  { color: var(--muted); }
        .text-right  { text-align: right; }
        .d-flex      { display: flex; }
        .gap-2       { gap: 8px; }
        .gap-3       { gap: 12px; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .font-mono   { font-family: 'Courier New', monospace; }
        .mt-3        { margin-top: 12px; }
        .mt-4        { margin-top: 16px; }
        .mb-3        { margin-bottom: 12px; }
        .mb-4        { margin-bottom: 16px; }
        .w-full      { width: 100%; }
    </style>
</head>
<body>
 
<!-- ── Sidebar ── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">🏪</div>
        <div class="brand-text">
            <div class="brand-name">Toko Arif</div>
            <div class="brand-sub">Manajemen Toko</div>
        </div>
    </div>
 
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-label">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
        </div>
 
        <div class="nav-section">
            <div class="nav-section-label">Master Data</div>
            <a href="{{ route('barang.index') }}" class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Barang
            </a>
            <a href="{{ route('customer.index') }}" class="nav-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                Customer
            </a>
            <a href="{{ route('supplier.index') }}" class="nav-item {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Supplier
            </a>
            <a href="{{ route('pegawai.index') }}" class="nav-item {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Pegawai
            </a>
        </div>
 
        <div class="nav-section">
            <div class="nav-section-label">Transaksi</div>
            <a href="{{ route('nota.index') }}" class="nav-item {{ request()->routeIs('nota.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Nota Penjualan
            </a>
            <a href="{{ route('faktur.index') }}" class="nav-item {{ request()->routeIs('faktur.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Faktur Pembelian
            </a>
        </div>
    </nav>
 
    <!-- ── User & Logout ── -->
    <div style="padding:12px 10px;border-top:1px solid var(--border);margin-top:auto">
        <div style="padding:10px 10px 8px;border-radius:9px;background:var(--bg3);border:1px solid var(--border)">
            <div style="font-size:12px;font-weight:600;color:var(--text);margin-bottom:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                {{ Auth::user()->name ?? 'Admin' }}
            </div>
            <div style="font-size:11px;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:8px">
                {{ Auth::user()->email ?? '' }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="
                    width:100%;
                    display:flex;
                    align-items:center;
                    gap:7px;
                    padding:6px 9px;
                    background:rgba(239,68,68,0.08);
                    color:#f87171;
                    border:1px solid rgba(239,68,68,0.18);
                    border-radius:7px;
                    font-size:12.5px;
                    font-weight:600;
                    font-family:'Plus Jakarta Sans',sans-serif;
                    cursor:pointer;
                    transition:background 0.15s;
                " onmouseover="this.style.background='rgba(239,68,68,0.16)'"
                   onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</aside>
 
<!-- ── Main ── -->
<div class="main">
    <header class="topbar">
        <span class="page-title">@yield('title', 'Dashboard')</span>
        <div class="topbar-right">
            <span class="date-pill">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
            @yield('topbar-actions')
        </div>
    </header>
 
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
 
        @if($errors->any())
            <div class="alert alert-danger">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
        @endif
 
        @yield('content')
    </div>
</div>
 
@stack('scripts')
</body>
</html>