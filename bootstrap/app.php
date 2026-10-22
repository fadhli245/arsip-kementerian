<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Mengarahkan storage ke folder /tmp khusus untuk serverless seperti Vercel
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    });

// Pindahkan path storage jika mendeteksi environment Vercel
if (getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');
}

return $app->create();