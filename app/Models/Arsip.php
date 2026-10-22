<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 
        'kategori', 
        'tanggal_dokumen', 
        'keterangan', 
        'file_path', 
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}