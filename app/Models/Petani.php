<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petani extends Model
{
    protected $table = 'petani';
    protected $fillable = ['nama', 'alamat', 'no_hp'];

    public function kebun(): HasMany
    {
        return $this->hasMany(Kebun::class);
    }
}