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
        'user_id',
        'nama',
        'no_hp',
        'alamat',
    ];

    public function kebun(): HasMany
    {
        return $this->hasMany(Kebun::class, 'petani_id');
    }

    /**
     * Cari data petani milik akun yang login.
     * Kalau belum ada, dihubungkan ke data petani bernama sama,
     * atau dibuatkan otomatis.
     */
    public static function untukUser($user): self
    {
        $petani = static::where('user_id', $user->id)->first();

        if (! $petani) {
            $petani = static::whereNull('user_id')->where('nama', $user->name)->first();
            $petani?->update(['user_id' => $user->id]);
        }

        return $petani ?? static::create([
            'user_id' => $user->id,
            'nama'    => $user->name,
            'alamat'  => '-',
            'no_hp'   => '-',
        ]);
    }
}