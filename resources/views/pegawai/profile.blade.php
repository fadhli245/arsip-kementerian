@extends('layouts.app')

@section('title', 'Profil Saya - SIM Arsip')

@section('content')
<section class="profile-page-card" style="max-width:760px;margin:0 auto;padding:26px;background:#fff;border:1px solid #e2e8f0;border-radius:16px">
    <span style="color:#2563eb;font-size:10px;font-weight:800;letter-spacing:1px">AKUN PEGAWAI</span>
    <h1 style="margin:8px 0 20px;color:#0f294a;font-size:22px">Profil Saya</h1>
    <dl style="display:grid;grid-template-columns:150px 1fr;gap:12px;margin:0 0 28px;font-size:13px">
        <dt style="color:#64748b">Nama</dt><dd style="margin:0;font-weight:700">{{ $pegawai->name }}</dd>
        <dt style="color:#64748b">Email</dt><dd style="margin:0">{{ $pegawai->email }}</dd>
        <dt style="color:#64748b">Unit kerja</dt><dd style="margin:0">{{ $pegawai->division ?: 'Belum ditentukan' }}</dd>
    </dl>
    <div id="ubah-kata-sandi" style="padding-top:20px;border-top:1px solid #e2e8f0">
        <h2 style="margin:0 0 15px;color:#0f294a;font-size:16px">Ubah Kata Sandi</h2>
        <form action="{{ route('pegawai.profile.password') }}" method="POST" style="display:grid;gap:12px;max-width:480px">
            @csrf @method('PUT')
            <label style="display:grid;gap:6px;color:#334155;font-size:12px;font-weight:700">Kata sandi lama
                <input type="password" name="password_lama" required autocomplete="current-password" style="padding:11px;border:1px solid #cbd5e1;border-radius:9px">
            </label>
            @error('password_lama')<small style="color:#b91c1c">{{ $message }}</small>@enderror
            <label style="display:grid;gap:6px;color:#334155;font-size:12px;font-weight:700">Kata sandi baru
                <input type="password" name="password" required minlength="8" autocomplete="new-password" style="padding:11px;border:1px solid #cbd5e1;border-radius:9px">
            </label>
            <label style="display:grid;gap:6px;color:#334155;font-size:12px;font-weight:700">Konfirmasi kata sandi baru
                <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" style="padding:11px;border:1px solid #cbd5e1;border-radius:9px">
            </label>
            @error('password')<small style="color:#b91c1c">{{ $message }}</small>@enderror
            <button type="submit" style="justify-self:start;padding:11px 16px;border:0;border-radius:9px;color:#fff;background:#0f294a;font-weight:700;cursor:pointer">Simpan Kata Sandi</button>
        </form>
    </div>
</section>
@endsection
