<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Menampilkan daftar seluruh pengguna
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Menampilkan form tambah pengguna baru
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. Menyimpan pengguna baru ke database
    public function store(Request $request)
    {
        $allowedRoles = auth()->user()->role === 'super_admin'
            ? ['super_admin', 'admin', 'admin_divisi', 'verifikator', 'user']
            : ['admin', 'admin_divisi', 'verifikator', 'user'];

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => ['required', 'in:' . implode(',', $allowedRoles)],
            'division' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'division' => $request->division,
            'is_active' => true,
        ]);

        ActivityLogger::record('Tambah Pengguna', 'Membuat akun pengguna: ' . $user->email, $user);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $allowedRoles = auth()->user()->role === 'super_admin'
            ? ['super_admin', 'admin', 'admin_divisi', 'verifikator', 'user']
            : ['admin', 'admin_divisi', 'verifikator', 'user'];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'division' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        ActivityLogger::record('Ubah Pengguna', 'Memperbarui akun pengguna: ' . $user->email, $user);

        return redirect()->route('admin.users.index')->with('success', 'Profil pengguna berhasil diperbarui.');
    }

    public function toggleActive(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();
        ActivityLogger::record($user->is_active ? 'Aktifkan Pengguna' : 'Nonaktifkan Pengguna', 'Memperbarui status akun: ' . $user->email, $user);

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    // 4. Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah admin menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        if ($user->arsips()->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'Akun ini masih memiliki arsip. Hapus atau alihkan arsip tersebut sebelum menghapus akun.');
        }

        $email = $user->email;
        $user->delete();
        ActivityLogger::record('Hapus Pengguna', 'Menghapus akun pengguna: ' . $email, $user);
        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
