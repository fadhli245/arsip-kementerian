<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $userRole = strtolower(Auth::user()->role ?? '');

        // Cek apakah role user ada di dalam daftar role yang diizinkan rute
        foreach ($roles as $role) {
            $role = strtolower(trim($role));
            if ($userRole === $role || str_contains($userRole, $role)) {
                return $next($request);
            }
        }

        abort(403, 'AKSES DITOLAK');
    }
}