<?php

use App\Http\Middleware\rolemember;
use App\Http\Middleware\whatsappchecker;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'=>rolemember::class,
            'whatsapp_auth'=>whatsappchecker::class

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
