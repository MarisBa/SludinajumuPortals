<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust Coolify's reverse proxy so Laravel respects X-Forwarded-Proto: https
        // and route()/url() generate https:// URLs (fixes mixed-content blocking).
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.blocked' => \App\Http\Middleware\CheckBlockedUser::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\CheckBlockedUser::class,
        ]);

        // TEMP: CSRF exception for the AI assistant endpoint while we diagnose why
        // POST /asistents/jautat never reaches the controller. Restore CSRF protection
        // by removing this exception once session/CSRF is confirmed working in prod.
        $middleware->validateCsrfTokens(except: [
            'asistents/jautat',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
