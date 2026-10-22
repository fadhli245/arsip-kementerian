@extends('layouts.app')

@section('title', 'Cari Arsip Dokumen - SIM Arsip')

@section('content')
<style>
    .search-box-card {
        background: white;
        border: 1px solid #e2eaf2;
        border-radius: 18px;
        padding: 25px 30px;
        box-shadow: 0 4px 20px rgba(22, 49, 78, 0.04);
        margin-bottom: 30px;
    }
    .search-title h2 { color: var(--navy); font-family: 'Manrope', sans-serif; font-size: 22px; margin-bottom: 6px; }
    .search-title p { color: var(--muted); font-size: 14px; margin-bottom: 20px; }
    
    .search-form { display: flex; gap: 12px; flex-wrap: wrap; }
    .form-input-wrap { flex: 2; min-width: 240px; }
    .form-select-wrap { flex: 1; min-width: 180px; }
    
    .form-control-custom {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--line);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        outline: none;
        background: #fbfcfd;
    }
    .form-control-custom:focus { border-color: var(--blue); background: #fff; box-shadow: 0 0 0 3px rgba(36,92,145,0.1); }
    
    .btn-search {
        background: linear-gradient(100deg, #163e68, #245c91);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-reset {
        background: #f0f4f8;
        color: var(--muted);
        border: 1px solid var(--line);
        padding: 12px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
    }

    /* Cards Grid untuk Hasil Arsip */
    .arsip-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
    .arsip-card {
        background: white;
        border: 1px solid #e2eaf2;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(22, 49, 78, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .arsip-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(22, 49, 78, 0.08); }
    
    .card-top { margin-bottom: 15px; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; margin-bottom: 12px; }
    .badge-surat { background: #edf9f2; color: #176b49; }
    .badge-laporan { background: #eef5fb; color: #245c91; }
    .badge-sk { background: #f3f0ff; color: #6d28d9; }
    
    .doc-number { font-size: 12px; font-weight: 700; color: var(--muted); margin-bottom: 4px; }
    .doc-title { font-family: 'Manrope', sans-serif; font-size: 16px; font-weight: 800; color: var(--navy); line-height: 1.4; margin-bottom: 8px; }
    .doc-desc { font-size: 13px; color: #5a6a7e; line-height: 1.5; margin-bottom: 15px; }
    .doc-date { font-size: 12px; color: var(--muted); display: flex; align-items: center; gap: 6px; }

    .card-bottom { border-top: 1px solid #f0f4f8; padding-top: 15px; margin-top: 15px; display: flex; justify-content: space-between; align-items: center; }
    .btn-download-pdf {
        background: #edf9f2;
        color: #176b49;
        border: 1px solid #ccebd8;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-download-pdf:hover { background: #176b49; color: white; }
</style>

<!-- Panel Pencarian -->
<div class="search-box-card">
    <div class="search-title">
        <h2>🔍 Pusat Pencarian Dokumen Arsip</h2>
        <p>Cari dokumen resmi kementerian berdasarkan judul, nomor surat, atau kategori dokumen.</p>
    </div>

    <form action="{{ route('pegawai.arsip.index') }}" method="GET" class="search-form">
        <div class="form-input-wrap">
            <input type="text" name="search" class="form-control-custom" placeholder="Ketik kata kunci atau nomor dokumen..." value="{{ request('search') }}">
        </div>

        <div class="form-select-wrap">
            <select name="kategori" class="form-control-custom">
                <option value="">Semua Kategori</option>
                <option value="Surat Masuk" {{ request('kategori') == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                <option value="Surat Keluar" {{ request('kategori') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                <option value="Surat Keputusan (SK)" {{ request('kategori') == 'Surat Keputusan (SK)' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                <option value="Laporan Kegiatan" {{ request('kategori') == 'Laporan Kegiatan' ? 'selected' : '' }}>Laporan Kegiatan</option>
            </select>
        </div>

        <button type="submit" class="btn-search">Cari Dokumen</button>
        @if(request('search') || request('kategori'))
            <a href="{{ route('pegawai.arsip.index') }}" class="btn-reset">Reset</a>
        @endif
    </form>
</div>

<!-- Hasil Daftar Arsip -->
<div class="arsip-grid">
    @forelse($arsips as $arsip)
        <div class="arsip-card">
            <div class="card-top">
                <span class="badge {{ $arsip->kategori == 'Surat Masuk' ? 'badge-surat' : ($arsip->kategori == 'Surat Keputusan (SK)' ? 'badge-sk' : 'badge-laporan') }}">
                    {{ $arsip->kategori }}
                </span>
                <div class="doc-number">📄 {{ $arsip->nomor_dokumen }}</div>
                <div class="doc-title">{{ $arsip->judul }}</div>
                <div class="doc-desc">{{ Str::limit($arsip->keterangan ?? 'Tidak ada ringkasan keterangan.', 90) }}</div>
                <div class="doc-date">📅 {{ \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d F Y') }}</div>
            </div>

            <div class="card-bottom">
                <span style="font-size: 11px; color: var(--green); font-weight: 700;">● Berkas Siap</span>
                <a href="{{ route('pegawai.arsip.download', $arsip->id) }}" class="btn-download-pdf">Unduh PDF</a>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; background: white; border: 1px solid #e2eaf2; border-radius: 16px; padding: 50px; text-align: center; color: var(--muted);">
            <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
            <h3 style="color: var(--navy); margin-bottom: 6px;">Dokumen Tidak Ditemukan</h3>
            <p style="font-size: 14px;">Tidak ada arsip yang sesuai dengan kata kunci pencarian Anda.</p>
        </div>
    @endforelse
</div>
@endsection