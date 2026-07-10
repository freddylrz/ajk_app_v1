<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use App\Custom\CryptManual;

class DecryptSanctumToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token) {

            try {

                $decrypted = CryptManual::decryption($token);

                // dd($decrypted['token_value']);

                if (!isset($decrypted['token_value'])) {
                    return response()->json([
                        'message' => 'Token tidak valid'
                    ], 401);
                }

                $request->headers->remove('Authorization');

                $request->headers->add([
                    'Authorization' => 'Bearer '.$decrypted['token_value']
                ]);

            } catch (\Exception $e) {

                return response()->json([
                    'message' => 'Invalid encrypted token'
                ], 401);

            }

        }

        return $next($request);
    }
}
