<?php

// 1. Buat struktur folder storage di /tmp agar Vercel bisa menulis data
$tmpStorage = '/tmp/storage';
if (!is_dir($tmpStorage)) {
    mkdir($tmpStorage, 0777, true);
    mkdir($tmpStorage . '/framework/cache/data', 0777, true);
    mkdir($tmpStorage . '/framework/views', 0777, true);
    mkdir($tmpStorage . '/framework/sessions', 0777, true);
    mkdir($tmpStorage . '/logs', 0777, true);
}

// 2. Muat autoload Composer
require __DIR__.'/../vendor/autoload.php';

// 3. Muat instance aplikasi Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// 4. Paksa Laravel menggunakan path /tmp yang baru saja dibuat
$app->useStoragePath($tmpStorage);

// 5. Tangkap dan jalankan request (Format standar Laravel 10 ke bawah)
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);