<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Arif — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
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
            --sidebar-w: 240px;
        }
 
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }
 
        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }
 
        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
 
        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: -0.5px;
        }
 
        .brand-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
 
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }
 
        .nav-section {
            margin-bottom: 24px;
        }
 
        .nav-section-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            padding: 0 8px;
            margin-bottom: 8px;
        }
 
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s;
            margin-bottom: 2px;
        }
 
        .nav-item:hover {
            background: var(--bg3);
            color: var(--text);
        }
 
        .nav-item.active {
            background: rgba(245,166,35,0.12);
            color: var(--accent);
        }
 
        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.8;
        }
 
        /* ── Main ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
 
        .topbar {
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
 
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }
 
        .topbar-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
 
        .content {
            padding: 28px;
            flex: 1;
        }
 
        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
        }
 
        .btn-primary {
            background: var(--accent);
            color: #0d0f14;
        }
        .btn-primary:hover { background: var(--accent2); }
 
        .btn-secondary {
            background: var(--bg3);
            color: var(--text);
            border: 1px solid var(--border2);
        }
        .btn-secondary:hover { background: var(--border); }
 
        .btn-danger {
            background: rgba(239,68,68,0.15);
            color: var(--red);
            border: 1px solid rgba(239,68,68,0.3);
        }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
 
        .btn-sm { padding: 6px 12px; font-size: 12.5px; }
 
        /* ── Cards ── */
        .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
 
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
 
        .card-title {
            font-weight: 700;
            font-size: 15px;
        }
 
        .card-body { padding: 20px; }
 
        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
 
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
 
        thead tr {
            border-bottom: 1px solid var(--border2);
        }
 
        th {
            padding: 11px 16px;
            text-align: left;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            font-weight: 600;
        }
 
        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }
 
        tbody tr:last-child td { border-bottom: none; }
 
        tbody tr:hover { background: rgba(255,255,255,0.02); }
 
        /* ── Forms ── */
        .form-group { margin-bottom: 20px; }
 
        label {
            display: block;
            font-size: 13px;
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
            padding: 10px 14px;
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
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
 
        .invalid-feedback {
            color: var(--red);
            font-size: 12px;
            margin-top: 5px;
        }
 
        .is-invalid {
            border-color: var(--red) !important;
        }
 
        /* ── Alert ── */
        .alert {
            padding: 13px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
 
        .alert-success {
            background: rgba(34,197,94,0.12);
            border: 1px solid rgba(34,197,94,0.3);
            color: var(--green);
        }
 
        .alert-danger {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.3);
            color: var(--red);
        }
 
        /* ── Badge ── */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
        }
 
        .badge-green { background: rgba(34,197,94,0.15); color: var(--green); }
        .badge-blue  { background: rgba(59,130,246,0.15); color: var(--blue); }
        .badge-gold  { background: rgba(245,166,35,0.15); color: var(--accent); }
 
        /* ── Stat card ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }
 
        .stat-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
 
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.07;
            transform: translate(20px, -20px);
        }
 
        .stat-card.gold::before  { background: var(--accent); }
        .stat-card.green::before { background: var(--green); }
        .stat-card.blue::before  { background: var(--blue); }
        .stat-card.red::before   { background: var(--red); }
 
        .stat-label {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
        }
 
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            line-height: 1;
        }
 
        .stat-card.gold  .stat-value { color: var(--accent); }
        .stat-card.green .stat-value { color: var(--green); }
        .stat-card.blue  .stat-value { color: var(--blue); }
        .stat-card.red   .stat-value { color: var(--red); }
 
        /* ── Pagination ── */
        .pagination {
            display: flex;
            gap: 6px;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
 
        .page-link {
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid var(--border2);
            background: var(--bg3);
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.15s;
        }
 
        .page-link:hover { color: var(--text); border-color: var(--accent); }
        .page-link.active { background: var(--accent); color: #0d0f14; border-color: var(--accent); font-weight: 700; }
 
        /* ── Detail item rows (nota/faktur create) ── */
        .item-row {
            display: grid;
            grid-template-columns: 1fr 100px 130px auto;
            gap: 10px;
            align-items: end;
            margin-bottom: 10px;
            background: var(--bg3);
            padding: 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
 
        #items-container .item-row:first-child .remove-item { display: none; }
 
        /* ── Misc ── */
        .text-muted { color: var(--muted); }
        .text-right { text-align: right; }
        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .flex { display: flex; }
        .gap-2 { gap: 8px; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .font-mono { font-family: 'Courier New', monospace; }
        .rupiah::before { content: 'Rp '; }
    </style>
</head>
<body>
 
<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-name">🏪 Toko Arif</div>
        <div class="brand-sub">Sistem Manajemen Toko</div>
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
</aside>
 
<!-- Main Content -->
<div class="main">
    <header class="topbar">
        <span class="page-title">@yield('title', 'Dashboard')</span>
        <div class="topbar-actions">
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
                <div>
                    @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
                </div>
            </div>
        @endif
 
        @yield('content')
    </div>
</div>
 
</body>
</html>