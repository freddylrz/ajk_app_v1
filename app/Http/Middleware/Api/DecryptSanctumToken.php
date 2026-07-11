<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\Api\CryptManual;

class DecryptSanctumToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token) {

            try {

                $decrypted = CryptManual::decryption($token);

                if (!isset($decrypted['token_value'])) {
                    return response()->json([
                        'status' => 401,
                        'message' => 'Token tidak valid'
                    ], 401);
                }

                $request->headers->remove('Authorization');

                $request->headers->add([
                    'Authorization' => 'Bearer ' . $decrypted['token_value']
                ]);

            } catch (\Exception $e) {

                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid encrypted token'
                ], 401);

            }

        }

        return $next($request);
    }
}
