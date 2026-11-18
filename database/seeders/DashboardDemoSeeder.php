<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\ArchiveRequest;
use App\Models\Arsip;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = User::whereIn('role', ['user', 'pegawai'])->orderBy('id')->first();
        if (!$pegawai) {
            $this->command?->warn('Seeder dashboard dilewati: buat atau siapkan akun pegawai terlebih dahulu.');
            return;
        }

        $file = 'dashboard-demo/arsip-simulasi.pdf';
        Storage::disk('local')->put($file, $this->samplePdf());

        $categories = ['Laporan Kegiatan', 'Surat Masuk', 'Surat Keputusan (SK)', 'Surat Keluar'];
        $titles = [
            'Laporan Koordinasi Keselamatan Pelayaran',
            'Surat Masuk Permohonan Data Pelabuhan',
            'Keputusan Penetapan Tim Pemeriksa Kapal',
            'Surat Keluar Tindak Lanjut Evaluasi Dermaga',
            'Laporan Pemantauan Alur Pelayaran',
            'Surat Masuk Undangan Rapat Teknis',
            'Keputusan Penugasan Inspektur Maritim',
            'Laporan Kegiatan Digitalisasi Arsip',
            'Surat Keluar Penyampaian Rekapitulasi',
            'Laporan Evaluasi Layanan Kepelabuhanan',
            'Surat Masuk Permintaan Informasi Navigasi',
            'Keputusan Pembentukan Kelompok Kerja Arsip',
        ];
        $numbers = [
            '124/HUBLA/X/2026', '087/HUBLA/IX/2026', '031/HUBLA/VIII/2026', '156/HUBLA/VII/2026',
            '102/HUBLA/VI/2026', '063/HUBLA/V/2026', '041/HUBLA/IV/2026', '119/HUBLA/III/2026',
            '075/HUBLA/II/2026', '022/HUBLA/I/2026', '208/HUBLA/XII/2025', '192/HUBLA/XI/2025',
        ];
        $archives = collect();

        foreach ($titles as $index => $title) {
            $archive = Arsip::firstOrCreate(
                ['nomor_surat' => $numbers[$index]],
                [
                    'judul' => $title,
                    'kategori' => $categories[$index % count($categories)],
                    'tanggal_dokumen' => now()->subDays($index * 37)->toDateString(),
                    'keterangan' => 'Data simulasi untuk meninjau fitur dashboard pegawai.',
                    'file_path' => $file,
                    'user_id' => $pegawai->id,
                    'classification' => $index === 11 ? 'Terbatas' : ($index === 5 ? 'Publik' : 'Internal'),
                    'allowed_divisions' => $index === 11 ? ['__DEMO_NO_ACCESS__'] : null,
                    'is_active' => true,
                    'approval_status' => 'Disetujui',
                    'format' => 'PDF',
                    'ukuran_bytes' => 18432,
                    'unit_pengunggah' => $pegawai->division ?: 'Direktorat Jenderal Perhubungan Laut',
                    'tanggal_unggah' => now()->subDays($index * 8),
                ]
            );
            $archives->push($archive);
        }

        foreach ($archives->take(2) as $archive) {
            $pegawai->favorites()->syncWithoutDetaching([$archive->id]);
        }

        foreach ($archives->take(3) as $index => $archive) {
            ActivityLog::firstOrCreate([
                'user_id' => $pegawai->id,
                'activity' => $index === 1 ? 'Unduh Arsip' : 'Preview Arsip',
                'subject_type' => Arsip::class,
                'subject_id' => $archive->id,
            ], ['description' => 'Aktivitas simulasi pada ' . $archive->judul]);
        }

        foreach ([
            ['status' => 'Menunggu', 'judul_arsip' => 'Arsip simulasi rencana pemeliharaan kapal', 'alasan_penolakan' => null],
            ['status' => 'Disetujui', 'judul_arsip' => 'Arsip simulasi laporan inspeksi pelabuhan', 'alasan_penolakan' => null],
            ['status' => 'Ditolak', 'judul_arsip' => 'Arsip simulasi dokumen operasional', 'alasan_penolakan' => 'Mohon lengkapi unit dan rentang tanggal dokumen yang dibutuhkan.'],
        ] as $item) {
            ArchiveRequest::firstOrCreate(
                ['user_id' => $pegawai->id, 'judul_arsip' => $item['judul_arsip']],
                [
                    'kategori' => 'Laporan Kegiatan',
                    'referensi' => $item['status'] === 'Disetujui' ? 'DEMO-HUBLA-001' : null,
                    'keterangan' => 'Data simulasi untuk menguji status dan riwayat permohonan.',
                    'status' => $item['status'],
                    'alasan_penolakan' => $item['alasan_penolakan'],
                ]
            );
        }

        UserNotification::firstOrCreate(
            ['user_id' => $pegawai->id, 'title' => 'Dokumen baru ditambahkan', 'message' => 'Arsip simulasi tersedia untuk ditinjau.'],
            ['type' => 'info', 'url' => route('pegawai.dashboard')]
        );
    }

    private function samplePdf(): string
    {
        $content = "BT /F1 18 Tf 72 740 Td (SIM Arsip - Dokumen Simulasi) Tj 0 -28 Td /F1 11 Tf (Berkas ini digunakan untuk pratinjau dan pengujian dashboard.) Tj ET";
        $objects = [
            '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj',
            '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj',
            '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj',
            '4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj',
            '5 0 obj << /Length ' . strlen($content) . " >> stream\n" . $content . "\nendstream endobj",
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object . "\n";
        }
        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf('%010d 00000 n ', $offset) . "\n";
        }
        return $pdf . 'trailer << /Size ' . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xrefOffset . "\n%%EOF";
    }
}
