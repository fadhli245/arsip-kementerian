<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveRequest extends Model
{
    protected $fillable = ['user_id', 'judul_arsip', 'keterangan', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
