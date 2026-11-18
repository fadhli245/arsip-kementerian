<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#050d1f">
    <title>Masuk · SIM Arsip Kementerian Perhubungan Laut</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        (() => {
            let theme = null;
            try { theme = localStorage.getItem('arsip-theme'); } catch (_) {}
            if (theme !== 'light' && theme !== 'dark') {
                theme = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            }
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>
<body class="login-page">
    <div class="login-background" aria-hidden="true">
        <div class="login-aurora"></div>
        <div class="login-grid"></div>
        <div class="login-orbits">
            <div class="login-orbit login-orbit--one">
                <span class="orbit-node orbit-node--top">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 7.5A2.5 2.5 0 0 1 6 5h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 20H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 10h16"/></svg>
                </span>
                <span class="orbit-node orbit-node--bottom">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><ellipse cx="12" cy="5" rx="7.5" ry="2.8"/><path d="M4.5 5v7c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8V5M4.5 12v7c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-7M4.5 12c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8"/></svg>
                </span>
            </div>
            <div class="login-orbit login-orbit--two">
                <span class="orbit-node orbit-node--top">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3.5h8l4.5 4.6v12.4H6z"/><path d="M14 3.5v5h4.5M9 13h6M9 16.5h6"/></svg>
                </span>
                <span class="orbit-node orbit-node--bottom">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 1 1 8 0v3m-4 4v2"/></svg>
                </span>
            </div>
            <div class="login-orbit login-orbit--three">
                <span class="orbit-node orbit-node--top">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 8.5h18v11H3zM2 5h20v3.5H2z"/><path d="M9 12h6"/></svg>
                </span>
                <span class="orbit-node orbit-node--bottom">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 7.5A2.5 2.5 0 0 1 6 5h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 20H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 10h16"/></svg>
                </span>
            </div>
        </div>
        <i class="login-dust login-dust--1"></i><i class="login-dust login-dust--2"></i>
        <i class="login-dust login-dust--3"></i><i class="login-dust login-dust--4"></i>
        <i class="login-dust login-dust--5"></i><i class="login-dust login-dust--6"></i>
        <i class="login-dust login-dust--7"></i>
    </div>

    <main class="login-card">
        <button class="theme-toggle" type="button" data-theme-toggle aria-label="Beralih ke mode terang" aria-pressed="false">
            <svg class="theme-icon theme-icon--moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.2 15.1A8.6 8.6 0 0 1 8.9 3.8 8.7 8.7 0 1 0 20.2 15.1Z"/></svg>
            <svg class="theme-icon theme-icon--sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/></svg>
            <span data-theme-label>Mode terang</span>
        </button>
        <div class="brand">
            <span class="brand-mark"><img src="{{ asset('images/logo.png') }}" alt=""></span>
            <span class="brand-name"><span>KEMENTERIAN</span>Perhubungan Laut</span>
        </div>
        <div class="eyebrow">SISTEM INFORMASI ARSIP</div>
        <header class="form-header">
            <h1>Selamat datang</h1>
            <p>Masuk untuk mengakses arsip kedinasan secara aman dan terintegrasi.</p>
        </header>

        @if($errors->any())
            <div class="login-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form class="login-form" action="{{ route('login.proses') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrap">
                    <svg class="input-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5.5" width="17" height="13" rx="2.5"/><path d="m5 7 7 5.5L19 7"/></svg>
                    <input id="email" type="email" name="email" class="form-input" placeholder="Masukkan email terdaftar" value="{{ old('email') }}" autocomplete="username" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <svg class="input-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 1 1 8 0v3"/></svg>
                    <input id="password" type="password" name="password" class="form-input form-input--password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                    <button class="password-toggle" type="button" data-password-toggle aria-label="Tampilkan password" aria-pressed="false">
                        <svg class="eye-icon eye-icon--open" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                        <svg class="eye-icon eye-icon--closed" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18M10.6 6.2A10.8 10.8 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.2 16.2 0 0 1-3.1 3.7M6.2 6.2C3.8 7.7 2.5 12 2.5 12s3.4 6 9.5 6c.9 0 1.8-.2 2.6-.5"/></svg>
                    </button>
                </div>
            </div>
            <div class="form-options">
                <label class="remember-me"><input type="checkbox" name="remember"> <span>Ingat saya</span></label>
                <a href="#" class="forgot-pass">Lupa kata sandi?</a>
            </div>
            <button type="submit" class="btn-login"><span class="btn-spinner" aria-hidden="true"></span><span class="btn-label">Masuk ke sistem</span></button>
        </form>
        <div class="form-footer-note">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 5 6v5c0 4.7 2.9 8.1 7 10 4.1-1.9 7-5.3 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></svg>
            <span>Khusus pegawai Kementerian Perhubungan Laut</span>
        </div>
    </main>
</body>
</html>
