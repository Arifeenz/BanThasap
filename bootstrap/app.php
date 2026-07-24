<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Safety net: ถ้า APP_DEBUG หลุดเป็น true บน production (เช่นลืมแก้ .env
        // ตอน deploy) ให้บังคับปิดก่อน render กันไม่ให้เห็น source code/stack trace
        $exceptions->render(function (\Throwable $e) {
            if (app()->isProduction() && config('app.debug')) {
                config(['app.debug' => false]);
                Log::critical('APP_DEBUG is true in production. Forced off before rendering exception.');
            }

            return null;
        });
    })->create();
