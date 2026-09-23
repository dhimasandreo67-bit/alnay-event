<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdminLoggedIn::class,
            'pelanggan' => \App\Http\Middleware\EnsurePelangganLoggedIn::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'auth/admin/login',
            'auth/admin/logout',
            'event',
            'event/*',
            'peserta/*',
            'pendaftaran/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Force agar semua error Unauthenticated selalu direspon JSON 401 (tanpa redirect GET)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'message' => 'Anda harus login terlebih dahulu.'
            ], 401);
        });
    })->create();