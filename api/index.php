<?php

// 1. Arahkan folder storage dan cache ke /tmp agar writable di Vercel
$tmpStorage = '/tmp/storage';
$tmpCache = '/tmp/cache';

if (!file_exists($tmpStorage)) {
    @mkdir($tmpStorage . '/framework/views', 0755, true);
    @mkdir($tmpStorage . '/framework/sessions', 0755, true);
    @mkdir($tmpStorage . '/framework/cache', 0755, true);
    @mkdir($tmpStorage . '/logs', 0755, true);
}

if (!file_exists($tmpCache)) {
    @mkdir($tmpCache, 0755, true);
}

// 2. Set environment path ke /tmp
app()->useStoragePath($tmpStorage);
$_ENV['APP_SERVICES_CACHE'] = $tmpCache . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpCache . '/packages.php';
$_ENV['APP_CONFIG_CACHE']   = $tmpCache . '/config.php';
$_ENV['APP_ROUTES_CACHE']   = $tmpCache . '/routes.php';

// 3. Jalankan Laravel secara normal
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath($tmpStorage);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);