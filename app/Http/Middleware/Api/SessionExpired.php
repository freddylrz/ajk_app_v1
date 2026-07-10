<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SessionExpired
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token || !str_contains($token, '|')) {
            return response()->json([
                'status' => 401,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        [$id, $plainToken] = explode('|', $token, 2);

        // Pastikan query mengarah ke schema public
        $accessToken = DB::table('public.personal_access_tokens')->find($id);

        if (!$accessToken || !hash_equals($accessToken->token, hash('sha256', $plainToken))) {
            return response()->json([
                'status' => 401,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        if ($accessToken->expires_at && Carbon::parse($accessToken->expires_at)->isPast()) {   
            // Hapus token yang kedaluwarsa
            DB::table('public.personal_access_tokens')->where('id', $id)->delete();
            
            return response()->json([
                'status' => 401,
                'message' => 'Token sudah kedaluwarsa.',
            ], 401);
        }

        $userModel = config('sanctum.model', \App\Models\User::class);
        $user = $userModel::find($accessToken->tokenable_id);

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Pengguna tidak ditemukan.',
            ], 401);
        }

        return $next($request);
    }
}
