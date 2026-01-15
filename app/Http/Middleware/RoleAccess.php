<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        $user = auth()->user();

        // Jika tidak ada role yang didefinisikan, default ke kasir & owner (backward compatibility)
        if (empty($roles)) {
            $roles = ['kasir', 'owner'];
        }

        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }
        return $next($request);
    }
}
