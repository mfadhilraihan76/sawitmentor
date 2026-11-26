<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kebun extends Model
{
    use HasFactory;

    protected $table = 'kebun';

    protected $fillable = [
        'petani_id',
        'nama_kebun',
        'luas_hektar',
        'luas_lahan',
        'lokasi',
    ];

    public function petani(): BelongsTo
    {
        return $this->belongsTo(Petani::class, 'petani_id');
    }

    public function panen(): HasMany
    {
        return $this->hasMany(Panen::class, 'kebun_id');
    }
}