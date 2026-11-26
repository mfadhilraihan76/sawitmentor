<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keuangan extends Model
{
    public const KATEGORI = [
        'Pupuk',
        'Pestisida & herbisida',
        'Upah tenaga kerja',
        'Transportasi',
        'Peralatan',
        'Bantuan / subsidi',
        'Lainnya',
    ];

    protected $table = 'keuangan';

    protected $fillable = [
        'petani_id',
        'kebun_id',
        'tanggal',
        'jenis',
        'kategori',
        'jumlah',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah'  => 'decimal:2',
        ];
    }

    public function kebun(): BelongsTo
    {
        return $this->belongsTo(Kebun::class, 'kebun_id');
    }
}