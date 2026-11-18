<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Panen extends Model
{
    protected $table = 'panen';
    protected $fillable = ['kebun_id', 'harga_tbs_id', 'tanggal_panen', 'berat_kg', 'total_pendapatan'];
    protected $casts = ['tanggal_panen' => 'date'];

    public function kebun(): BelongsTo
    {
        return $this->belongsTo(Kebun::class);
    }

    public function hargaTbs(): BelongsTo
    {
        return $this->belongsTo(HargaTbs::class);
    }
}