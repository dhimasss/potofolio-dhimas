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
        // Vercel meneruskan request lewat proxy; percayai header X-Forwarded-*
        // agar URL, asset, dan form memakai https:// yang benar.
        $middleware->trustProxies(at: '*');

        // Pengganti app/Http/Middleware/Authenticate.php & RedirectIfAuthenticated.php (Laravel <= 10):
        // - tamu yang membuka halaman ber-middleware "auth"  -> /admin/login
        // - admin yang membuka halaman ber-middleware "guest" -> /admin
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
