<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petani extends Model
{
    use HasFactory;

    protected $table = 'petani';

    protected $fillable = [
        'nama',
        'no_hp',
        'alamat',
    ];

    public function kebun(): HasMany
    {
        return $this->hasMany(Kebun::class, 'petani_id');
    }
}