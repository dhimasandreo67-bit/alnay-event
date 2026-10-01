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
            // Milik main
            'admin' => \App\Http\Middleware\EnsureAdminLoggedIn::class,
            'pelanggan' => \App\Http\Middleware\EnsurePelangganLoggedIn::class,
            // Milik han
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'admin.session' => \App\Http\Middleware\EnsureAdminSession::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'auth/admin/login',
            'auth/admin/logout',
            'event',
            'event/*',
            'peserta/*',
            'pendaftaran/*',
            'voting/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {

            // 1. Admin → redirect ke admin login
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login');
            }

            // 2. AJAX / API / expectsJson → JSON 401
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Anda harus login terlebih dahulu.',
                ], 401);
            }

            // 3. Halaman biasa → redirect ke Google OAuth
            return redirect()->route('auth.google', [
                'redirect_to' => url()->current(),
            ]);
        });
    })->create();