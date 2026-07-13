<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek refresh token dari cookie (nama cookie sesuai yang di-set
        // AuthController API v1: __ajk-tib-at / __ajk-tib-rt)
        $data = $request->cookie('__ajk-tib-rt') ?? ($_COOKIE['__ajk-tib-rt'] ?? null);

        if (!$data) {
            return redirect('/login');
        }

        return $next($request);
    }
}
