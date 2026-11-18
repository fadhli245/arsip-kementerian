# Tugas: Sempurnakan Dashboard Admin SIM Arsip

## Peran dan tujuan

Kamu adalah senior fullstack engineer yang mengerjakan aplikasi SIM Arsip Direktorat Jenderal Perhubungan Laut, Kementerian Perhubungan.

Sempurnakan Dashboard Admin pada `/admin/dashboard` sampai semua bagian yang disepakati berfungsi, responsif, dan aman. Dashboard harus konsisten dengan Dashboard Pegawai serta pola yang sudah dipakai aplikasi.

Jangan meninggalkan TODO, placeholder kosong, tombol tanpa aksi, atau data contoh yang ditulis langsung di Blade. Jika ada fitur yang tidak bisa diselesaikan karena keterbatasan sistem atau keputusan bisnis, jelaskan penyebab dan dampaknya.

## Pedoman proyek dan pilihan teknologi

Sebelum mengubah kode:

1. Baca `AGENTS.md` dan pedoman yang relevan: `guidelines/engine.md`, `frontend.md`, `branding.md`, `server.md`, `testing.md`, dan `sprint5.md`.
2. Periksa `package.json`, konfigurasi frontend, layout, serta halaman Admin dan Pegawai untuk memastikan teknologi yang sudah digunakan.
3. Ikuti stack dan komponen yang benar-benar tersedia di proyek. Pedoman proyek menetapkan Blade + Bootstrap; jangan memasang Tailwind atau library UI baru jika belum digunakan tanpa alasan teknis yang jelas.
4. Gunakan Alpine.js untuk interaksi ringan dan Chart.js untuk grafik hanya jika tersedia atau dapat ditambahkan tanpa bertentangan dengan struktur build proyek.
5. Pertahankan identitas visual biru maritim dan pola layout bersama yang sudah ada.

## Langkah 0 — Audit sebelum mengedit

Laporkan ringkasan audit secara singkat sebelum menjelaskan implementasi. Periksa:

- View Dashboard Admin, layout, sidebar, topbar, partial, dan komponen Blade.
- Apakah layout atau komponen dipakai bersama Dashboard Pegawai atau halaman lain.
- Route bernama, middleware, controller, policy/gate, serta method HTTP.
- Model, relasi, migration, dan kolom terkait arsip, kategori, permohonan, pengguna, sesi, notifikasi, serta log aktivitas.
- Versi dan konfigurasi frontend, termasuk dukungan tema.
- Test yang tersedia, konfigurasi database testing, dan cara build frontend.

Jangan mengasumsikan nama model, kolom, atau route. Gunakan struktur yang ditemukan di repo.

## Kondisi dashboard saat ini

Dashboard memiliki sidebar Admin, topbar, banner, empat kartu statistik, akses cepat, grafik unggahan bulanan, statistik kategori, aktivitas terbaru, login terakhir, dan tabel arsip terbaru.

Masalah yang perlu ditangani:

- Teks sulit dibaca karena terlalu kecil.
- Kartu statistik belum memiliki ikon atau keterangan yang membantu.
- Lima kartu akses cepat tersusun tidak simetris.
- Belum ada notifikasi dan tindakan cepat untuk permohonan.
- Kategori dan aktivitas belum disajikan dengan badge yang konsisten.
- Belum ada indikator kesehatan sistem dan pemakaian storage.
- Tabel, tema, dan navigasi perlu nyaman digunakan pada layar kecil.

Pertahankan fungsi halaman yang sudah ada. Jangan mengubah perilaku Kelola Arsip, Persetujuan Arsip, Manajemen Pengguna, Kategori, login, atau Dashboard Pegawai tanpa kebutuhan yang terverifikasi.

## Aturan keamanan dan implementasi

