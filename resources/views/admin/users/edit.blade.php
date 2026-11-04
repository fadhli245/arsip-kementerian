@extends('layouts.app')

@section('title', 'Edit Pengguna - SIM Arsip')

@section('content')
<div style="max-width:720px;margin:0 auto;background:#fff;padding:28px;border:1px solid #E2E8F0;border-radius:12px">
    <h1 style="font-size:22px;color:#0A2558;margin-bottom:6px">Edit Akun Pegawai</h1>
    <p style="color:#64748B;margin-bottom:22px">Perbarui profil, divisi, peran, atau atur ulang kata sandi.</p>
    <form action="{{ route('admin.users.update', $user) }}" method="POST" style="display:grid;gap:15px">
        @csrf
        @method('PUT')
        <label style="display:grid;gap:6px;font-weight:600">Nama<input name="name" value="{{ old('name', $user->name) }}" maxlength="255" required style="padding:11px;border:1px solid #CBD5E1;border-radius:8px"></label>
        <label style="display:grid;gap:6px;font-weight:600">Email<input type="email" name="email" value="{{ old('email', $user->email) }}" required style="padding:11px;border:1px solid #CBD5E1;border-radius:8px"></label>
        <label style="display:grid;gap:6px;font-weight:600">Divisi<input name="division" value="{{ old('division', $user->division) }}" maxlength="100" placeholder="Nama divisi" style="padding:11px;border:1px solid #CBD5E1;border-radius:8px"></label>
        <label style="display:grid;gap:6px;font-weight:600">Role
            <select name="role" required style="padding:11px;border:1px solid #CBD5E1;border-radius:8px;background:#fff">
                @foreach((auth()->user()->role === 'super_admin' ? ['super_admin' => 'Super Admin', 'admin' => 'Admin', 'admin_divisi' => 'Admin Divisi', 'verifikator' => 'Verifikator', 'user' => 'Pegawai'] : ['admin' => 'Admin', 'admin_divisi' => 'Admin Divisi', 'verifikator' => 'Verifikator', 'user' => 'Pegawai']) as $value => $label)
                    <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label style="display:grid;gap:6px;font-weight:600">Kata sandi baru <span style="font-weight:400;color:#64748B;font-size:12px">Kosongkan jika tidak ingin mengubah kata sandi.</span><input type="password" name="password" minlength="8" autocomplete="new-password" style="padding:11px;border:1px solid #CBD5E1;border-radius:8px"></label>
        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:8px">
            <a href="{{ route('admin.users.index') }}" style="padding:10px 16px;color:#334155;border:1px solid #CBD5E1;border-radius:8px;text-decoration:none">Kembali</a>
            <button type="submit" style="padding:10px 18px;color:#fff;background:#0A2558;border:0;border-radius:8px;font-weight:700;cursor:pointer">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
