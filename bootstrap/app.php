<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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

if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');
    
    // Memastikan direktori storage di dalam /tmp otomatis dibuat jika belum ada
    $storagePath = '/tmp/storage';
    if (!is_dir($storagePath)) {
        mkdir($storagePath, 0777, true);
        mkdir($storagePath . '/framework', 0777, true);
        mkdir($storagePath . '/framework/views', 0777, true);
        mkdir($storagePath . '/framework/cache', 0777, true);
        mkdir($storagePath . '/framework/sessions', 0777, true);
        mkdir($storagePath . '/logs', 0777, true);
    }
}

return $app->create();