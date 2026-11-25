<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $role = strtolower(trim((string) $user->role));

        if (!in_array($role, ['admin', 'petani'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['email' => 'Role akun tidak valid.']);
        }

        if (!in_array($role, $roles, true)) {
            // Role sudah pasti admin/petani, jadi arahkan ke dashboard miliknya sendiri
            return redirect()->route($role === 'admin' ? 'admin.dashboard' : 'petani.dashboard');
        }

        return $next($request);
    }
}