<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HargaTbs extends Model
{
    protected $table = 'harga_tbs';
    protected $fillable = ['periode_mulai', 'periode_selesai', 'harga_per_kg'];
    protected $casts = ['periode_mulai' => 'date', 'periode_selesai' => 'date'];

    public function panen(): HasMany
    {
        return $this->hasMany(Panen::class);
    }
}