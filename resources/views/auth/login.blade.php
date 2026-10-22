<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengarsipan - Kementerian</title>
    
    <!-- KITA TARUH CSS-NYA LANGSUNG DI SINI BIAR PASTI TERBACA -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap');
        :root{--navy:#102d4f;--navy2:#173f69;--blue:#245c91;--ink:#18283b;--muted:#718096;--line:#dce4ed;--bg:#f5f7fb;--white:#fff;--red:#a83232;--green:#176b49}
        *{box-sizing:border-box}html,body{margin:0;min-height:100%;font-family:'DM Sans',Arial,sans-serif;color:var(--ink)}body{background:var(--bg)}button,input{font:inherit}.login-page{min-height:100vh;display:grid;grid-template-columns:minmax(360px, .92fr) 1.08fr}.brand-panel{position:relative;display:flex;flex-direction:column;justify-content:space-between;overflow:hidden;padding:clamp(36px,6vw,86px);color:white;background:radial-gradient(ellipse at 10% 90%,rgba(64,126,181,.34),transparent 45%),linear-gradient(145deg,#0b223d,#143e67 68%,#1a4a76)}.brand-panel:after{content:"";position:absolute;width:430px;height:430px;border:1px solid rgba(255,255,255,.09);border-radius:50%;right:-230px;top:13%;box-shadow:0 0 0 48px rgba(255,255,255,.025),0 0 0 96px rgba(255,255,255,.02);pointer-events:none}.brand-content,.brand-footer{position:relative;z-index:1}.brand-mark{width:64px;height:64px;display:grid;place-items:center;border:1px solid rgba(255,255,255,.35);background:rgba(255,255,255,.08);border-radius:18px;margin-bottom:42px}.brand-mark svg{width:39px;height:39px}.eyebrow,.form-eyebrow{font-size:11px;letter-spacing:2.3px;font-weight:700}.eyebrow{color:#b7d4ef;margin:0 0 16px}.brand-panel h1{font-family:Manrope,sans-serif;font-size:clamp(28px,3vw,42px);line-height:1.22;letter-spacing:-1.2px;margin:0;max-width:540px}.brand-description{font-size:15px;line-height:1.8;color:#d3e0ed;max-width:440px;margin:24px 0 30px}.brand-features{display:grid;gap:17px;color:#eef5fb;font-size:14px}.brand-features>div{display:flex;align-items:center;gap:12px}.feature-icon{width:22px;height:22px;display:grid;place-items:center;border-radius:50%;background:rgba(159,209,190,.16);color:#b9e4d1;font-size:12px}.brand-footer{font-size:11px;line-height:1.6;color:#b8ccdf;border-top:1px solid rgba(255,255,255,.15);padding-top:22px;margin-top:54px}.form-panel{display:flex;flex-direction:column;justify-content:center;align-items:center;padding:48px 28px 20px;background:linear-gradient(135deg,#f9fbfe,#f1f5fa)}.login-card{width:100%;max-width:420px;background:var(--white);border:1px solid #e7edf4;border-radius:20px;padding:clamp(27px,4vw,43px);box-shadow:0 18px 55px rgba(22,49,78,.08)}.mobile-mark{display:none}.form-eyebrow{color:var(--blue);margin:0 0 10px}.login-card h2{font-family:Manrope,sans-serif;font-size:27px;letter-spacing:-.8px;margin:0 0 10px;color:#142b45}.form-intro{font-size:13px;line-height:1.7;color:var(--muted);margin:0 0 28px}.field-group{margin-bottom:20px}.field-group label{display:block;font-size:12px;font-weight:700;margin-bottom:9px;color:#2b3b4f}.input-wrap{height:49px;border:1px solid var(--line);border-radius:9px;display:flex;align-items:center;gap:11px;padding:0 13px;background:#fff;transition:border-color .2s,box-shadow .2s}.input-wrap:focus-within{border-color:#5284b5;box-shadow:0 0 0 3px rgba(48,105,159,.1)}.input-wrap svg{width:18px;height:18px;stroke:#8292a5;stroke-width:1.6;fill:none;flex-shrink:0}.input-wrap input{width:100%;min-width:0;border:0;outline:0;color:var(--ink);font-size:13px;background:transparent}.input-wrap input::placeholder{color:#a0acb9}.toggle-password{border:0;background:transparent;color:var(--blue);font-size:11px;font-weight:700;white-space:nowrap;cursor:pointer;padding:5px 0}.remember-row{display:flex;align-items:center;gap:9px;color:#65758a;font-size:12px;margin:3px 0 24px;cursor:pointer}.remember-row input{accent-color:var(--blue);width:15px;height:15px}.submit-button{width:100%;height:49px;border:0;border-radius:9px;background:linear-gradient(100deg,#163e68,#245c91);color:white;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:12px;cursor:pointer;box-shadow:0 7px 15px rgba(26,72,115,.16);transition:transform .15s,filter .15s}.submit-button:hover{filter:brightness(1.08);transform:translateY(-1px)}.submit-button span{font-size:18px;font-weight:400}.security-note{display:flex;gap:9px;align-items:flex-start;margin-top:25px;padding-top:20px;border-top:1px solid #edf1f5;color:#8794a4;font-size:10px;line-height:1.6}.lock-dot{color:#4d8d73;font-size:10px;padding-top:1px}.alert{font-size:12px;line-height:1.5;border-radius:8px;padding:11px 13px;margin:-7px 0 20px}.alert-error{color:var(--red);background:#fff0ef;border:1px solid #f5d4d1}.alert-success{color:var(--green);background:#edf9f2;border:1px solid #ccebd8}.field-error{display:block;margin-top:6px;color:var(--red);font-size:11px}.page-footer{max-width:420px;width:100%;text-align:center;color:#98a4b3;font-size:10px;margin-top:25px;line-height:1.6}
        @media(max-width:850px){.login-page{grid-template-columns:1fr}.brand-panel{display:none}.form-panel{min-height:100vh;padding:28px 18px;background:radial-gradient(ellipse at top,#e7eff8,transparent 55%),var(--bg)}.mobile-mark{display:grid;place-items:center;width:48px;height:48px;border-radius:14px;background:var(--navy);color:#fff;font-size:27px;margin-bottom:25px}.login-card{max-width:430px}.page-footer{margin-top:22px}}@media(max-width:420px){.form-panel{padding:18px 12px}.login-card{padding:26px 21px;border-radius:16px}.login-card h2{font-size:24px}}
    </style>
</head>
<body>
    <div class="login-page">
        <!-- Panel Kiri yang Biru -->
        <div class="brand-panel">
            <div class="brand-content">
                <div class="brand-mark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <div class="eyebrow">REPUBLIK INDONESIA</div>
                <h1>Sistem Informasi Pengarsipan Digital</h1>
                <p class="brand-description">
                    Layanan repositori dokumen terintegrasi untuk pengelolaan, verifikasi, dan pencarian arsip resmi.
                </p>
                <div class="brand-features">
                    <div>
                        <span class="feature-icon">✓</span>
                        <span>Akses Dokumen Berbasis Hak Otorisasi</span>
                    </div>
                    <div>
                        <span class="feature-icon">✓</span>
                        <span>Penyimpanan Aman Terklasifikasi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Kanan untuk Form Login -->
        <div class="form-panel">
            <div class="login-card">
                <div class="form-eyebrow">AKSES MASUK</div>
                <h2>Masuk ke Akun</h2>
                
                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Perhatikan kode form action ini, sudah nyambung ke Laravel -->
                <form action="{{ route('login.proses') }}" method="POST">
                    @csrf
                    
                    <div class="field-group">
                        <label>EMAIL</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <!-- Nama input ini harus "email" -->
                            <input type="email" name="email" placeholder="contoh: admin@kementerian.go.id" required>
                        </div>
                    </div>

                    <div class="field-group">
                        <label>KATA SANDI</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <!-- Nama input ini harus "password" -->
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="submit-button">
                        <span>Masuk ke Sistem</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>