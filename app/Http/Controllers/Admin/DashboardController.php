<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\ArchiveRequest;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $activityTypes = ['Unduh Arsip', 'Preview Arsip'];
        $totalArsip = Arsip::count();
        $pegawaiCount = User::whereIn('role', ['user', 'pegawai'])->where('is_active', true)->count();
        $adminCount = User::whereIn('role', ['admin', 'super_admin', 'admin_divisi'])->where('is_active', true)->count();
        $totalPenggunaAktif = $pegawaiCount + $adminCount;
        $totalUnduhan = ActivityLog::where('activity', 'Unduh Arsip')->count();
        $totalAkses = ActivityLog::whereIn('activity', $activityTypes)->count();
        $permohonanMenunggu = ArchiveRequest::where('status', 'Menunggu')->count();
        $arsipBulanIni = Arsip::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $arsipTerbaru = Arsip::with('user')->latest()->take(5)->get();
        $penggunaTerakhirLogin = User::orderByDesc('last_login_at')->take(6)->get();
        $logsTerbaru = ActivityLog::with(['user', 'subject'])->latest()->take(8)->get();
        $permohonanTerbaru = ArchiveRequest::with('user')->where('status', 'Menunggu')->latest()->take(4)->get();

        $tahunAktif = (int) request('tahun', now()->year);
        $daftarTahun = Arsip::query()->selectRaw('YEAR(created_at) as year')->distinct()->orderByDesc('year')->pluck('year')->map(fn ($year) => (int) $year);
        if ($daftarTahun->isEmpty()) $daftarTahun = collect([now()->year]);
        if (!$daftarTahun->contains($tahunAktif)) $tahunAktif = (int) $daftarTahun->first();
        $grafik = $this->chartData($tahunAktif);

        $categoryAccess = ActivityLog::query()
            ->whereIn('activity', $activityTypes)
            ->where('subject_type', (new Arsip())->getMorphClass())
            ->join('arsips', function ($join) {
                $join->on('activity_logs.subject_id', '=', 'arsips.id');
            })
            ->select('arsips.kategori', DB::raw('COUNT(activity_logs.id) as total'))
            ->groupBy('arsips.kategori')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $categoryMaxAccess = max(1, (int) ($categoryAccess->max('total') ?? 0));
        $quotaBytes = max(1, (int) (config('arsip.storage_quota_gb', 10) * 1024 * 1024 * 1024));
        $storageUsedBytes = (int) Arsip::sum('ukuran_bytes');
        $storageUnknownCount = Arsip::whereNull('ukuran_bytes')->count();
        $storagePercent = round($storageUsedBytes / $quotaBytes * 100, 1);
        $dbOk = true;
        try { DB::connection()->getPdo(); } catch (\Throwable $exception) { $dbOk = false; }
        try {
            $storageDisk = Storage::disk(config('filesystems.default'));
            $storageOk = method_exists($storageDisk, 'path') && is_writable($storageDisk->path(''));
        } catch (\Throwable $exception) {
            $storageOk = false;
        }
        $statusSystemHealthy = $dbOk && $storageOk && $storagePercent <= 90 && $storageUnknownCount === 0;
        $statusMessage = !$dbOk ? 'Koneksi database bermasalah.' : (!$storageOk ? 'Penyimpanan tidak dapat ditulis.' : ($storagePercent > 90 ? 'Kapasitas penyimpanan ' . $storagePercent . '%, segera tinjau.' : ($storageUnknownCount ? 'Ukuran file belum tercatat untuk ' . $storageUnknownCount . ' arsip.' : 'Sistem berjalan normal.')));
        $statusSistem = ['db_ok' => $dbOk, 'storage_ok' => $storageOk, 'storage_used_bytes' => $storageUsedBytes, 'storage_quota_bytes' => $quotaBytes, 'storage_persen' => $storagePercent, 'ukuran_tidak_diketahui' => $storageUnknownCount, 'pesan' => $statusMessage, 'healthy' => $statusSystemHealthy];
        $greeting = now()->hour < 11 ? 'Selamat Pagi' : (now()->hour < 15 ? 'Selamat Siang' : (now()->hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        $storageUsedHuman = $this->humanBytes($storageUsedBytes);
        $storageQuotaHuman = $this->humanBytes($quotaBytes);
        $daftarNotifikasi = auth()->user()->dashboardNotifications()->latest()->take(5)->get();
        $notifikasiBelumDibaca = auth()->user()->dashboardNotifications()->whereNull('read_at')->count();

        return view('admin.dashboard', compact(
            'totalArsip', 'totalUnduhan', 'totalAkses', 'arsipBulanIni', 'totalPenggunaAktif', 'adminCount', 'pegawaiCount',
            'permohonanMenunggu', 'permohonanTerbaru', 'arsipTerbaru', 'penggunaTerakhirLogin',
            'logsTerbaru', 'categoryAccess', 'categoryMaxAccess', 'daftarTahun', 'tahunAktif', 'grafik',
            'statusSistem', 'storageUsedHuman', 'storageQuotaHuman', 'greeting', 'daftarNotifikasi', 'notifikasiBelumDibaca'
        ));
    }

    public function chart(Request $request)
    {
        $availableYears = Arsip::query()->selectRaw('YEAR(created_at) as year')->distinct()->pluck('year')->map(fn ($year) => (int) $year);
        $year = (int) $request->query('tahun', now()->year);
        abort_unless($year >= 2000 && $year <= now()->year + 1 && ($availableYears->isEmpty() || $availableYears->contains($year)), 422);

        return response()->json($this->chartData($year));
    }

    public function markNotificationsRead()
    {
        auth()->user()->dashboardNotifications()->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['message' => 'Notifikasi ditandai sudah dibaca.', 'unread' => 0]);
    }

    private function chartData(int $year): array
    {
        $counts = Arsip::query()->selectRaw('MONTH(created_at) as month, COUNT(*) as total')->whereYear('created_at', $year)->groupBy('month')->pluck('total', 'month');
        $labels = collect(range(1, 12))->map(fn ($month) => Carbon::create($year, $month, 1)->locale('id')->translatedFormat('M'))->all();
        $data = collect(range(1, 12))->map(fn ($month) => (int) $counts->get($month, 0))->all();
        return ['year' => $year, 'labels' => $labels, 'data' => $data, 'total' => array_sum($data)];
    }

    private function humanBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = max(0, $bytes);
        $index = 0;
        while ($size >= 1024 && $index < count($units) - 1) { $size /= 1024; $index++; }
        return number_format($size, $index === 0 ? 0 : 1, ',', '.') . ' ' . $units[$index];
    }

    public function aktivitas()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(15);

        return view('admin.aktivitas', compact('logs'));
    }

    public function permohonan()
    {
        $permohonan = ArchiveRequest::with('user')->latest()->paginate(15);
        $arsipMenunggu = Arsip::with('user')->whereIn('approval_status', ['Draft', 'Menunggu'])->latest()->paginate(15, ['*'], 'arsip_page');

        return view('admin.permohonan', compact('permohonan', 'arsipMenunggu'));
    }

    public function updatePermohonan(Request $request, ArchiveRequest $archiveRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Diproses,Disetujui,Ditolak'],
            'alasan_penolakan' => ['nullable', 'required_if:status,Ditolak', 'string', 'min:10', 'max:300'],
        ]);

        abort_unless(!in_array($archiveRequest->status, ['Disetujui', 'Ditolak'], true), 409, 'Permohonan ini sudah diproses.');
        DB::transaction(function () use ($validated, $archiveRequest) {
            $archiveRequest->update([
                'status' => $validated['status'],
                'alasan_penolakan' => $validated['status'] === 'Ditolak' ? ($validated['alasan_penolakan'] ?? null) : null,
            ]);
            ActivityLog::create([
                'user_id' => auth()->id(), 'activity' => $validated['status'] === 'Disetujui' ? 'Setujui Permohonan' : 'Tolak Permohonan',
                'description' => 'Mengubah status permohonan ' . $archiveRequest->judul_arsip . ' menjadi ' . $archiveRequest->status,
                'subject_type' => $archiveRequest->getMorphClass(), 'subject_id' => $archiveRequest->id,
            ]);
            UserNotification::create([
                'user_id' => $archiveRequest->user_id,
                'type' => $validated['status'] === 'Disetujui' ? 'success' : ($validated['status'] === 'Ditolak' ? 'danger' : 'info'),
                'title' => 'Permohonan ' . strtolower($validated['status']),
                'message' => $archiveRequest->judul_arsip . ($archiveRequest->alasan_penolakan ? ': ' . $archiveRequest->alasan_penolakan : ''),
                'url' => route('pegawai.dashboard'),
            ]);
        });

        return back()->with('success', 'Status permohonan berhasil diperbarui.');
    }

    public function updateArchiveApproval(Request $request, Arsip $arsip)
    {
        $validated = $request->validate([
            'approval_status' => ['required', 'in:Draft,Menunggu,Disetujui,Ditolak'],
        ]);

        $arsip->update($validated);
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Verifikasi Arsip',
            'description' => 'Memperbarui status publikasi arsip ' . $arsip->judul . ' menjadi ' . $arsip->approval_status,
            'subject_type' => $arsip->getMorphClass(),
            'subject_id' => $arsip->id,
        ]);

        return back()->with('success', 'Status publikasi arsip berhasil diperbarui.');
    }
}
