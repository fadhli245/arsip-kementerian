<?php

// Vercel menyediakan filesystem sementara di /tmp. Laravel tetap memakai
// storage/ untuk source, jadi arahkan file runtime ke lokasi yang bisa ditulis.
$storagePath = '/tmp/storage';

foreach ([
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
] as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;

require __DIR__ . '/../public/index.php';
