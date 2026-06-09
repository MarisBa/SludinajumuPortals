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

        // CSRF exceptions:
        // - asistents/jautat: TEMP, restore once session/CSRF is confirmed in prod.
        // - stripe/webhook: PERMANENT — Stripe POSTs directly without a session;
        //   request authenticity is enforced by the Stripe-Signature header instead.
        $middleware->validateCsrfTokens(except: [
            'asistents/jautat',
            'stripe/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
