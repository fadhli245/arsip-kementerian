@extends('layouts.app')

@section('title', 'Detail Arsip - SIM Arsip')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; max-width: 900px; margin: 0 auto; width: 100%;">

    <!-- Header Form -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 15px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #0A2558; margin: 0;">Rincian Dokumen Arsip</h2>
            <p style="font-size: 13.5px; color: #64748B; margin: 5px 0 0 0;">Informasi detail dari arsip kedinasan yang dipilih.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" style="background-color: #F8FAFC; color: #0A2558; border: 1px solid #CBD5E1; font-weight: 700; font-size: 13px; padding: 10px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s;">
            ← Kembali
        </a>
    </div>

    <!-- Card Detail Utama -->
    <div style="background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden;">
        
        <!-- Header Card -->
        <div style="background: #F8FAFC; padding: 20px 30px; border-bottom: 1px solid #E2E8F0; display: flex; align-items: center; gap: 15px;">
            <div style="background: #0A2558; color: white; width: 50px; height: 50px; border-radius: 10px; display: flex; justify-content: center; align-items: center; font-size: 24px;">
                📄
            </div>
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #0F172A;">{{ $arsip->judul }}</h3>
                <div style="font-size: 13px; color: #64748B; margin-top: 4px; font-weight: 600;">Diunggah pada: {{ \Carbon\Carbon::parse($arsip->created_at)->format('d F Y, H:i') }}</div>
            </div>
        </div>

        <!-- Body Card (Informasi Metadata) -->
        <div style="padding: 30px; display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
            
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Surat</span>
                <span style="font-size: 15px; font-weight: 600; color: #1E293B;">{{ $arsip->nomor_surat ?? '-' }}</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Kategori Arsip</span>
                <span style="display: inline-block; background: #E0F2FE; color: #0369A1; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 700; width: fit-content;">
                    {{ $arsip->kategori }}
                </span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Dokumen</span>
                <span style="font-size: 15px; font-weight: 600; color: #1E293B;">{{ \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d/m/Y') }}</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Status File PDF</span>
                @if($arsip->file_path)
                    <span style="font-size: 14px; font-weight: 700; color: #16A34A;">✅ Tersedia di Database</span>
                @else
                    <span style="font-size: 14px; font-weight: 700; color: #DC2626;">❌ File Kosong/Hilang</span>
                @endif
            </div>

            <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 6px; margin-top: 10px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Keterangan / Ringkasan</span>
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 15px; border-radius: 8px; font-size: 14px; color: #334155; line-height: 1.6;">
                    {{ $arsip->keterangan ?? 'Tidak ada keterangan tambahan yang dicantumkan.' }}
                </div>
            </div>

        </div>

        <!-- Footer Card (Tombol Aksi) -->
        <div style="background: #F8FAFC; padding: 20px 30px; border-top: 1px solid #E2E8F0; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.arsip.edit', $arsip->id) }}" style="background: #FFFFFF; color: #F59E0B; border: 1px solid #FCD34D; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13.5px; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                ✏️ Edit Data
            </a>
            
            @if($arsip->file_path)
            <a href="{{ route('admin.arsip.download', $arsip->id) }}" style="background-color: #0A2558; color: #FFFFFF; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 13.5px; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(10, 37, 88, 0.2);">
                📥 Unduh File Dokumen
            </a>
            @else
            <button disabled style="background-color: #CBD5E1; color: #FFFFFF; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 13.5px; display: flex; align-items: center; gap: 6px; cursor: not-allowed;">
                🚫 File Tidak Tersedia
            </button>
            @endif
        </div>
    </div>
</div>
@endsection