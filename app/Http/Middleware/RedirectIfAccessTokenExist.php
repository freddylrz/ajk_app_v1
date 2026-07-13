<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAccessTokenExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nama cookie sesuai yang di-set AuthController API v1
        if (isset($_COOKIE['__ajk-tib-at']) && isset($_COOKIE['__ajk-tib-rt'])) {
            return redirect('/client/dashboard');
        }

        return $next($request);
    }
}
