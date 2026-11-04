@extends('layouts.app')

@section('title', 'Riwayat Aktivitas - SIM Arsip')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">
    <div>
        <h2 style="margin: 0; color: #0A2558; font-size: 24px; font-weight: 800;">Riwayat Aktivitas Pengguna</h2>
        <p style="margin: 6px 0 0; color: #64748B;">Catatan login dan perubahan data penting yang dilakukan di sistem.</p>
    </div>

    <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 12px rgba(15, 23, 42, .04);">
        <div style="padding: 18px 22px; border-bottom: 1px solid #E2E8F0; font-weight: 700; color: #0F172A;">Log Aktivitas</div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                <thead>
                    <tr style="background: #F8FAFC; color: #475569;">
                        <th style="padding: 13px 18px;">Pengguna</th>
                        <th style="padding: 13px 18px;">Aktivitas</th>
                        <th style="padding: 13px 18px;">Keterangan</th>
                        <th style="padding: 13px 18px;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr style="border-top: 1px solid #E2E8F0; color: #1E293B;">
                            <td style="padding: 14px 18px;">
                                <div style="font-weight: 700;">{{ $log->user->name }}</div>
                                <div style="margin-top: 3px; color: #64748B;">{{ $log->user->email }}</div>
                            </td>
                            <td style="padding: 14px 18px;">
                                <span style="display: inline-block; padding: 5px 9px; border-radius: 6px; color: #0A4B78; background: #E0F2FE; font-weight: 700;">{{ $log->activity }}</span>
                            </td>
                            <td style="padding: 14px 18px;">{{ $log->description }}</td>
                            <td style="padding: 14px 18px; color: #64748B; white-space: nowrap;">
                                {{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 40px 18px; color: #64748B; text-align: center;">Belum ada aktivitas yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #E2E8F0;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
