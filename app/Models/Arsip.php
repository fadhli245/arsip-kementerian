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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
