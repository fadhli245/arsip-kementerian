@extends('layouts.app')

@section('title', 'Notifikasi - SIM Arsip')

@section('content')
<section style="max-width:900px;margin:0 auto;padding:22px;background:#fff;border:1px solid #e2e8f0;border-radius:16px">
    <h1 style="margin:0 0 18px;color:#0f294a;font-size:21px">Notifikasi</h1>
    @forelse($notifications as $notification)
        <a href="{{ $notification->url ?: route('pegawai.dashboard') }}" style="display:flex;gap:12px;padding:14px 0;border-bottom:1px solid #e2e8f0;color:inherit;text-decoration:none">
            <span aria-hidden="true" style="display:grid;width:30px;height:30px;place-items:center;border-radius:9px;background:#eff6ff;color:#1d4ed8">{{ $notification->type === 'success' ? '✓' : '•' }}</span>
            <span><strong style="display:block;color:#0f294a;font-size:12px">{{ $notification->title }}</strong><small style="color:#64748b">{{ $notification->message }}</small><small style="display:block;margin-top:3px;color:#94a3b8">{{ $notification->created_at->locale('id')->diffForHumans() }}</small></span>
            @if(!$notification->read_at)<i aria-label="Belum dibaca" style="width:7px;height:7px;margin:6px 0 0 auto;border-radius:50%;background:#2563eb"></i>@endif
        </a>
    @empty
        <p style="padding:30px;color:#64748b;text-align:center">Belum ada notifikasi.</p>
    @endforelse
    @if($notifications->hasPages())<div style="margin-top:18px">{{ $notifications->links() }}</div>@endif
</section>
@endsection
