<?php

use App\Http\Middleware\rolemember;
use App\Http\Middleware\whatsappchecker;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role'=>rolemember::class,
            'whatsapp_auth'=>whatsappchecker::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // n8n / bot tidak selalu kirim Accept: application/json
        $exceptions->shouldRenderJsonWhen(fn ($request) => $request->is('api/*') || $request->expectsJson());

        // Tangani CSRF / Token Mismatch (419) secara ramah dan cegah layar 419 karena klik ganda atau tab lama
        $exceptions->render(function (TokenMismatchException $e, $request) {
            if ($request->is('sesi/login') || $request->is('loginbarcode')) {
                if (auth()->check() && auth()->user()?->role === 'scanabsen') {
                    return redirect()->route('scan.barcode');
                }

                return redirect()->route('scan.login')
                    ->withErrors(['username' => 'Sesi login telah diperbarui atau terdeteksi klik ganda. Silakan coba masuk kembali.']);
            }

            if ($request->is('admin/login') || $request->is('admin/*')) {
                if (auth()->check()) {
                    return redirect()->route('admin.dashboard');
                }

                return redirect()->route('admin.login')
                    ->withErrors(['login_input' => 'Sesi login telah kedaluwarsa. Silakan coba masuk kembali.']);
            }

            return redirect()->back()
                ->withInput($request->except('password', '_token'))
                ->withErrors(['error' => 'Sesi Anda telah kedaluwarsa. Silakan muat ulang dan coba lagi.']);
        });
    })->create();
