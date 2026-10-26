<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM Arsip Kementerian Perhubungan Laut</title>
    <!-- Kode untuk memanggil logo kecil di tab browser (Favicon) -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; height: 100vh; display: flex; overflow: hidden; }

        /* Container Split Layout */
        .login-container { display: flex; width: 100vw; height: 100vh; }

        /* Sisi Kiri: Background Gradasi Solid (Tanpa Gambar Orang) */
        .login-brand-side {
            flex: 1.2;
            background: linear-gradient(135deg, #0A192F 0%, #07111E 100%);
            color: white;
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Elemen Dekoratif Halus di Background Kiri */
        .login-brand-side::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(2, 132, 199, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
        }

        .brand-top { display: flex; align-items: center; gap: 12px; z-index: 2; }
        .brand-logo-badge {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .brand-title-text { font-size: 13.5px; font-weight: 700; letter-spacing: 0.5px; line-height: 1.2; color: #FFFFFF; }
        .brand-title-text span { display: block; font-size: 10.5px; font-weight: 600; color: #38BDF8; letter-spacing: 1px; text-transform: uppercase; }

        .brand-body { z-index: 2; max-width: 480px; margin-top: 20px; }
        .brand-subtitle { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #38BDF8; margin-bottom: 10px; }
        .brand-body h1 { font-size: 34px; font-weight: 800; line-height: 1.25; margin-bottom: 18px; letter-spacing: -0.5px; }
        .brand-body p { font-size: 14.5px; color: #94A3B8; line-height: 1.6; margin-bottom: 35px; }

        .feature-list { display: flex; flex-direction: column; gap: 14px; }
        .feature-item { display: flex; align-items: flex-start; gap: 12px; font-size: 13.5px; font-weight: 500; color: #E2E8F0; }
        .feature-icon-box { min-width: 28px; height: 28px; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
        .feature-text strong { display: block; font-size: 13.5px; font-weight: 700; color: #FFFFFF; }
        .feature-text span { font-size: 12.5px; color: #94A3B8; }

        .brand-footer { z-index: 2; font-size: 11.5px; color: #64748B; font-weight: 500; }

        /* Sisi Kanan: Form Login */
        .login-form-side {
            flex: 1;
            background: #F8FAFC;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow-y: auto;
            z-index: 10;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            text-align: center;
        }

        .form-center-logo {
            width: 56px;
            height: 56px;
            margin: 0 auto 18px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0284C7, #0369A1);
            color: #FFFFFF;
            border-radius: 16px;
            font-size: 26px;
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.25);
        }

        .form-header { margin-bottom: 25px; text-align: center; }
        .form-header h2 { font-size: 24px; font-weight: 800; color: #0F172A; letter-spacing: -0.5px; margin-bottom: 6px; }
        .form-header p { font-size: 13px; color: #64748B; font-weight: 500; }
        .form-header span.sub-desc { display: block; font-size: 11.5px; color: #94A3B8; margin-top: 4px; line-height: 1.4; }

        .form-group { margin-bottom: 18px; text-align: left; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 16px; color: #94A3B8; }
        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: #0F172A;
            transition: all 0.2s;
            outline: none;
        }
        .form-input:focus {
            background: #FFFFFF;
            border-color: #0284C7;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.1);
        }

        .form-options { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; font-size: 13px; text-align: left; }
        .remember-me { display: flex; align-items: center; gap: 8px; color: #475569; font-weight: 500; cursor: pointer; }
        .forgot-pass { color: #0284C7; text-decoration: none; font-weight: 600; }
        .forgot-pass:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #0284C7, #0369A1);
            color: #FFFFFF;
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
            transition: all 0.2s;
        }
        .btn-login:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        .form-footer-note {
            margin-top: 22px;
            font-size: 11.5px;
            color: #94A3B8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-top: 1px solid #F1F5F9;
            padding-top: 18px;
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FEE2E2;
            color: #DC2626;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
            font-weight: 500;
            text-align: left;
        }

        @media (max-width: 900px) {
            .login-brand-side { display: none; }
            .login-form-side { padding: 20px; }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Sisi Kiri: Background Gradasi Solid (Tanpa Foto Orang) -->
        <div class="login-brand-side">
            <div class="brand-top">
                <div class="brand-logo-badge">⚓</div>
                <div class="brand-title-text">
                    <span>Kementerian</span>
                    Perhubungan Laut
                </div>
            </div>

            <div class="brand-body">
                <div class="brand-subtitle">Sistem Informasi</div>
                <h1>Pengarsipan Dokumen Internal Pengolahan Data</h1>
                <p>Mendukung tata kelola arsip yang tertib, aman, dan terintegrasi untuk pelayanan publik yang lebih baik.</p>
                
                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon-box">📁</div>
                        <div class="feature-text">
                            <strong>Akses Cepat</strong>
                            <span>Temukan dokumen yang Anda butuhkan dengan mudah dan cepat.</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon-box">🛡️</div>
                        <div class="feature-text">
                            <strong>Aman & Terpercaya</strong>
                            <span>Sistem dilengkapi dengan keamanan berlapis dan hak akses.</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon-box">☁️</div>
                        <div class="feature-text">
                            <strong>Terintegrasi</strong>
                            <span>Seluruh arsip tersimpan secara terpusat dan terorganisir.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="brand-footer">
                &copy; 2026 Direktorat Jenderal Perhubungan Laut. All rights reserved.
            </div>
        </div>

        <!-- Sisi Kanan: Kotak Form Login -->
        <div class="login-form-side">
            <div class="login-card">
                
                <!-- Logo di Tengah Atas Form -->
                <div class="form-center-logo">
                    ⚓
                </div>

                <div class="form-header">
                    <h2>Login Sistem</h2>
                    <p>Pengarsipan Dokumen Kementerian</p>
                    <span class="sub-desc">Silakan masuk menggunakan akun yang telah diberikan oleh administrator.</span>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

               <form action="{{ route('login.proses') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Email / Username</label>
                        <div class="input-wrapper">
                            <span class="input-icon">✉️</span>
                            <input type="email" name="email" class="form-input" placeholder="Masukkan email atau username" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="password" class="form-input" placeholder="Masukkan kata sandi" required>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" style="accent-color: #0284C7; width: 16px; height: 16px;"> Ingat saya
                        </label>
                        <a href="#" class="forgot-pass">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="btn-login">Masuk</button>
                </form>

                <div class="form-footer-note">
                    🔒 Hanya untuk pegawai Kementerian Perhubungan Laut
                </div>
            </div>
        </div>
    </div>

</body>
</html>