<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Jika belum login, arahkan ke halaman utama/login
        if (!Auth::check()) {
            return redirect('/');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            abort(403, 'Akun Anda sedang dinonaktifkan. Hubungi administrator.');
        }

        // 2. Ambil role user, ubah ke huruf kecil dan bersihkan spasi
        $userRole = strtolower(trim(Auth::user()->role ?? ''));

        // 3. Ubah parameter roles yang dikirim dari rute menjadi huruf kecil
        $allowedRoles = array_map(function($role) {
            return strtolower(trim($role));
        }, $roles);

        // 4. Jika role user cocok dengan salah satu role yang diizinkan (atau mengandung kata 'admin')
        if (in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        // Jika tidak cocok sama sekali, tampilkan 403
        abort(403, 'AKSES DITOLAK: Peran Anda (' . $userRole . ') tidak diizinkan mengakses halaman ini.');
    }
}