- Pertahankan nama route, nama field, middleware, autentikasi, otorisasi, dan alur yang sudah digunakan kecuali audit menunjukkan perbaikan diperlukan.
- Semua aksi Admin harus dilindungi middleware, policy, atau gate di server. Menyembunyikan tombol di UI bukan otorisasi.
- Gunakan route bernama dan method HTTP yang tepat: GET untuk membaca, POST untuk aksi, PUT/PATCH untuk perubahan, dan DELETE untuk penghapusan.
- Semua form web yang mengubah data wajib memakai `@csrf`; gunakan `@method(...)` untuk PUT/PATCH/DELETE.
- Escape data dengan `{{ }}`. Jangan merender data pengguna dengan `{!! !!}`.
- Validasi semua input di server. Gunakan FormRequest jika sesuai pola proyek.
- Aksi penting, termasuk persetujuan, penolakan, perubahan, dan penghapusan, harus tercatat di audit log dengan pelaku, waktu, tindakan, objek terkait, dan IP bila pola aplikasi mendukungnya. Jangan simpan password, token, atau isi dokumen sensitif ke log.
- Gunakan transaksi untuk operasi multi-tabel yang harus berhasil sebagai satu kesatuan.
- Gunakan eager loading untuk relasi yang ditampilkan. Hindari query database di Blade dan masalah N+1.
- Buat migration tambahan hanya jika audit menunjukkan kebutuhan nyata. Migration harus aman dan tidak menghapus atau mengubah data lama tanpa alasan.
- Jangan menjalankan migration destruktif pada database berisi data penting.
- Ikuti konfigurasi storage privat dan otorisasi akses arsip yang sudah ada.

## Bahasa, tanggal, dan angka

- Semua teks antarmuka menggunakan bahasa Indonesia yang jelas dan konsisten.
- Format tanggal mengikuti pola proyek, misalnya `17 Nov 2026` atau `17/11/2026 08:15`.
- Waktu relatif menggunakan locale Indonesia, misalnya “10 menit yang lalu”.
- Angka dan ukuran file menggunakan format yang mudah dibaca pengguna Indonesia.
- Gunakan data database dan relasi; jangan menulis angka dashboard langsung di Blade.

## Desain, tema, dan responsivitas

- Pertahankan palet biru tua maritim, biru aksen, latar netral, dan warna semantik hijau, amber, merah, serta ungu sesuai identitas visual yang ada.
- Tingkatkan keterbacaan: teks isi sekitar 14px atau lebih, label kecil tetap terbaca, jarak antarbagian cukup, dan tombol memiliki area klik nyaman.
- Gunakan kartu dengan batas dan hierarki visual yang konsisten dengan aplikasi.
- Bila dark mode sudah didukung, gunakan mekanisme tema yang sama dengan login dan Dashboard Pegawai. Jangan membuat mekanisme tema kedua.
- Pada lebar 360px, 768px, dan 1440px, grid harus menyesuaikan; tabel dapat digulir di wadahnya sendiri atau berubah menjadi kartu; halaman tidak boleh melebar horizontal.
- Sidebar berubah menjadi drawer pada layar kecil jika sesuai dengan pola proyek.
- Hormati `prefers-reduced-motion`; animasi tidak boleh menghalangi penggunaan.

## Komponen dan ikon

Gunakan komponen yang sudah ada bila memungkinkan. Jika belum ada dan memang bermanfaat, buat komponen bersama untuk avatar, ikon, badge kategori, dan badge aktivitas.

- Ikon dan avatar memakai latar lembut/translusen, bukan bidang warna pekat yang mengurangi keterbacaan.
- Pastikan kontras memadai pada tema terang dan gelap.
- Tombol ikon wajib memiliki `aria-label`, fokus terlihat, tooltip sesuai, dan state loading saat menjalankan aksi.
- Terapkan perubahan ke Dashboard Pegawai hanya jika komponen memang dipakai bersama; jangan mengubah fungsinya.

## Spesifikasi dashboard

### 1. Topbar

- Tampilkan breadcrumb “Direktorat Jenderal Perhubungan Laut”.
- Sediakan tombol menu untuk layar kecil bila sesuai dengan layout proyek.
- Tampilkan kontrol tema hanya jika aplikasi sudah mendukung tema.
- Sediakan tombol notifikasi dengan badge jumlah relevan. Sembunyikan badge saat nol dan tampilkan `9+` bila jumlah melewati sembilan.
- Dropdown notifikasi menampilkan maksimal lima item terbaru, waktu relatif, status belum dibaca, dan tautan valid.
- Sediakan aksi “Tandai semua dibaca” dengan CSRF dan perbarui tampilan setelah server mengonfirmasi keberhasilan.
- Jika tidak ada notifikasi, tampilkan empty state.
- Profil menampilkan avatar, nama, dan unit. Aksi dropdown harus menuju route yang benar-benar tersedia.

Jangan membuat halaman profil atau ubah kata sandi baru hanya berdasarkan asumsi. Tambahkan hanya jika memang menjadi kebutuhan proyek dan implementasinya mengikuti pola autentikasi aplikasi.

### 2. Banner utama

