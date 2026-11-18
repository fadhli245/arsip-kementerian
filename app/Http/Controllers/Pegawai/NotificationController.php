<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->dashboardNotifications()->latest()->paginate(20);

        return view('pegawai.notifications.index', compact('notifications'));
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->dashboardNotifications()->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json(['unread' => 0, 'message' => 'Semua notifikasi ditandai telah dibaca.']);
    }
}
