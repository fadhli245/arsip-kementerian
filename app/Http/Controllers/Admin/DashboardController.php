<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\ArchiveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $activityTypes = ['Unduh Arsip', 'Preview Arsip'];
        $totalArsip = Arsip::count();
        $totalPegawai = User::whereIn('role', ['user', 'pegawai'])->count();
        $totalUnduhan = ActivityLog::where('activity', 'Unduh Arsip')->count();
        $totalAkses = ActivityLog::whereIn('activity', $activityTypes)->count();
        $permohonanMenunggu = ArchiveRequest::where('status', 'Menunggu')->count();
        $arsipTerbaru = Arsip::with('user')->latest()->take(6)->get();
        $penggunaTerakhirLogin = User::orderByDesc('last_login_at')->take(6)->get();
        $logsTerbaru = ActivityLog::with(['user', 'subject'])->latest()->take(8)->get();

        $uploadsBulanan = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->startOfMonth()->subMonths($monthsAgo);
            return [
                'label' => $date->translatedFormat('M'),
                'total' => Arsip::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
            ];
        });
        $maxUpload = max(1, (int) $uploadsBulanan->max('total'));

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

        return view('admin.dashboard', compact(
            'totalArsip', 'totalPegawai', 'totalUnduhan', 'totalAkses',
            'permohonanMenunggu', 'arsipTerbaru', 'penggunaTerakhirLogin',
            'logsTerbaru', 'uploadsBulanan', 'maxUpload', 'categoryAccess'
        ));
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
        ]);

        $archiveRequest->update($validated);
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Ubah Status Permohonan',
            'description' => 'Mengubah status permohonan ' . $archiveRequest->judul_arsip . ' menjadi ' . $archiveRequest->status,
            'subject_type' => $archiveRequest->getMorphClass(),
            'subject_id' => $archiveRequest->id,
        ]);

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
