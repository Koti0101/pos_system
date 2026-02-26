<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS System – {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sb: 230px;
            --dark:   #0d1117;
            --panel:  #161b27;
            --card:   #1c2333;
            --hover:  #21293a;
            --blue:   #4f8ef7;
            --green:  #22c55e;
            --orange: #f59e0b;
            --red:    #ef4444;
            --purple: #a855f7;
            --t1:     #e6edf3;
            --t2:     #8b949e;
            --t3:     #484f58;
            --bdr:    rgba(255,255,255,0.07);
            --r:      10px;
        }
        .light-mode {
            --dark:   #f5f7fa;
            --panel:  #ffffff;
            --card:   #f9fafb;
            --hover:  #e0e6ed;
            --t1:     #0d1117;
            --t2:     #4b5563;
            --t3:     #6b7280;
            --bdr:    rgba(0,0,0,0.07);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; overflow: hidden; font-family: 'DM Sans', sans-serif; background: var(--dark); color: var(--t1); }
        .pos-wrap { display: flex; height: 100vh; width: 100vw; overflow: hidden; }

        /* SIDEBAR */
        .pos-sb {
            width: var(--sb); min-width: var(--sb); height: 100vh;
            background: var(--panel); border-right: 1px solid var(--bdr);
            display: flex; flex-direction: column; flex-shrink: 0;
            overflow: hidden;
            transition: width 0.25s, min-width 0.25s, background 0.25s, color 0.25s;
        }
        .pos-sb.mini { width: 56px; min-width: 56px; }
        .sb-top {
            display: flex; align-items: center; gap: 10px;
            padding: 15px 12px; border-bottom: 1px solid var(--bdr);
            white-space: nowrap; overflow: hidden; flex-shrink: 0;
        }
        .sb-icon {
            width: 34px; height: 34px; min-width: 34px;
            background: linear-gradient(135deg, var(--blue), var(--purple));
            border-radius: 8px; display: flex; align-items: center;
            justify-content: center; font-size: 16px; flex-shrink: 0;
        }
        .sb-name strong { display: block; font-family: 'Space Grotesk', sans-serif; font-size: 13.5px; font-weight: 700; }
        .sb-name small  { font-size: 10px; color: var(--t2); letter-spacing: 1px; text-transform: uppercase; }
        .pos-sb.mini .sb-name { display: none; }

        /* Nav */
        .sb-nav { flex: 1; min-height: 0; padding: 8px 7px; overflow-y: auto; overflow-x: hidden; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.15) transparent; }
        .sb-nav::-webkit-scrollbar { width: 3px; }
        .sb-nav::-webkit-scrollbar-track { background: transparent; }
        .sb-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        .sb-grp { font-size: 9px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--t3); padding: 10px 7px 5px; white-space: nowrap; }
        .pos-sb.mini .sb-grp { opacity: 0; pointer-events: none; height: 24px; }

        .sb-a {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 9px; border-radius: 8px;
            color: var(--t2); text-decoration: none;
            font-size: 13px; font-weight: 500;
            white-space: nowrap; overflow: hidden;
            transition: all 0.15s; margin-bottom: 1px; position: relative;
        }
        .sb-a:hover { background: var(--hover); color: var(--t1); }
        .sb-a.on { background: rgba(79,142,247,0.13); color: var(--blue); border: 1px solid rgba(79,142,247,0.17); }
        .sb-a.on::before { content: ''; position: absolute; left: 0; top: 22%; bottom: 22%; width: 3px; background: var(--blue); border-radius: 0 3px 3px 0; }
        .sb-a i { font-size: 15px; min-width: 17px; text-align: center; flex-shrink: 0; }
        .pos-sb.mini .sb-a span { display: none; }

        /* Footer */
        .sb-bot { padding: 8px 7px; border-top: 1px solid var(--bdr); display: flex; flex-direction: row; gap: 6px; flex-shrink: 0; }
        .sb-tog, #colorModeBtn { flex: 1; background: none; border: none; color: var(--t2); padding: 7px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s; }
        .sb-tog:hover, #colorModeBtn:hover { background: var(--hover); color: var(--t1); }

        /* MAIN */
        .pos-main { flex: 1; min-width: 0; height: 100vh; display: flex; flex-direction: column; overflow: hidden; background: var(--dark); color: var(--t1); transition: background 0.25s, color 0.25s; }

        /* TOPBAR */
        .pos-top { height: 56px; min-height: 56px; background: var(--panel); border-bottom: 1px solid var(--bdr); display: flex; align-items: center; justify-content: space-between; padding: 0 18px; flex-shrink: 0; gap: 10px; transition: background 0.25s, color 0.25s; }
        .mobile-menu-toggle { display: none; background: none; border: none; color: var(--t2); font-size: 24px; padding: 0 8px 0 0; cursor: pointer; }
        .mobile-menu-toggle:hover { color: var(--t1); }
        .top-title { font-family: 'Space Grotesk', sans-serif; font-size: 15px; font-weight: 600; white-space: nowrap; }
        .top-right { display: flex; align-items: center; gap: 7px; flex-shrink: 0; }
        .top-btn { height: 34px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--bdr); background: var(--card); color: var(--t2); font-size: 12.5px; font-weight: 600; display: flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.15s; text-decoration: none; white-space: nowrap; }
        .top-btn:hover { background: var(--hover); color: var(--t1); }
        .top-btn.pos { background: linear-gradient(135deg, var(--blue), var(--purple)); border: none; color: white; }
        .top-btn.pos:hover { opacity: 0.9; color: white; }
        .top-btn.out { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: var(--red); }
        .top-btn.out:hover { background: var(--red); color: white; border-color: var(--red); }

        /* CONTENT */
        .pos-page { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 18px; transition: background 0.25s, color 0.25s; }

        /* STAT CARDS */
        .sc { background: var(--card); border: 1px solid var(--bdr); border-radius: var(--r); padding: 18px; position: relative; overflow: hidden; transition: transform 0.15s, box-shadow 0.15s, background 0.25s; }
        .sc:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.3); }
        .sc::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; }
        .sc.b::after { background: linear-gradient(90deg, var(--blue), transparent); }
        .sc.g::after { background: linear-gradient(90deg, var(--green), transparent); }
        .sc.o::after { background: linear-gradient(90deg, var(--orange), transparent); }
        .sc-ico { width: 40px; height: 40px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 17px; margin-bottom: 12px; }
        .sc-ico.b { background: rgba(79,142,247,0.15); color: var(--blue); }
        .sc-ico.g { background: rgba(34,197,94,0.15); color: var(--green); }
        .sc-ico.o { background: rgba(245,158,11,0.15); color: var(--orange); }
        .sc-lbl { font-size: 10.5px; color: var(--t2); text-transform: uppercase; letter-spacing: 0.6px; font-weight: 600; margin-bottom: 5px; }
        .sc-val { font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; line-height: 1; }
        .sc-sub { font-size: 11.5px; color: var(--t2); margin-top: 7px; }

        /* CARDS */
        .cc { background: var(--card); border: 1px solid var(--bdr); border-radius: var(--r); overflow: hidden; transition: background 0.25s; }
        .cc-h { padding: 13px 16px; border-bottom: 1px solid var(--bdr); display: flex; align-items: center; justify-content: space-between; }
        .cc-t { font-family: 'Space Grotesk', sans-serif; font-size: 13.5px; font-weight: 600; }

        /* TABLE */
        .pt { width: 100%; border-collapse: collapse; }
        .pt th { padding: 9px 14px; font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--t3); border-bottom: 1px solid var(--bdr); background: rgba(255,255,255,0.02); text-align: left; }
        .pt td { padding: 11px 14px; font-size: 13px; border-bottom: 1px solid var(--bdr); color: var(--t2); }
        .pt tr:last-child td { border-bottom: none; }
        .pt tbody tr:hover td { background: rgba(255,255,255,0.02); color: var(--t1); }
        .pt td:first-child { color: var(--t1); font-weight: 500; }

        /* BADGES */
        .bg-g { background: rgba(34,197,94,0.15); color: var(--green); padding: 3px 8px; border-radius: 5px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .bg-b { background: rgba(79,142,247,0.15); color: var(--blue); padding: 3px 8px; border-radius: 5px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .bg-o { background: rgba(245,158,11,0.15); color: var(--orange); padding: 3px 8px; border-radius: 5px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .bg-r { background: rgba(239,68,68,0.15); color: var(--red); padding: 3px 8px; border-radius: 5px; font-size: 11px; font-weight: 600; white-space: nowrap; }

        /* BUTTONS */
        .btn-b { background: rgba(79,142,247,0.15); color: var(--blue); border: 1px solid rgba(79,142,247,0.2); padding: 5px 11px; border-radius: 7px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; cursor: pointer; }
        .btn-b:hover { background: var(--blue); color: white; }

        .av { width: 26px; height: 26px; border-radius: 50%; background: linear-gradient(135deg, var(--purple), var(--blue)); display: inline-flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; color: white; flex-shrink: 0; }

        .fl-ok  { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); color: var(--green); border-radius: var(--r); padding: 9px 14px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; font-size: 13px; }
        .fl-err { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: var(--red); border-radius: var(--r); padding: 9px 14px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; font-size: 13px; }

        .emp { text-align: center; padding: 36px; color: var(--t3); }
        .emp i { font-size: 26px; margin-bottom: 8px; display: block; }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        /* MOBILE */
        @media (max-width: 768px) {
            .mobile-menu-toggle { display: flex !important; }
            .pos-sb { position: fixed; top: 0; left: -100%; width: 85% !important; max-width: 300px; z-index: 1050; transition: left 0.3s ease; box-shadow: 2px 0 10px rgba(0,0,0,0.3); }
            .pos-sb.mobile-open { left: 0; }
            .pos-sb.mini { width: 85% !important; min-width: unset; }
            .pos-sb.mini .sb-name, .pos-sb.mini .sb-a span { display: block !important; }
            .pos-sb.mini .sb-grp { opacity: 1 !important; height: auto !important; pointer-events: auto !important; }
            .pos-main { width: 100%; }
            .top-btn span { display: none; }
            .top-btn { padding: 0 8px; }
            .top-btn i { margin: 0; font-size: 18px; }
            .top-title { font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; }
            .row { --bs-gutter-x: 10px; }
            .col-md-3, .col-md-4, .col-md-6, .col-lg-3, .col-lg-4, .col-lg-6 { width: 100%; margin-bottom: 10px; }
            .sc { padding: 14px; }
            .sc-val { font-size: 20px; }
            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .pt { min-width: 600px; }
            .cc { margin-bottom: 15px; }
            .pos-page { padding: 12px; }
            input, select, textarea { font-size: 16px !important; }
        }
        @media (max-width: 480px) {
            .top-title { max-width: 120px; }
        }
        body.sidebar-open { overflow: hidden; }
    </style>
</head>
<body>
<div class="pos-wrap">

    <aside class="pos-sb" id="sidebar">
        <div class="sb-top">
            <div class="sb-icon">🏪</div>
            <div class="sb-name">
                <strong>POS System</strong>
                <small>Gracious Store</small>
            </div>
        </div>

        <nav class="sb-nav">
            <div class="sb-grp">Main Menu</div>

            <a href="{{ route('dashboard') }}" class="sb-a {{ request()->routeIs('dashboard') ? 'on' : '' }}">
                <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
            </a>

            <a href="{{ route('pos.index') }}" class="sb-a {{ request()->routeIs('pos.*') ? 'on' : '' }}">
                <i class="bi bi-bag-plus"></i><span>New Sale (POS)</span>
            </a>

            <a href="{{ route('sales.index') }}" class="sb-a {{ request()->routeIs('sales.*') ? 'on' : '' }}">
                <i class="bi bi-receipt"></i><span>Sales History</span>
            </a>

            <div class="sb-grp">Inventory</div>

            <a href="{{ route('products.index') }}" class="sb-a {{ request()->routeIs('products.*') ? 'on' : '' }}">
                <i class="bi bi-box-seam"></i><span>Products</span>
            </a>

            {{-- Categories: Admin only --}}
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('categories.index') }}" class="sb-a {{ request()->routeIs('categories.*') ? 'on' : '' }}">
                <i class="bi bi-tags"></i><span>Categories</span>
            </a>
            @endif

            {{-- Reports: Admin only --}}
            @if(auth()->user()->role === 'admin')
            <div class="sb-grp">Reports</div>
            <a href="{{ route('reports.index') }}" class="sb-a {{ request()->routeIs('reports.*') ? 'on' : '' }}">
                <i class="bi bi-bar-chart"></i><span>Sales Report</span>
            </a>
            @endif

            {{-- Admin only section --}}
            @if(auth()->user()->role === 'admin')
            <div class="sb-grp">Admin</div>
            <a href="{{ route('users.index') }}" class="sb-a {{ request()->routeIs('users.*') ? 'on' : '' }}">
                <i class="bi bi-people"></i><span>Manage Users</span>
            </a>
            @endif

            <div class="sb-grp">Account</div>
            <a href="{{ route('profile.edit') }}" class="sb-a {{ request()->routeIs('profile.*') ? 'on' : '' }}">
                <i class="bi bi-person-circle"></i><span>My Profile</span>
            </a>

        </nav>

        <div class="sb-bot">
            <button class="sb-tog" onclick="toggleSb()" id="sbBtn" title="Toggle sidebar">
                <i class="bi bi-layout-sidebar-reverse" id="sbIco"></i>
            </button>
            <button id="colorModeBtn" title="Toggle color mode">
                <i class="bi bi-moon" id="colorModeIcon"></i>
            </button>
        </div>
    </aside>

    <div class="pos-main">
        <header class="pos-top">
            <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleMobileSidebar()">
                <i class="bi bi-list"></i>
            </button>

            <span class="top-title">@yield('page-title', 'Dashboard')</span>

            <div class="top-right">
                @if(auth()->user()->role === 'admin')
                    <span class="badge bg-danger" style="padding:6px 12px;">Admin</span>
                @else
                    <span class="badge bg-info" style="padding:6px 12px;">Cashier</span>
                @endif

                <a href="{{ route('pos.index') }}" class="top-btn pos">
                    <i class="bi bi-plus-lg"></i><span> New Sale</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="top-btn">
                    <i class="bi bi-person"></i><span> Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="top-btn out">
                        <i class="bi bi-box-arrow-right"></i><span> Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="pos-page">
            @if(session('success'))
            <div class="fl-ok"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="fl-err"><i class="bi bi-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSb() {
        const sb  = document.getElementById('sidebar');
        const ico = document.getElementById('sbIco');
        const mini = sb.classList.toggle('mini');
        ico.className = mini ? 'bi bi-layout-sidebar' : 'bi bi-layout-sidebar-reverse';
        localStorage.setItem('sb_mini', mini ? '1' : '0');
    }

    const colorModeBtn  = document.getElementById('colorModeBtn');
    const colorModeIcon = document.getElementById('colorModeIcon');

    document.addEventListener('DOMContentLoaded', () => {
        localStorage.removeItem('sb_mini');
        document.getElementById('sidebar').classList.remove('mini');
        document.getElementById('sbIco').className = 'bi bi-layout-sidebar-reverse';

        const mode = localStorage.getItem('color_mode') || 'dark';
        if (mode === 'light') {
            document.body.classList.add('light-mode');
            colorModeIcon.className = 'bi bi-sun';
        }
    });

    colorModeBtn.addEventListener('click', () => {
        const isLight = document.body.classList.toggle('light-mode');
        colorModeIcon.className = isLight ? 'bi bi-sun' : 'bi bi-moon';
        localStorage.setItem('color_mode', isLight ? 'light' : 'dark');
    });

    function toggleMobileSidebar() {
        const sb = document.getElementById('sidebar');
        sb.classList.toggle('mobile-open');
        document.body.classList.toggle('sidebar-open', sb.classList.contains('mobile-open'));
    }

    document.addEventListener('click', function(event) {
        const sb = document.getElementById('sidebar');
        const toggle = document.getElementById('mobileMenuToggle');
        if (window.innerWidth <= 768) {
            if (!sb.contains(event.target) && !toggle.contains(event.target)) {
                sb.classList.remove('mobile-open');
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.body.classList.remove('sidebar-open');
        }
    });
</script>
@yield('scripts')
</body>
</html>
