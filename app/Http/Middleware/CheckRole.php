<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role = 'admin'): Response
    {
        // Jika user belum login, biarkan middleware 'auth' yang menangani redirect ke login.
        // Jika user SUDAH login tapi role-nya bukan admin, barulah lempar 403.
        if ($request->user() && $request->user()->role !== $role) {
            abort(403, 'Akses khusus detektif (Admin) saja!');
        }

        return $next($request);
    }
}