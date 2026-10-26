<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Arsip</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0 0 5px 0; text-transform: uppercase; }
        .header p { margin: 0; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid black; padding: 8px 12px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        
        /* Menyembunyikan elemen tertentu saat dicetak */
        @media print {
            @page { margin: 1.5cm; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>LAPORAN REKAPITULASI DOKUMEN ARSIP</h2>
        <p>Kementerian Perhubungan Laut</p>
        <p style="font-size: 12px; margin-top:5px;">Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Judul Dokumen</th>
                <th style="width: 20%;">Kategori</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($arsips as $index => $arsip)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $arsip->judul }}</td>
                <td class="text-center">{{ $arsip->kategori }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d/m/Y') }}</td>
                <td>{{ $arsip->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>