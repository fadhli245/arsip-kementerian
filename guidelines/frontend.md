# Standar Frontend

Antarmuka menggunakan Blade dan Bootstrap dengan layout bersama di `resources/views/layouts`. Halaman admin dan pegawai sebaiknya memperluas layout yang sama agar navigasi dan gaya konsisten.

## Blade dan komponen

- Pisahkan halaman berdasarkan area (`admin`, `pegawai`, `auth`) dan gunakan `@extends`, `@section`, serta partial/component untuk elemen berulang.
- Tampilkan data dengan escaped Blade `{{ $value }}`. Gunakan `{!! !!}` hanya untuk HTML tepercaya yang memang perlu dirender.
- Form harus memakai `@csrf`; form update/delete harus memakai `@method('PUT')`, `@method('PATCH')`, atau `@method('DELETE')` sesuai route.
- Tampilkan error validasi dari `$errors` dekat field terkait dan pertahankan input dengan `old()` bila aman.
- Berikan label yang terhubung ke input, state fokus yang terlihat, teks tombol yang jelas, dan layout yang tetap nyaman pada layar kecil.

## Bootstrap dan UX

- Utamakan kelas dan komponen Bootstrap yang sudah tersedia di proyek sebelum menambah CSS atau JavaScript baru.
- Gunakan tabel untuk data arsip dengan header yang jelas, aksi yang mudah dikenali, pagination, serta tampilan kosong ketika belum ada data.
- Konfirmasi aksi yang menghapus atau mengubah data penting. Hindari mengandalkan warna saja untuk menyampaikan status.
- Jangan menaruh aturan bisnis atau query database di template.

## Alur request

- Gunakan route bernama (`route('...')`) dan method form sesuai definisi route.
- Untuk unggah file, pastikan form menggunakan `enctype="multipart/form-data"`; berikan validasi dan pesan ukuran/jenis file yang diperbolehkan.
- Tampilkan pesan sukses atau gagal dari session setelah redirect, tanpa membocorkan detail internal.
