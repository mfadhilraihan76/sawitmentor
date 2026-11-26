<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Panen extends Model
{
    use HasFactory;

    protected $table = 'panen';

    protected $fillable = [
        'kebun_id',
        'harga_tbs_id',
        'berat_kg',
        'total_pendapatan',
        'tanggal_panen',
    ];

    protected function casts(): array
    {
        return [
            'berat_kg'         => 'decimal:2',
            'total_pendapatan' => 'decimal:2',
            'tanggal_panen'    => 'date',
        ];
    }

    public function hargaTbs(): BelongsTo
    {
        return $this->belongsTo(HargaTbs::class, 'harga_tbs_id');
    }

    public function kebun(): BelongsTo
    {
        return $this->belongsTo(Kebun::class, 'kebun_id');
    }
}