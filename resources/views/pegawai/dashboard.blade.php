@extends('layouts.app')

@section('title', 'Dashboard Pegawai - SIM Arsip')

@section('content')
<style>
    .welcome-card { background: white; padding: 35px 40px; border-radius: 18px; border: 1px solid #e2eaf2; box-shadow: 0 4px 15px rgba(22, 49, 78, 0.03); margin-bottom: 25px; }
    .welcome-card h1 { font-family: 'Manrope', sans-serif; font-size: 24px; color: var(--navy); margin: 8px 0; }
    .welcome-card p { color: var(--muted); font-size: 14px; max-width: 600px; line-height: 1.6; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
    .stat-card { background: white; padding: 25px; border-radius: 16px; border: 1px solid #e2eaf2; box-shadow: 0 4px 15px rgba(22, 49, 78, 0.03); display: flex; flex-direction: column; gap: 8px; transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card strong { font-family: 'Manrope', sans-serif; font-size: 32px; color: var(--navy); line-height: 1; }
    .stat-card span { font-size: 13px; font-weight: 600; color: var(--muted); }
</style>

<div class="welcome-card">
    <div style="color: var(--blue); font-size: 11px; font-weight: 700; letter-spacing: 2px;">PANEL PEGAWAI</div>
    <!-- Cek apakah kolom di database kamu 'nama' atau 'name' -->
    <h1>Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
    <p>Akses dokumen internal yang diizinkan, cari arsip kedinasan, atau ajukan pengarsipan dokumen baru ke pengelola.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <strong>34</strong>
        <span>Dokumen Boleh Diakses</span>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--green);">
        <strong style="color: var(--green);">5</strong>
        <span>Pengajuan Disetujui</span>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--blue);">
        <strong style="color: var(--blue);">2</strong>
        <span>Pengajuan Diproses</span>
    </div>
</div>
@endsection