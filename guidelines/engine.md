# Standar Engine dan Arsitektur

Panduan ini berlaku untuk aplikasi SIM Arsip Kementerian Perhubungan Laut. Proyek menggunakan Laravel 10, PHP 8.1+, dan MySQL.

## Struktur dan tanggung jawab

- Route di `routes/web.php` untuk halaman web dan `routes/api.php` untuk endpoint API. Beri nama route agar mudah dipakai dari Blade.
- Controller menangani HTTP: validasi input, otorisasi, pemanggilan layanan/model, lalu mengembalikan response atau redirect.
- Logika yang dipakai lintas controller atau proses panjang dapat diletakkan di `app/Services`. Hindari controller gemuk dan query kompleks di Blade.
- Eloquent model berada di `app/Models`; gunakan relasi model daripada merangkai query berulang.
- View Blade berada di `resources/views`; migrasi dan seeder masing-masing di `database/migrations` dan `database/seeders`.

## Database dan Eloquent

- Buat perubahan skema melalui migration. Gunakan tipe kolom, indeks, foreign key, dan aturan nullability yang sesuai; jangan mengubah database secara manual sebagai pengganti migration.
- Nama tabel jamak snake_case, kolom snake_case, dan model singular PascalCase.
- Definisikan relasi dengan return type yang jelas, misalnya `belongsTo` atau `hasMany`. Pastikan foreign key dan perilaku penghapusan konsisten.
- Gunakan `$fillable` atau `$guarded` secara sadar dan validasi semua input sebelum mass assignment.
- Untuk daftar arsip, gunakan pagination, eager loading (`with`) saat relasi ditampilkan, dan filter yang dibatasi pada kolom yang diizinkan.
- Simpan file arsip di storage disk, bukan sebagai blob di tabel. Simpan path dan metadata yang diperlukan di database.

## Keamanan dan kualitas

- Otorisasi dilakukan di middleware, policy, atau gate; menyembunyikan tombol di UI bukan kontrol akses.
- Jangan mengembalikan detail exception, kredensial, atau data sensitif kepada pengguna.
- Gunakan transaksi saat satu operasi menulis beberapa tabel yang harus konsisten.
- Ikuti gaya Laravel yang sudah ada di proyek dan jalankan formatter Pint bila tersedia.
