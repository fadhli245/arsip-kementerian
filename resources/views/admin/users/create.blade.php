@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru - SIM Arsip')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; max-width: 800px; margin: 0 auto;">

    <!-- Header Form -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 15px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #0A2558; margin: 0;">Tambah Pengguna Baru</h2>
            <p style="font-size: 13.5px; color: #64748B; margin: 5px 0 0 0;">Daftarkan akun pegawai atau administrator baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" style="background: #F1F5F9; color: #475569; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s;">
            ⬅️ Kembali
        </a>
    </div>

    <!-- Card Form Utama -->
    <div style="background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 30px;">
        
        <!-- Action mengarah ke route store pengguna -->
        <form action="{{ route('admin.users.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf <!-- Token keamanan wajib dari Laravel -->

            <!-- Grid untuk Nama & Email -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Nama Lengkap <span style="color: #EF4444;">*</span></label>
                    <input type="text" name="name" required placeholder="Masukkan nama pegawai..." style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Alamat Email Kedinasan <span style="color: #EF4444;">*</span></label>
                    <input type="email" name="email" required placeholder="contoh@kementerian.go.id" style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>
            </div>

            <!-- Grid untuk Password & Role -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Password <span style="color: #EF4444;">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter..." style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Hak Akses (Role) <span style="color: #EF4444;">*</span></label>
                    <select name="role" required style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF; cursor: pointer;">
                        <option value="">-- Pilih Hak Akses --</option>
                        <option value="pegawai">Pegawai Biasa (Hanya lihat & unduh)</option>
                        <option value="admin">Administrator (Kelola arsip & sistem)</option>
                    </select>
                </div>
            </div>

            <!-- Pesan Error Validasi (jika ada) -->
            @if($errors->any())
            <div style="background: #FEF2F2; color: #DC2626; padding: 12px 15px; border-radius: 8px; font-size: 13px; border: 1px solid #F87171;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Tombol Aksi -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="reset" style="background: #FFFFFF; color: #64748B; border: 1px solid #CBD5E1; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13.5px; cursor: pointer; transition: 0.2s;">
                    Reset Form
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #0A2558, #153A7A); color: white; border: none; padding: 10px 28px; border-radius: 8px; font-weight: 700; font-size: 13.5px; cursor: pointer; box-shadow: 0 4px 10px rgba(10, 37, 88, 0.2); transition: 0.2s;">
                    💾 Simpan Pengguna
                </button>
            </div>

        </form>
    </div>
</div>
@endsection