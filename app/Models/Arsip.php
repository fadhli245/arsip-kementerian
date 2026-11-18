<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsips'; // Sesuaikan jika nama tabel kamu 'arsips'

    protected $fillable = [
        'judul',
        'nomor_surat',
        'kategori',
        'format',
        'ukuran_bytes',
        'unit_pengunggah',
        'tanggal_unggah',
        'tanggal_dokumen',
        'keterangan',
        'file_path',
        'user_id',
        'classification',
        'allowed_divisions',
        'is_active',
        'approval_status',
    ];

    protected $casts = [
        'allowed_divisions' => 'array',
        'is_active' => 'boolean',
        'tanggal_unggah' => 'datetime',
        'tanggal_dokumen' => 'date',
        'ukuran_bytes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'archive_favorites', 'arsip_id', 'user_id')->withTimestamps();
    }

    public function getFormatLabelAttribute(): string
    {
        return strtoupper($this->format ?: pathinfo((string) $this->file_path, PATHINFO_EXTENSION) ?: 'BERKAS');
    }

    public function getUkuranHumanAttribute(): string
    {
        if (!$this->ukuran_bytes) {
            return 'Ukuran tidak tersedia';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = (float) $this->ukuran_bytes;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return number_format($size, $unit === 0 ? 0 : 1, ',', '.') . ' ' . $units[$unit];
    }

}
