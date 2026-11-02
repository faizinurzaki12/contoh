<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !in_array((int) $user->role, [0, 1], true)) {
            abort(403, 'Akses ditolak. Hanya admin yang diizinkan.');
        }

        return $next($request);
    }
}