- Tampilkan label “PORTAL ADMINISTRATOR”, judul dashboard, deskripsi, sapaan kepada Admin, dan tanggal lengkap.
- Sapaan dapat menggunakan waktu lokal pengguna dengan isi awal dari server agar tidak kosong saat halaman dimuat.
- Tampilkan status kesehatan sistem hanya jika pemeriksaan dapat dilakukan secara aman dan akurat. Tangani kegagalan database/storage tanpa membocorkan detail internal.
- Tampilkan jumlah permohonan yang menunggu dan tautan ke halaman persetujuan.
- Sediakan tombol “Tambah Arsip” dan “Kelola Permohonan” yang menuju route bernama dan tersedia.
- Pada layar kecil, tombol harus mudah ditekan dan tersusun rapi.

### 3. Kartu statistik

Tampilkan empat kartu dengan tinggi dan susunan konsisten:

1. **Total Arsip Tersimpan** — jumlah arsip serta tambahan pada bulan berjalan.
2. **Permohonan Pending** — jumlah permohonan berstatus menunggu; beri penanda bila tindakan diperlukan.
3. **Total Pengguna Aktif** — angka dan rincian Admin/Pegawai. Definisi “aktif” harus mengikuti data atau aturan aplikasi; jangan menyamakan akun aktif dengan sedang online tanpa dasar.
4. **Kapasitas Penyimpanan** — pemakaian dan kuota. Gunakan ukuran file tersimpan yang tersedia. Jika metadata ukuran belum tersedia, jelaskan atau tambahkan dukungan skema dengan migration aman.

Animasi angka hanya digunakan bila sesuai komponen proyek dan tidak mengganggu aksesibilitas. Nilai akhir tetap harus terbaca tanpa JavaScript.

### 4. Akses cepat

Tampilkan tepat empat kartu simetris:

1. Manajemen Arsip — “Unggah dan kelola metadata dokumen”.
2. Persetujuan Permohonan — “Tinjau permohonan arsip pegawai”. Tampilkan badge pending hanya jika lebih dari nol.
3. Manajemen Pengguna — “Atur role Admin/Pegawai dan akses”.
4. Kategori & Klasifikasi — “Atur jenis surat dan tingkat kerahasiaan”.

Setiap kartu memiliki ikon, judul, deskripsi singkat, dan tautan valid. Akses ke Riwayat Aktivitas tetap tersedia di bagian aktivitas.

### 5. Grafik dan antrean persetujuan

#### Grafik unggahan bulanan

- Tampilkan jumlah arsip per bulan untuk tahun terpilih.
- Tampilkan 12 bulan; bulan tanpa data bernilai nol.
- Sediakan filter tahun berdasarkan tahun yang tersedia di data.
- Jika menggunakan endpoint JSON, validasi tahun dan batasi query di server.
- Saat memuat data, tampilkan loading. Jika gagal, tampilkan pesan yang jelas dan tombol coba lagi.
- Grafik harus terbaca pada tema yang didukung dan memiliki ringkasan alternatif untuk teknologi bantu.
- Tampilkan jumlah setahun serta bulan dengan unggahan terbanyak.
- Tampilkan empty state bila tidak ada data.

#### Permohonan terbaru

- Tampilkan maksimal empat permohonan yang berstatus menunggu.
- Setiap item menampilkan pemohon, unit jika tersedia, judul, kategori, waktu, dan aksi sesuai izin.
- Persetujuan meminta konfirmasi bila diperlukan pola aplikasi.
- Penolakan meminta alasan jika diwajibkan aturan bisnis; validasi panjang dan pesan error konsisten di frontend/server.
- Setelah aksi berhasil, perbarui jumlah pending dan daftar berdasarkan respons server. Jangan mengubah UI permanen sebelum server mengonfirmasi keberhasilan.
- Tangani respons 403, 419, 422, dan kegagalan jaringan dengan pesan sesuai.
- Cegah pengiriman ganda saat aksi sedang diproses.
- Pastikan notifikasi pemohon dan audit log mengikuti mekanisme yang tersedia.
- Jika tidak ada antrean, tampilkan empty state.
- Tautan “Lihat semua permohonan” menuju route persetujuan yang tersedia.

### 6. Aktivitas, pengguna, dan arsip terbaru

#### Aktivitas terbaru

- Tampilkan maksimal delapan aktivitas terbaru.
- Sajikan pelaku, tindakan, objek terkait bila ada, badge jenis aktivitas, dan waktu relatif.
- Bedakan tindakan dengan label dan warna, bukan warna saja.
- Tampilkan empty state bila belum ada aktivitas dan tautan menuju Riwayat Aktivitas.

#### Login terakhir

