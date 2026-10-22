@extends('layouts.app')

@section('title', 'Manajemen Pengguna - SIM Arsip')

@section('content')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-header h2 { font-family: 'Manrope', sans-serif; color: var(--navy); font-size: 22px; }
    .btn-add { background: var(--blue); color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; }
    
    .table-card { background: white; border: 1px solid #e2eaf2; border-radius: 14px; padding: 20px; box-shadow: 0 5px 20px rgba(22,49,78,.03); }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
    th { background: #f8fafc; color: var(--navy); padding: 12px; border-bottom: 2px solid #e2eaf2; font-size: 12px; letter-spacing: 0.5px; }
    td { padding: 12px; border-bottom: 1px solid #e2eaf2; color: #334155; }
    .badge-role { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-admin { background: #fee2e2; color: #991b1b; }
    .badge-pegawai { background: #e0f2fe; color: #0369a1; }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; }
</style>

<div class="page-header">
    <h2>Manajemen Pengguna & Staf</h2>
    <a href="{{ route('admin.users.create') }}" class="btn-add">➕ Tambah Pengguna Baru</a>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA LENGKAP</th>
                <th>EMAIL</th>
                <th>ROLE / HAK AKSES</th>
                <th>TERDAFTAR</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $user->name ?? $user->nama }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge-role {{ $user->role === 'admin' ? 'badge-admin' : 'badge-pegawai' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">Hapus</button>
                            </form>
                        @else
                            <span style="color: var(--muted); font-size: 12px; font-style: italic;">Akun Anda</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--muted); padding: 30px;">Belum ada data pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection