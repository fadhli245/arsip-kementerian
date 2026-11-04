# Standar Server dan HTTP

## Konfigurasi lingkungan

- Simpan konfigurasi lokal dan rahasia di `.env`; jangan commit nilai rahasia. Perbarui `.env.example` dengan nama variabel dan nilai contoh yang aman bila konfigurasi baru diperlukan.
- Gunakan `APP_DEBUG=false` pada production. Pastikan `APP_KEY`, koneksi database, URL aplikasi, dan konfigurasi storage sesuai lingkungan deployment.
- Setelah perubahan konfigurasi di server, bersihkan atau bangun ulang config cache sesuai proses deployment.
- File unggahan arsip harus menggunakan disk Laravel dan aturan akses yang sesuai. Jangan membuat file privat dapat diakses publik tanpa kebutuhan dan otorisasi.

## Route, method, dan CSRF

- Gunakan GET untuk membaca, POST untuk membuat/aksi, PUT atau PATCH untuk memperbarui, dan DELETE untuk menghapus.
- Semua form web yang mengubah data wajib menyertakan `@csrf`. Untuk method selain GET/POST, sertakan `@method(...)` dan pastikan route memakai method yang sama.
- Jangan mengubah aksi tulis menjadi GET hanya untuk menghindari error method atau CSRF.
- Validasi request di server, batasi tipe/ukuran unggahan, dan pastikan pengguna berhak melakukan aksi tersebut.

## Diagnosis error umum

- **419 Page Expired**: periksa token `@csrf`, session/cookie, domain/HTTPS, dan apakah halaman/form lama dibuka setelah session berakhir.
- **405 Method Not Allowed**: bandingkan method form (`POST`, `PUT`, `DELETE`) dengan route; cek `@method` serta daftar route lewat `php artisan route:list`.
- **500**: periksa log `storage/logs/laravel.log`, konfigurasi environment, migration, dan exception di server. Jangan menampilkan stack trace ke pengguna.
- **Upload gagal**: cek validasi Laravel, `upload_max_filesize`/`post_max_size`, permission direktori, disk, dan symlink storage bila digunakan.
