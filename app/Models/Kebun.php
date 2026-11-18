<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kebun extends Model
{
    protected $table = 'kebun';
    protected $fillable = ['petani_id', 'nama_kebun', 'lokasi', 'luas_lahan'];

    public function petani(): BelongsTo
    {
        return $this->belongsTo(Petani::class);
    }

    public function panen(): HasMany
    {
        return $this->hasMany(Panen::class);
    }
}