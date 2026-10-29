<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM Arsip Kementerian Perhubungan Laut</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #F4F7FE;
            --sidebar-bg: #FFFFFF;
            --sidebar-border: #E2E8F0;
            --primary-color: #0284C7;
            --primary-hover: #0369A1;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --card-bg: #FFFFFF;
            --border-color: #E2E8F0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-body); color: var(--text-main); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar Styling */
        .sidebar { width: 280px; background-color: var(--sidebar-bg); display: flex; flex-direction: column; border-right: 1px solid var(--sidebar-border); box-shadow: 2px 0 10px rgba(0,0,0,0.01); transition: transform 0.3s ease; z-index: 100; }
        .sidebar-brand { padding: 25px 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--border-color); }
        .brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #0284C7, #0369A1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 12px rgba(2,132,199,0.25); }
        .brand-text h1 { color: #0F172A; font-size: 16px; font-weight: 700; letter-spacing: 0.5px; }
        .brand-text span { color: var(--primary-color); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

        .sidebar-menu { padding: 20px 15px; flex: 1; display: flex; flex-direction: column; gap: 6px; overflow-y: auto; }
        .menu-label { font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; margin: 15px 10px 8px 10px; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: #475569; text-decoration: none; border-radius: 10px; font-weight: 500; font-size: 13.5px; transition: all 0.2s ease; }
        .nav-link:hover { background: #F1F5F9; color: var(--primary-color); }
        .nav-link.active { background: #E0F2FE; color: #0369A1; font-weight: 700; }
        .nav-link span.icon { font-size: 16px; }

        .sidebar-footer { padding: 15px 20px; border-top: 1px solid var(--border-color); background: #FAFAFA; }
        .btn-logout { width: 100%; background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; padding: 10px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; text-decoration: none; }
        .btn-logout:hover { background: #DC2626; color: #FFFFFF; }

        /* Main Content Wrapper */
        .main-wrapper { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; width: 100%; }
        
        /* Top Navbar */
        .top-navbar { height: 70px; background: #FFFFFF; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; padding: 0 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.01); z-index: 10; gap: 15px; }
        .navbar-left { display: flex; align-items: center; gap: 15px; }
        .navbar-title { font-size: 13.5px; font-weight: 600; color: var(--text-muted); display: flex; align-items: center; gap: 8px; }
        
        /* Hamburger Menu Button */
        .hamburger-btn { display: none; background: #F1F5F9; color: var(--primary-color); border: 1px solid var(--border-color); width: 38px; height: 38px; border-radius: 8px; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; transition: 0.2s; }
        .hamburger-btn:hover { background: #E0F2FE; }

        /* Tombol Logout Cepat di Navbar Mobile */
        .mobile-logout-btn { display: none; background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; width: 38px; height: 38px; border-radius: 8px; align-items: center; justify-content: center; font-size: 16px; cursor: pointer; transition: 0.2s; }
        .mobile-logout-btn:hover { background: #DC2626; color: #FFFFFF; }

        .user-profile { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 36px; height: 36px; background: #E0F2FE; color: #0369A1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; border: 2px solid #BAE6FD; }
        .user-meta h4 { font-size: 13.5px; font-weight: 700; color: var(--text-main); white-space: nowrap; }
        .user-meta p { font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; white-space: nowrap; }

        /* Content Area */
        .content-area { flex: 1; overflow-y: auto; padding: 30px; }

        /* Backdrop untuk HP saat sidebar terbuka */
        .sidebar-backdrop { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); z-index: 90; backdrop-filter: blur(2px); }

        /* RESPONSIVE MEDIA QUERIES */
        @media (max-width: 992px) {
            .sidebar { position: fixed; top: 0; left: -280px; height: 100vh; transition: left 0.3s ease; }
            .sidebar.show { left: 0; }
            .sidebar-backdrop.show { display: block; }
            .hamburger-btn { display: flex; }
            .mobile-logout-btn { display: flex; }
            .top-navbar { padding: 0 15px; height: 60px; }
            .content-area { padding: 12px !important; }
            .navbar-title span { display: none; } 
            .user-meta { display: none; }
            
            .content-area div[style*="grid"], .content-area .grid, form .grid, .form-grid, .row {
                display: flex !important;
                flex-direction: column !important;
                gap: 12px !important;
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .content-area > div, .card, .stat-card {
                width: 100% !important;
                padding: 14px !important;
                margin-bottom: 10px !important;
                border-radius: 10px !important;
            }

            .stat-number, .number, h2, h3 { font-size: 22px !important; }

            input, select, textarea, .form-control {
                font-size: 13px !important;
                padding: 9px 12px !important;
                width: 100% !important;
            }

            .btn, button, a.btn, .btn-kembali {
                width: 100% !important;
                font-size: 13px !important;
                padding: 10px 14px !important;
                justify-content: center !important;
                text-align: center !important;
                display: flex !important;
            }
        }
    </style>
</head>
<body>

    <!-- Backdrop khusus mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="appSidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">⚓</div>
            <div class="brand-text">
                <h1>SIM ARSIP</h1>
                <span>Kemenhub Laut</span>
            </div>
        </div>

       <div class="sidebar-menu">
            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="menu-label">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="icon">📊</span> Dasbor Admin
                </a>
                <a href="{{ route('admin.arsip.index') }}" class="nav-link {{ request()->routeIs('admin.arsip.*') ? 'active' : '' }}">
                    <span class="icon">📁</span> Kelola Arsip
                </a>
                <a href="{{ route('admin.aktivitas') }}" class="nav-link {{ request()->routeIs('admin.aktivitas') ? 'active' : '' }}">
                    <span class="icon">🕒</span> Riwayat Aktivitas
                </a>

                <div class="menu-label">Sistem</div>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="icon">👥</span> Manajemen Pengguna
                </a>
            @else
                <div class="menu-label">Portal Pegawai</div>
                <a href="{{ route('pegawai.dashboard') }}" class="nav-link {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
                    <span class="icon">📊</span> Dasbor Pegawai
                </a>
                <a href="{{ route('pegawai.arsip.index') }}" class="nav-link {{ request()->routeIs('pegawai.arsip.*') ? 'active' : '' }}">
                    <span class="icon">🔍</span> Cari & Unduh Arsip
                </a>
            @endif
        </div>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">
                    <span>🚪</span> Keluar Sistem
                </button>
            </form>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="navbar-left">
                <button class="hamburger-btn" id="sidebarToggle" title="Menu Navigasi">
                    ☰
                </button>
                <div class="navbar-title">
                    <span>📍 Direktorat Jenderal Perhubungan Laut</span>
                </div>
            </div>
            <div class="user-profile">
                <!-- Tombol Logout Cepat khusus Tampilan Mobile (Menggunakan Form POST yang aman) -->
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="mobile-logout-btn" title="Keluar Sistem">
                        🚪
                    </button>
                </form>

                <div class="user-avatar">
                    {{ substr(Auth::user()->nama ?? Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="user-meta">
                    <h4>{{ Auth::user()->nama ?? Auth::user()->name ?? 'Administrator' }}</h4>
                    <p>Divisi Pengelolaan Sistem Data</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Skrip Interaktif untuk Sidebar Mobile -->
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const appSidebar = document.getElementById('appSidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function toggleSidebar() {
            appSidebar.classList.toggle('show');
            sidebarBackdrop.classList.toggle('show');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', toggleSidebar);
        }
    </script>
</body>
</html>