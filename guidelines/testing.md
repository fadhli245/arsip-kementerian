# Standar Pengujian dan Diagnosis

Gunakan pengujian Laravel yang tersedia di `tests/` dan PHPUnit sesuai konfigurasi proyek. Pilih pengujian yang memeriksa perilaku yang terlihat pengguna, bukan detail implementasi yang tidak perlu.

## Area yang perlu diverifikasi

- **Route dan akses**: pengguna tamu, admin, dan pegawai mendapat akses yang sesuai; uji juga akses langsung ke URL aksi admin.
- **Arsip**: validasi create/update, pencarian/filter, pagination, dan aturan unggah file.
- **Aktivitas**: aksi penting menghasilkan log yang benar dan tidak memasukkan rahasia.
- **Form web**: CSRF aktif, method form cocok dengan route, validasi mengembalikan error pada field yang tepat.
- **Database**: migration dapat dijalankan ulang dari database bersih; gunakan database testing terpisah dari data lokal.

## Menjalankan pemeriksaan

- Jalankan test suite dengan `php artisan test` atau `vendor/bin/phpunit` setelah perubahan yang relevan.
- Untuk melihat route dan method: `php artisan route:list`.
- Untuk format PHP: `vendor/bin/pint` bila tersedia.
- Jangan menjalankan migration destruktif pada database berisi data penting. Gunakan database khusus pengujian.

## Pelaporan error

- Reproduksi error, catat langkah, URL/method, peran pengguna, dan pesan yang tampil.
- Periksa log aplikasi di `storage/logs/laravel.log` dan cocokkan dengan route, validasi, middleware, session, serta konfigurasi environment.
- Untuk 419, cek CSRF dan session; untuk 405, cek method form dan route. Setelah perbaikan, ulangi alur yang gagal.
- Jangan memasukkan kredensial, token, atau data pribadi ke laporan maupun fixture pengujian.
