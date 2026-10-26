@extends('layouts.app')

@section('title', 'Input Massal Arsip - SIM Arsip')

@section('content')
<style>
    .form-container { background: white; border: 1px solid #e2eaf2; border-radius: 14px; padding: 30px; max-width: 950px; margin: 0 auto; box-shadow: 0 5px 20px rgba(22,49,78,.03); }
    .form-header { margin-bottom: 25px; border-bottom: 1px solid #e2eaf2; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
    .form-header h2 { color: var(--navy); font-family: 'Manrope', sans-serif; font-size: 22px; margin: 0; }
    
    table.input-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    table.input-table th { background: #e2eaf2; color: var(--navy); padding: 10px; font-size: 12px; border: 1px solid var(--line); text-align: left; }
    table.input-table td { padding: 8px; border: 1px solid var(--line); }
    .form-control { width: 100%; padding: 8px 10px; border: 1px solid var(--line); border-radius: 6px; font-size: 13px; font-family: inherit; }
    
    .btn-add-row { background: #0369a1; color: white; border: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer; }
    .btn-del-row { background: #ef4444; color: white; border: none; padding: 6px 10px; border-radius: 6px; font-size: 12px; cursor: pointer; }
    
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; border-top: 1px solid var(--line); padding-top: 20px; }
    .btn-secondary { background: #f0f4f8; color: var(--muted); border: 1px solid var(--line); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; }
    .btn-submit { background: #166534; color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; }
</style>

<div class="form-container">
    <div class="form-header">
        <h2>📝 Input Massal Data Arsip</h2>
        <button type="button" class="btn-add-row" onclick="addRow()">➕ Tambah Baris</button>
    </div>

    <form action="{{ route('admin.arsip.import.store') }}" method="POST">
        @csrf
        <table class="input-table" id="arsipTable">
            <thead>
                <tr>
                    <th style="width: 32%;">JUDUL DOKUMEN</th>
                    <th style="width: 24%;">KATEGORI</th>
                    <th style="width: 18%;">TANGGAL</th>
                    <th style="width: 21%;">KETERANGAN</th>
                    <th style="width: 5%; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td><input type="text" name="judul[]" class="form-control" placeholder="Judul dokumen..." required></td>
                    <td>
                        <select name="kategori[]" class="form-control" required>
                            <option value="Surat Masuk">Surat Masuk</option>
                            <option value="Surat Keluar">Surat Keluar</option>
                            <option value="Surat Keputusan (SK)">Surat Keputusan (SK)</option>
                            <option value="Laporan Kegiatan">Laporan Kegiatan</option>
                        </select>
                    </td>
                    <td><input type="date" name="tanggal_dokumen[]" class="form-control" required></td>
                    <td><input type="text" name="keterangan[]" class="form-control" placeholder="Keterangan..."></td>
                    <td style="text-align: center;">
                        <button type="button" class="btn-del-row" onclick="removeRow(this)">X</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="form-actions">
            <a href="{{ route('admin.arsip.index') }}" class="btn-secondary">Kembali</a>
            <button type="submit" class="btn-submit">💾 Simpan Data Arsip</button>
        </div>
    </form>
</div>

<script>
    function addRow() {
        let tbody = document.getElementById('tableBody');
        let newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" name="judul[]" class="form-control" placeholder="Judul dokumen..." required></td>
            <td>
                <select name="kategori[]" class="form-control" required>
                    <option value="Surat Masuk">Surat Masuk</option>
                    <option value="Surat Keluar">Surat Keluar</option>
                    <option value="Surat Keputusan (SK)">Surat Keputusan (SK)</option>
                    <option value="Laporan Kegiatan">Laporan Kegiatan</option>
                </select>
            </td>
            <td><input type="date" name="tanggal_dokumen[]" class="form-control" required></td>
            <td><input type="text" name="keterangan[]" class="form-control" placeholder="Keterangan..."></td>
            <td style="text-align: center;">
                <button type="button" class="btn-del-row" onclick="removeRow(this)">X</button>
            </td>
        `;
        tbody.appendChild(newRow);
    }

    function removeRow(btn) {
        let row = btn.closest('tr');
        let tbody = document.getElementById('tableBody');
        if (tbody.rows.length > 1) {
            row.remove();
        } else {
            alert('Minimal harus ada 1 baris input!');
        }
    }
</script>
@endsection