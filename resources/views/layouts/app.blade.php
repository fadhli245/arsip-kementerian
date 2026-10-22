<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIM Arsip Kementerian')</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap');
        
        :root {
            --navy: #102d4f; --navy2: #173f69; --blue: #245c91; --ink: #18283b;
            --muted: #718096; --line: #dce4ed; --bg: #f5f7fb; --white: #fff;
            --red: #a83232; --green: #176b49;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--ink); display: flex; min-height: 100vh; }
        
        /* CSS Sidebar */
        .sidebar { width: 260px; background: var(--navy); color: white; padding: 25px 20px; display: flex; flex-direction: column; }
        .sidebar h2 { font-family: 'Manrope', sans-serif; font-size: 20px; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid var(--navy2); }
        .nav-link { color: #d3e0ed; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 8px; display: block; transition: 0.2s; font-size: 14px; font-weight: 500; }
        .nav-link:hover { background: var(--blue); color: white; }
        .logout-btn { background: var(--red); color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-family: inherit; font-weight: 600; width: 100%; text-align: left; font-size: 14px; transition: 0.2s; }
        .logout-btn:hover { filter: brightness(1.1); }
        
        /* CSS Konten Utama */
        .main-content { flex: 1; padding: 40px; overflow-y: auto; width: 100%; }
    </style>
</head>
<body>

    <!-- SIDEBAR MENU KIRI -->
    <div class="sidebar">
        <h2>🏛️ SIM Arsip</h2>
        
        @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="nav-link">📊 Dashboard Admin</a>
            <a href="{{ route('admin.arsip.index') }}" class="nav-link">📂 Kelola Arsip</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link">👥 Manajemen Pengguna</a>
        @else
            <a href="{{ route('pegawai.dashboard') }}" class="nav-link">📊 Dashboard Pegawai</a>
            <a href="{{ route('pegawai.arsip.index') }}" class="nav-link">🔍 Cari Arsip</a>
        @endif

        <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
            @csrf
            <button type="submit" class="logout-btn">🚪 Keluar Sistem</button>
        </form>
    </div>
    <!-- AREA KONTEN KANAN -->
    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>