<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perawatan extends Model
{
    public const JENIS = [
        'Pemupukan',
        'Penyemprotan',
        'Pemangkasan pelepah',
        'Pembersihan gulma',
        'Pembersihan piringan',
        'Lainnya',
    ];

    protected $table = 'perawatan';

    protected $fillable = [
        'petani_id',
        'kebun_id',
        'jenis',
        'tanggal',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function kebun(): BelongsTo
    {
        return $this->belongsTo(Kebun::class, 'kebun_id');
    }
}