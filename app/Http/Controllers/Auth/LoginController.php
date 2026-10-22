<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        // Cek email dan password ke database
        if (Auth::attempt($credentials)) {
            // Jika berhasil, buat sesi baru untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();

            // Arahkan (Redirect) berdasarkan Role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role === 'user') {
                return redirect()->route('pegawai.dashboard');
            }
        }

        // Jika email/password salah, kembalikan ke halaman login dengan pesan error
        return back()->with('error', 'Email atau password yang Anda masukkan salah!');
    }

    // 3. Memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}