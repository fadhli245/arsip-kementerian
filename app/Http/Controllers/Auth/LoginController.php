<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Menampilkan halaman form login
    public function showLoginForm()
    {
        // Pastikan nama view ini sesuai dengan file login kamu (misal: auth/login.blade.php)
        return view('auth.login'); 
    }

    // 2. Memproses data login (Sama dengan route /login-proses)
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $credentials['is_active'] = true;

        // Cek email dan password ke database
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil, buat sesi baru untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();

            $user = Auth::user();
            $loginAt = Carbon::now();

            // Arahkan (Redirect) berdasarkan Role
            if (in_array($user->role, ['admin', 'super_admin', 'admin_divisi'], true)) {
                $user->last_login_at = $loginAt;
                $user->save();
                ActivityLogger::record('Login', 'Pengguna berhasil masuk ke sistem.');

                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'verifikator') {
                $user->last_login_at = $loginAt;
                $user->save();
                ActivityLogger::record('Login', 'Pengguna berhasil masuk ke sistem.');

                return redirect()->route('admin.permohonan.index');
            } elseif (in_array($user->role, ['user', 'pegawai'], true)) {
                $user->last_login_at = $loginAt;
                $user->save();
                ActivityLogger::record('Login', 'Pengguna berhasil masuk ke sistem.');

                return redirect()->route('pegawai.dashboard');
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Peran akun tidak valid.'])->onlyInput('email');
        }

        // Jika email/password salah, kembalikan ke halaman login dengan pesan error
        return back()->with('error', 'Email atau password yang Anda masukkan salah!');
    }

    // 3. Memproses Logout
    public function logout(Request $request)
    {
        ActivityLogger::record('Logout', 'Pengguna keluar dari sistem.');
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
