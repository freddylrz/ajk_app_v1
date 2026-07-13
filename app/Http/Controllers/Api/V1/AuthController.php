<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Api\CryptManual;
use Carbon\Carbon;
use Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();

                $usernameMissing = $errors->has('username');
                $passwordMissing = $errors->has('password');

                if ($usernameMissing && $passwordMissing) {
                    $message = 'Validasi gagal, username dan password wajib diisi';
                } elseif ($usernameMissing) {
                    $message = 'Validasi gagal, username wajib diisi';
                } elseif ($passwordMissing) {
                    $message = 'Validasi gagal, password wajib diisi';
                } else {
                    $message = 'Validasi gagal';
                }

                return response()->json([
                    'status' => 422,
                    'message' => $message,
                ], 422);
            }

            $validated = $validator->validated();

            $fieldType = filter_var($validated['username'], FILTER_VALIDATE_EMAIL)
                ? 'email'
                : 'name';

            if (
                !Auth::attempt([
                    $fieldType => $validated['username'],
                    'password' => $validated['password']
                ])
            ) {
                return response()->json([
                    'message' => 'Username atau password salah'
                ], 401);
            }

            $user = Auth::user();

            if ($user->is_active == 0) {
                return response()->json([
                    'message' => 'User tidak aktif'
                ], 403);
            }

            $accessTokenExpire = now()->addDays(1); // 24 jam
            $refreshTokenExpire = now()->addDays(30); // 30 hari

            $accessToken = $user->createToken($user->name . '_access_token', ['access_token'], $accessTokenExpire);
            $refreshToken = $user->createToken($user->name . '_refresh_token', ['refresh-token'], $refreshTokenExpire);

            $accessTokenRes = [
                'token_type' => 'Bearer',
                'token_value' => $accessToken->plainTextToken,
            ];

            $refreshTokenRes = [
                'token_type' => 'Bearer',
                'token_value' => $refreshToken->plainTextToken,
            ];

            return response()->json([
                'status' => 200,
                'message' => 'Login berhasils',
                'data' => [
                    'accessToken' => CryptManual::encryption($accessTokenRes),
                    'refreshToken' => CryptManual::encryption($refreshTokenRes)
                ]
            ])->cookie(
                    '__ajk-tib-at',
                    CryptManual::encryption($accessTokenRes),
                    1440,
                    '/',                     // path
                    null,  // domain
                    true,                    // Secure
                    false,                   // HttpOnly
                    false,
                    'Lax'
                )->cookie(
                    '__ajk-tib-rt',
                    CryptManual::encryption($refreshTokenRes),
                    43200,
                    '/',
                    null,  // domain
                    false,
                    false,
                    false,
                    'Lax'
                );
        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }
    public function userInfo(Request $r)
    {
        try {
            $user = Auth::user();
            $token = $r->user()->currentAccessToken();

            $roles = DB::table('role_user')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('role_user.user_id', $user->id)
                ->pluck('roles.name')
                ->toArray();

            $data = DB::table('users')
                ->select(
                    'users.id',
                    'users.display_name',
                    'users.is_active',
                )
                ->where('users.id', (string) $user->id)
                ->first();

            $payload = [
                'user_info' => [
                    'id' => (string) $data->id,
                    'display_name' => (string) $user->display_name,
                    'active' => (string) $user->is_active,
                    'roles' => $roles,
                ],
                'session_expire' => Carbon::parse($token->expires_at)->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil memuat info user!',
                'data' => $payload
            ], 200);
        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada sistem. Silakan coba lagi.',
            ], 500);
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Anda berhasil logout!'
        ], 200);
    }

    public function refresh(Request $r)
    {
        try {
            if (!$r->user()->tokenCan('refresh-token')) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Token tidak valid.',
                ], 401);
            }

            $user = Auth::user();
            $currentToken = $r->user()->currentAccessToken();

            if ($user->is_active == 0) {
                return response()->json([
                    'message' => 'User tidak aktif'
                ], 403);
            }

            $currentToken->delete();

            $accessTokenExpire = now()->addDays(1); // 24 jam
            $refreshTokenExpire = now()->addDays(30); // 30 hari

            $accessToken = $user->createToken($user->name . '_access_token', ['access_token'], $accessTokenExpire);
            $refreshToken = $user->createToken($user->name . '_refresh_token', ['refresh-token'], $refreshTokenExpire);

            $accessTokenRes = [
                'token_type' => 'Bearer',
                'token_value' => $accessToken->plainTextToken,
            ];

            $refreshTokenRes = [
                'token_type' => 'Bearer',
                'token_value' => $refreshToken->plainTextToken,
            ];

            return response()->json([
                'status' => 200,
                'message' => 'Login berhasil',
                'data' => [
                    'accessToken' => CryptManual::encryption($accessTokenRes),
                    'refreshToken' => CryptManual::encryption($refreshTokenRes)
                ]
            ])->cookie(
                    '__ajk-tib-at',
                    CryptManual::encryption($accessTokenRes),
                    1440,
                    '/',
                    null,
                    true,
                    false,
                    false,
                    'Lax'
                )->cookie(
                    '__ajk-tib-rt',
                    CryptManual::encryption($refreshTokenRes),
                    43200,
                    '/',
                    null,
                    true,
                    false,
                    false,
                    'Lax'
                );
        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada sistem. Silakan coba lagi.',
            ], 500);
        }
    }
}
