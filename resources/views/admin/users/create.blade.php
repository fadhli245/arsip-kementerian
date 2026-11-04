@extends('layouts.app')

@section('title', 'Tambah Pengguna - SIM Arsip')

@section('content')
<div style="max-width: 720px; margin: 0 auto; background: #fff; padding: 28px; border: 1px solid #E2E8F0; border-radius: 12px;">
    <h2 style="margin-bottom: 8px; color: #0A2558;">Tambah Pengguna</h2>
    <p style="margin-bottom: 24px; color: #64748B;">Buat akun admin atau pegawai untuk mengakses sistem arsip.</p>

    @if ($errors->any())
        <div role="alert" style="margin-bottom: 20px; padding: 12px 16px; color: #991B1B; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" style="display: grid; gap: 16px;">
        @csrf

        <label style="display: grid; gap: 6px; font-weight: 600;">
            Nama
            <input type="text" name="name" value="{{ old('name') }}" maxlength="255" required autocomplete="name" style="padding: 11px 12px; border: 1px solid #CBD5E1; border-radius: 8px;">
        </label>

        <label style="display: grid; gap: 6px; font-weight: 600;">
            Email
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" style="padding: 11px 12px; border: 1px solid #CBD5E1; border-radius: 8px;">
        </label>

        <label style="display: grid; gap: 6px; font-weight: 600;">
            Kata sandi
            <input type="password" name="password" required minlength="8" autocomplete="new-password" style="padding: 11px 12px; border: 1px solid #CBD5E1; border-radius: 8px;">
        </label>

        <label style="display: grid; gap: 6px; font-weight: 600;">
            Peran
            <select name="role" required style="padding: 11px 12px; border: 1px solid #CBD5E1; border-radius: 8px; background: #fff;">
                <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>Pegawai</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="admin_divisi" {{ old('role') === 'admin_divisi' ? 'selected' : '' }}>Admin Divisi</option>
                <option value="verifikator" {{ old('role') === 'verifikator' ? 'selected' : '' }}>Verifikator</option>
                @if(auth()->user()->role === 'super_admin')<option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>@endif
            </select>
        </label>

        <label style="display: grid; gap: 6px; font-weight: 600;">
            Divisi (opsional)
            <input type="text" name="division" value="{{ old('division') }}" maxlength="100" placeholder="Contoh: Tata Usaha" style="padding: 11px 12px; border: 1px solid #CBD5E1; border-radius: 8px;">
        </label>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 8px;">
            <a href="{{ route('admin.users.index') }}" style="padding: 10px 16px; color: #334155; border: 1px solid #CBD5E1; border-radius: 8px; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 18px; color: #fff; background: #0A2558; border: 0; border-radius: 8px; font-weight: 700; cursor: pointer;">Simpan Pengguna</button>
        </div>
    </form>
</div>
@endsection
