<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPerawatan extends Model
{
    protected $table = 'jadwal_perawatan';
    protected $fillable = ['user_id', 'kebun_id', 'judul', 'jenis', 'tanggal', 'selesai'];
    protected $casts = ['tanggal' => 'date', 'selesai' => 'boolean'];
}