@extends('layouts.app')

@section('title', 'Master Kategori & Klasifikasi - SIM Arsip')

@section('content')
<style>
    .category-page{display:grid;gap:16px}.category-panel{background:#fff;border:1px solid #E2E8F0;border-radius:13px;padding:20px}.category-panel h1{font-size:21px;color:#0A2558;margin-bottom:7px}.category-panel p{color:#64748B;font-size:13px}.category-form{display:grid;grid-template-columns:2fr 1fr auto;gap:10px;margin-top:16px}.category-form input,.category-row input{padding:10px;border:1px solid #CBD5E1;border-radius:8px;min-width:0}.category-form button,.category-row button{padding:9px 12px;border:0;background:#0A2558;color:white;border-radius:8px;font-weight:700;cursor:pointer}.category-table{width:100%;border-collapse:collapse;margin-top:16px;font-size:13px}.category-table th,.category-table td{text-align:left;padding:11px;border-bottom:1px solid #E2E8F0}.category-table th{color:#475569;background:#F8FAFC}.category-row{display:flex;gap:7px;align-items:center}.category-row input:first-child{flex:1}.category-row input:nth-child(2){width:140px}.category-row .toggle{background:#F1F5F9;color:#334155}.category-active{color:#15803D;font-weight:700}.category-inactive{color:#B91C1C;font-weight:700}@media(max-width:720px){.category-form{grid-template-columns:1fr}.category-row{flex-wrap:wrap}.category-row input:first-child{flex-basis:100%}}
</style>
<div class="category-page">
    <section class="category-panel">
        <h1>Master Data Kategori &amp; Klasifikasi</h1>
        <p>Kelola kategori surat dan kode klasifikasi. Kategori yang dinonaktifkan tidak muncul untuk arsip baru, tetapi arsip lama tetap tersimpan.</p>
        <form class="category-form" action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <input name="name" value="{{ old('name') }}" maxlength="100" required placeholder="Nama kategori, misalnya Surat Masuk">
            <input name="classification_code" value="{{ old('classification_code') }}" maxlength="50" placeholder="Kode klasifikasi">
            <button type="submit">Tambah Kategori</button>
        </form>
    </section>
    <section class="category-panel" style="overflow:auto">
        <table class="category-table">
            <thead><tr><th>Kategori</th><th>Kode</th><th>Jumlah Arsip</th><th>Status</th><th>Ubah</th></tr></thead>
            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td colspan="5">
                        <form class="category-row" action="{{ route('admin.kategori.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input name="name" value="{{ $category->name }}" maxlength="100" required aria-label="Nama kategori">
                            <input name="classification_code" value="{{ $category->classification_code }}" maxlength="50" aria-label="Kode klasifikasi" placeholder="Kode">
                            <span style="min-width:75px">{{ $category->archives_count }} arsip</span>
                            <span class="{{ $category->is_active ? 'category-active' : 'category-inactive' }}" style="min-width:65px">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            <button type="submit">Simpan</button>
                        </form>
                        <form action="{{ route('admin.kategori.status', $category) }}" method="POST" style="margin-top:7px;text-align:right">
                            @csrf
                            @method('PATCH')
                            <button class="category-row toggle" type="submit" style="display:inline-block">{{ $category->is_active ? 'Nonaktifkan kategori' : 'Aktifkan kategori' }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#64748B;padding:24px">Belum ada kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection
