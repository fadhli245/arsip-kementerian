<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ArchiveRequest;
use App\Models\Arsip;
use App\Models\ArchiveCategory;
use App\Services\ActivityLogger;
use App\Services\ArchiveAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $kategori = $request->query('kategori');
        $kategoriList = ArchiveCategory::where('is_active', true)->orderBy('name')->pluck('name');

        $arsipQuery = ArchiveAccess::applyTo(Arsip::query(), $request->user())->latest();
        if ($kategori && $kategoriList->contains($kategori)) {
            $arsipQuery->where('kategori', $kategori);
        }

        $activityQuery = ActivityLog::where('user_id', $userId);
        $riwayat = (clone $activityQuery)->whereIn('activity', ['Unduh Arsip', 'Preview Arsip'])
            ->with('subject')->latest()->take(5)->get();

        return view('pegawai.dashboard', [
            'arsips' => $arsipQuery->take(5)->get(),
            'kategoriAktif' => $kategoriList->contains($kategori) ? $kategori : '',
            'kategoriList' => $kategoriList,
            'riwayat' => $riwayat,
            'permohonan' => ArchiveRequest::where('user_id', $userId)->latest()->take(5)->get(),
            'totalArsip' => ArchiveAccess::applyTo(Arsip::query(), $request->user())->count(),
            'totalAkses' => (clone $activityQuery)->whereIn('activity', ['Unduh Arsip', 'Preview Arsip'])->count(),
            'totalUnduhan' => (clone $activityQuery)->where('activity', 'Unduh Arsip')->count(),
            'totalPreview' => (clone $activityQuery)->where('activity', 'Preview Arsip')->count(),
            'totalPermohonan' => ArchiveRequest::where('user_id', $userId)->count(),
        ]);
    }

    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'judul_arsip' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        $archiveRequest = ArchiveRequest::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        ActivityLogger::record('Ajukan Permohonan Arsip', 'Mengajukan permohonan: ' . $archiveRequest->judul_arsip, $archiveRequest);

        return redirect()->route('pegawai.dashboard')->with('success', 'Permohonan arsip berhasil dikirim dan berstatus Menunggu.');
    }
}
