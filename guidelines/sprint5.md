# Fokus Sprint 5

Sprint ini memprioritaskan modul manajemen arsip, pemisahan hak akses admin dan pegawai, serta pencatatan aktivitas pengguna. Implementasi harus mengikuti struktur fitur dan route yang sudah ada.

## Ruang lingkup

- **Manajemen arsip**: admin dapat melihat, mencari/filter, menambah, mengubah, dan menghapus arsip sesuai kebutuhan modul; dukung detail serta impor/ekspor hanya jika alur yang disepakati tersedia.
- **Akses pegawai**: pegawai memperoleh akses baca sesuai kebijakan aplikasi dan tidak dapat menjalankan aksi admin dengan memanggil URL secara langsung.
- **Hak akses admin**: lindungi route dan aksi administrasi menggunakan middleware/policy di server, bukan hanya menyembunyikan menu.
- **Log aktivitas**: catat tindakan penting dengan pengguna, jenis aksi, waktu, dan subjek terkait bila tersedia. Hindari menyimpan password, token, atau isi dokumen sensitif ke log.

## Kriteria selesai

- Pengguna yang tidak berhak ditolak pada route dan menerima respons/redirect yang sesuai.
- Validasi server-side berjalan untuk input dan file; kegagalan menampilkan pesan yang dapat dipahami.
- Perubahan data multi-tabel konsisten dan aktivitas penting tercatat.
- Daftar arsip mendukung keadaan kosong dan pagination; antarmuka mengikuti pedoman frontend dan branding.
- Perubahan database disertai migration yang dapat dijalankan pada lingkungan baru.
- Alur utama diverifikasi melalui pengujian yang relevan sebelum rilis.

Catat perubahan ruang lingkup dan keputusan sprint pada issue atau dokumentasi tim; file ini menyatakan target kerja, bukan bukti bahwa setiap fitur telah selesai.
