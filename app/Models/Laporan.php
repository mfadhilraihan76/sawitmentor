<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'petani_id',
        'judul',
        'dari',
        'sampai',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'dari'   => 'date',
            'sampai' => 'date',
        ];
    }
}