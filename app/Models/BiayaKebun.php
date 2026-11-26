<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaKebun extends Model
{
    protected $table = 'biaya_kebun';
    protected $fillable = ['user_id', 'kebun_id', 'tanggal', 'kategori', 'keterangan', 'jumlah'];
    protected $casts = ['tanggal' => 'date', 'jumlah' => 'float'];
}