- Tampilkan lima sampai enam pengguna terakhir masuk bila datanya tersedia.
- Tampilkan nama, role dengan label ramah pengguna, serta waktu login.
- Tampilkan status online hanya bila aplikasi memiliki data sesi/aktivitas yang mendukungnya. Jika tidak, tampilkan waktu login terakhir saja.
- Sediakan tautan ke Manajemen Pengguna.

#### Arsip terbaru

- Tampilkan maksimal lima arsip terbaru dengan judul, nomor surat, kategori, pengunggah, tanggal, dan aksi.
- Gunakan badge kategori yang konsisten dan tautan “Kelola semua”.
- Tampilkan aksi detail/edit/preview/unduh hanya bila route, otorisasi, dan penyimpanan file tersedia.
- Preview dokumen harus melewati otorisasi server. Jangan membuka path storage privat secara langsung.
- Tampilkan fallback untuk tipe file yang tidak dapat dipratinjau atau bila file gagal dimuat.
- Pada layar kecil, tabel tetap dapat digunakan tanpa membuat halaman melebar.
- Tampilkan empty state dan tombol tambah arsip bila belum ada data.

## Data dan query

Jangan memaksakan nama variabel berikut bila struktur aplikasi berbeda. Setelah audit, sediakan data yang konsisten untuk:

- Admin yang sedang login.
- Statistik arsip, permohonan, dan pengguna.
- Ringkasan storage bila dapat dihitung.
- Data grafik 12 bulan dan daftar tahun.
- Permohonan pending terbaru.
- Kategori paling banyak diakses jika sumber data memang tersedia.
- Aktivitas terbaru, login terakhir jika tersedia, dan arsip terbaru.
- Notifikasi dan jumlah belum dibaca jika fitur notifikasi tersedia.

Gunakan eager loading untuk relasi yang ditampilkan. Tambahkan indeks hanya setelah memeriksa migration serta pola query yang benar-benar memerlukannya.

Seeder contoh boleh ditambahkan bila sejalan dengan pola proyek. Seeder harus idempoten, tidak menghapus data, dan tidak berjalan otomatis di produksi. Jelaskan cara menjalankan dan membersihkan data contoh dengan aman.

## Endpoint

Tambahkan endpoint hanya bila belum tersedia dan benar-benar diperlukan oleh UI. Semua aksi Admin harus menggunakan middleware/policy otorisasi yang sesuai.

Sebelum menambah atau mengganti route, periksa `route:list` dan pertahankan nama serta method yang digunakan halaman lain. Endpoint baru harus memiliki nama route konsisten, validasi server, response JSON konsisten, penanganan CSRF/session, dan tidak membocorkan exception atau data sensitif.

## Verifikasi

Jalankan pemeriksaan yang relevan dan laporkan hasilnya satu per satu. Gunakan database testing terpisah jika test menyentuh database. Jangan menjalankan migration atau seeder pada data lokal berharga tanpa memastikan target database.

Verifikasi:

1. Migration dapat berjalan pada lingkungan testing bersih jika ada migration baru.
2. Build frontend berhasil.
3. Test otomatis yang relevan lulus.
4. Dashboard menampilkan data dan empty state.
5. Filter grafik dan state gagal memuat bekerja bila fitur diterapkan.
6. Aksi persetujuan/penolakan tervalidasi, terotorisasi, dan tercatat sesuai mekanisme proyek.
7. Tamu, Admin, dan Pegawai mendapat akses sesuai aturan; uji akses langsung ke endpoint aksi Admin.
8. Tautan dan tombol menuju route valid.
9. Tampilan responsif pada 360px, 768px, dan 1440px tanpa scroll horizontal halaman.
10. Dashboard Pegawai, login, dan halaman Admin lain yang terdampak tidak mengalami regresi.
11. Periksa console browser atau log aplikasi bila lingkungan memungkinkan.

Jangan menyatakan pemeriksaan visual, browser, atau otorisasi berhasil jika tidak dijalankan. Tandai mana yang **Lulus**, **Gagal**, atau **Belum diverifikasi**, beserta alasannya.

## Format laporan akhir

Sampaikan secara ringkas:

1. Ringkasan audit awal.
2. Perubahan yang dilakukan dan alasan utamanya.
3. Daftar file yang dibuat/diubah, dikelompokkan menurut view, controller, route, migration, seeder, dan aset frontend.
4. Cara mengganti data contoh dengan data nyata, jika seeder ditambahkan.
5. Cara mengubah kuota storage, jika konfigurasi tersebut diterapkan.
6. Checklist verifikasi dengan status **Lulus**, **Gagal**, atau **Belum diverifikasi** dan alasan spesifik.
