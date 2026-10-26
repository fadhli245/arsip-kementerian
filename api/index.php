<?php

// 1. Definisikan path sementara di folder /tmp (karena Vercel read-only)
$tmpStorage = '/tmp/storage';
$tmpCache = '/tmp/cache';

// 2. Buat kerangka folder storage secara otomatis jika belum ada
if (!file_exists($tmpStorage)) {
    @mkdir($tmpStorage . '/framework/views', 0755, true);
    @mkdir($tmpStorage . '/framework/sessions', 0755, true);
    @mkdir($tmpStorage . '/framework/cache', 0755, true);
    @mkdir($tmpStorage . '/logs', 0755, true);
}

if (!file_exists($tmpCache)) {
    @mkdir($tmpCache, 0755, true);
}

// 3. Set environment variable agar cache mengarah ke /tmp
$_ENV['APP_SERVICES_CACHE'] = $tmpCache . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpCache . '/packages.php';
$_ENV['APP_CONFIG_CACHE']   = $tmpCache . '/config.php';
$_ENV['APP_ROUTES_CACHE']   = $tmpCache . '/routes.php';

// 4. Panggil file inti Laravel (Autoload & App)
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 5. Ubah letak Storage Path SETELAH variabel $app didefinisikan (agar tidak error)
$app->useStoragePath($tmpStorage);

// 6. Tangkap request dan jalankan aplikasi HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);