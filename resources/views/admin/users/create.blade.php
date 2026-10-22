@extends('layouts.app')

@section('title', 'Tambah Pengguna - SIM Arsip')

@section('content')
<style>
    .form-container { background: white; border: 1px solid #e2eaf2; border-radius: 14px; padding: 30px; max-width: 600px; margin: 0 auto; box-shadow: 0 5px 20px rgba(22,49,78,.03); }
    .form-header { margin-bottom: 25px; border-bottom: 1px solid #e2eaf2; padding-bottom: 15px; }
    .form-header h2 { color: var(--navy); font-family: 'Manrope', sans-serif; font-size: 22px; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; font-family: inherit; }
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
    .btn-secondary { background: #f0f4f8; color: var(--muted); border: 1px solid var(--line); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; }
    .btn-submit { background: var(--blue); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; }
    .error-msg { color: var(--red); font-size: 12px; margin-top: 5px; display: block; }
</style>

<div class="form-container">
    <div class="form-header">
        <h2>Tambah Akun Pengguna / Staf</h2>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>NAMA LENGKAP</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso, S.Kom" required>
            @error('name') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>EMAIL KANTOR / PRIBADI</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="budi@dephub.go.id" required>
            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>PASSWORD AWAL</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            @error('password') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>HAK AKSES (ROLE)</label>
            <select name="role" class="form-control" required>
                <option value="pegawai">Pegawai / Staf</option>
                <option value="admin">Administrator</option>
            </select>
            @error('role') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-submit">Simpan Akun</button>
        </div>
    </form>
</div>
@endsection