<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ArchiveCategory;
use App\Models\ArchiveRequest;
use App\Models\Arsip;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\ArchiveAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $categoryList = ArchiveCategory::where('is_active', true)->orderBy('name')->pluck('name');
        $visible = fn () => ArchiveAccess::applyTo(Arsip::query(), $user);
        $kategori = $request->query('kategori');
        $kategoriAktif = $categoryList->contains($kategori) ? $kategori : '';
        $year = filter_var($request->query('tahun'), FILTER_VALIDATE_INT);

        $arsipQuery = $visible()->with('user')->latest('tanggal_dokumen')->latest('id');
        if ($kategoriAktif !== '') {
            $arsipQuery->where('kategori', $kategoriAktif);
        }
        if ($year && $year >= 1900 && $year <= 2200) {
            $arsipQuery->whereYear('tanggal_dokumen', $year);
        }
        $archivePage = $arsipQuery->paginate(10)->withQueryString();

        $activityQuery = ActivityLog::where('user_id', $userId);
        $history = (clone $activityQuery)
            ->whereIn('activity', ['Unduh Arsip', 'Preview Arsip'])
            ->with(['subject' => fn (MorphTo $morphTo) => $morphTo->morphWith([Arsip::class => ['user']])])
            ->latest()
            ->take(8)
            ->get();
        $activityMonth = (clone $activityQuery)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        $activityPreviousMonth = (clone $activityQuery)->whereBetween('created_at', [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()]);
        $monthAccess = (clone $activityMonth)->whereIn('activity', ['Unduh Arsip', 'Preview Arsip'])->count();
        $previousAccess = (clone $activityPreviousMonth)->whereIn('activity', ['Unduh Arsip', 'Preview Arsip'])->count();
        $accessTrend = $previousAccess > 0 ? (int) round((($monthAccess - $previousAccess) / $previousAccess) * 100) : null;

        $distributionRows = $visible()->select('kategori', DB::raw('COUNT(*) as total'))->groupBy('kategori')->get();
        $distribution = $distributionRows->map(fn ($row) => [
            'label' => $row->kategori,
            'value' => (int) $row->total,
        ])->values();
        $categoryCounts = $distribution->mapWithKeys(fn ($item) => [$item['label'] => $item['value']]);

        $favoriteIds = $user->favorites()->select('arsips.id');
        $favorites = $visible()
            ->whereIn('id', $favoriteIds)
            ->with('user')
            ->latest('arsips.id')
            ->limit(4)
            ->get();
        $favoriteIds = $user->favorites()->pluck('arsips.id')->map(fn ($id) => (int) $id)->all();
        $archiveData = $archivePage->getCollection()->map(fn (Arsip $arsip) => [
            'id' => $arsip->id,
            'judul' => $arsip->judul,
            'nomor' => $arsip->nomor_surat ?: 'Nomor surat tidak tersedia',
            'kategori' => $arsip->kategori,
            'format' => $arsip->format_label,
            'ukuran' => $arsip->ukuran_human,
            'tanggal' => $arsip->tanggal_dokumen?->format('d/m/Y') ?? '-',
            'tahun' => $arsip->tanggal_dokumen?->format('Y') ?? '',
            'akses' => $arsip->classification,
            'unit' => $arsip->unit_pengunggah ?: ($arsip->user?->division ?: $arsip->user?->name ?: 'Unit tidak tersedia'),
            'tanggalUnggah' => ($arsip->tanggal_unggah ?: $arsip->created_at)?->format('d M Y') ?? '-',
            'favorit' => in_array((int) $arsip->id, $favoriteIds, true),
            'previewUrl' => route('pegawai.arsip.preview', $arsip->id),
            'previewLogUrl' => route('pegawai.arsip.log-preview', $arsip->id),
            'downloadUrl' => route('pegawai.arsip.download', $arsip->id),
        ])->values();
        $favoriteData = $favorites->map(fn (Arsip $arsip) => [
            'id' => $arsip->id,
            'judul' => $arsip->judul,
            'nomor' => $arsip->nomor_surat ?: 'Nomor surat tidak tersedia',
            'kategori' => $arsip->kategori,
            'format' => $arsip->format_label,
            'ukuran' => $arsip->ukuran_human,
            'tanggal' => $arsip->tanggal_dokumen?->format('d/m/Y') ?? '-',
            'tahun' => $arsip->tanggal_dokumen?->format('Y') ?? '',
            'akses' => $arsip->classification,
            'unit' => $arsip->unit_pengunggah ?: ($arsip->user?->division ?: $arsip->user?->name ?: 'Unit tidak tersedia'),
            'tanggalUnggah' => ($arsip->tanggal_unggah ?: $arsip->created_at)?->format('d M Y') ?? '-',
            'favorit' => true,
            'previewUrl' => route('pegawai.arsip.preview', $arsip->id),
            'previewLogUrl' => route('pegawai.arsip.log-preview', $arsip->id),
            'downloadUrl' => route('pegawai.arsip.download', $arsip->id),
        ])->values();
        $historyData = $history->map(function (ActivityLog $log) {
            $arsip = $log->subject;
            if (!$arsip instanceof Arsip) {
                return null;
            }

            return [
                'id' => $arsip->id,
                'judul' => $arsip->judul,
                'nomor' => $arsip->nomor_surat ?: 'Nomor surat tidak tersedia',
                'kategori' => $arsip->kategori,
                'format' => $arsip->format_label,
                'ukuran' => $arsip->ukuran_human,
                'tanggal' => $arsip->tanggal_dokumen?->format('d/m/Y') ?? '-',
                'tahun' => $arsip->tanggal_dokumen?->format('Y') ?? '',
                'akses' => $arsip->classification,
                'unit' => $arsip->unit_pengunggah ?: ($arsip->user?->division ?: $arsip->user?->name ?: 'Unit tidak tersedia'),
                'tanggalUnggah' => ($arsip->tanggal_unggah ?: $arsip->created_at)?->format('d M Y') ?? '-',
                'favorit' => false,
                'previewUrl' => route('pegawai.arsip.preview', $arsip->id),
                'previewLogUrl' => route('pegawai.arsip.log-preview', $arsip->id),
                'downloadUrl' => route('pegawai.arsip.download', $arsip->id),
            ];
        })->filter()->values();

        return view('pegawai.dashboard', [
            'pegawai' => $user,
            'arsips' => $archivePage,
            'arsipDashboardData' => $archiveData,
            'kategoriAktif' => $kategoriAktif,
            'kategoriList' => $categoryList,
            'jumlahKategori' => $categoryCounts,
            'tahunAktif' => $year ? (string) $year : '',
            'daftarTahun' => $visible()->whereNotNull('tanggal_dokumen')->selectRaw('YEAR(tanggal_dokumen) as tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
            'riwayat' => $history,
            'riwayatDashboardData' => $historyData,
            'permohonan' => ArchiveRequest::where('user_id', $userId)->latest()->take(5)->get(),
            'totalPermohonan' => ArchiveRequest::where('user_id', $userId)->count(),
            'totalArsip' => $visible()->count(),
            'arsipBaruMingguIni' => $visible()->where('created_at', '>=', now()->startOfWeek())->count(),
            'totalAkses' => (clone $activityQuery)->whereIn('activity', ['Unduh Arsip', 'Preview Arsip'])->count(),
            'aksesPersenVsBulanLalu' => $accessTrend,
            'totalUnduhan' => (clone $activityQuery)->where('activity', 'Unduh Arsip')->count(),
            'unduhanBulanIni' => (clone $activityMonth)->where('activity', 'Unduh Arsip')->count(),
            'totalPreview' => (clone $activityQuery)->where('activity', 'Preview Arsip')->count(),
            'previewBulanIni' => (clone $activityMonth)->where('activity', 'Preview Arsip')->count(),
            'distribusiKategori' => $distribution,
            'favorit' => $favorites,
            'favoriteData' => $favoriteData,
            'favoriteIds' => $favoriteIds,
            'daftarNotifikasi' => $user->dashboardNotifications()->latest()->take(5)->get(),
            'notifikasiBelumDibaca' => $user->dashboardNotifications()->whereNull('read_at')->count(),
        ]);
    }

    public function storeRequest(\App\Http\Requests\Pegawai\StoreArchiveRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            $archiveRequest = ArchiveRequest::create([
                'user_id' => $request->user()->id,
                'judul_arsip' => $data['judul_arsip'],
                'kategori' => $data['kategori'],
                'referensi' => $data['referensi'] ?? null,
                'keterangan' => $data['keterangan'],
            ]);

            \App\Services\ActivityLogger::record(
                'Ajukan Permohonan Arsip',
                'Mengajukan permohonan: ' . $archiveRequest->judul_arsip,
                $archiveRequest
            );

            User::whereIn('role', ['admin', 'super_admin', 'admin_divisi'])
                ->where('is_active', true)
                ->pluck('id')
                ->each(fn ($adminId) => UserNotification::create([
                    'user_id' => $adminId,
                    'type' => 'permohonan',
                    'title' => 'Permohonan arsip baru',
                    'message' => $request->user()->name . ' mengajukan ' . $archiveRequest->judul_arsip,
                    'url' => route('admin.permohonan.index'),
                ]));
        });

        return redirect()->route('pegawai.dashboard')->with('success', 'Permohonan berhasil diajukan.');
    }
}
