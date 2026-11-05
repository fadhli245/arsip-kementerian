<?php

// 1. Buat folder penyimpanan sementara di /tmp untuk Vercel
$storageFolders = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/logs',
    '/tmp/views'
];

foreach ($storageFolders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }
}

// 2. Alihkan lokasi penyimpanan framework ke /tmp
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_SERVER['APP_STORAGE'] = '/tmp/storage';

// 3. Jalankan entry point Laravel
require __DIR__ . '/../public/index.php';