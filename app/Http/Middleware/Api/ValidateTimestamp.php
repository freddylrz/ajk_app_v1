<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTimestamp
{
    public function handle(Request $request, Closure $next): Response
    {
        $timestamp = $request->header('X-Timestamp');

        if (!$timestamp || !is_numeric($timestamp)) {
            return response()->json(['error' => 'Invalid or missing timestamp'], 400);
        }

        $current = time(); // Waktu server sekarang
        $allowedDrift = 300; // 5 menit = 300 detik

        if (abs($current - $timestamp) > $allowedDrift) {
            return response()->json(['error' => 'Timestamp out of acceptable range'], 403);
        }

        return $next($request);
    }
}