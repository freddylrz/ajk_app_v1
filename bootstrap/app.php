<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Middleware\Api\DecryptSanctumToken;
use App\Http\Middleware\TrustProxies;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->priority([
            DecryptSanctumToken::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
        ]);

        $middleware->redirectGuestsTo(function () {
            return null;
        });

        $middleware->alias([
            'DecryptSanctumToken' => \App\Http\Middleware\Api\DecryptSanctumToken::class,
            'SessionExpired'      => \App\Http\Middleware\Api\SessionExpired::class,
            'ValidateTimestamp'   => \App\Http\Middleware\Api\ValidateTimestamp::class,
            'CheckRole'           => \App\Http\Middleware\Api\CheckRole::class,
        ]);

        $middleware->use([
            TrustProxies::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Token tidak valid',
                ], 401);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Rute API tidak ditemukan.',
                ], 404);
            }
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 405,
                    'message' => 'Metode permintaan tidak diizinkan.',
                ], 405);
            }
        });

        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Terjadi kesalahan pada sistem. Silakan coba kembali.',
                ], 500);
            }
        });
    })->create();
