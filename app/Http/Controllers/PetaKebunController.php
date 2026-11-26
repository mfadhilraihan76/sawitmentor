<?php

namespace App\Http\Controllers;

use App\Models\Kebun;
use App\Models\Petani;

class PetaKebunController extends Controller
{
    public function data()
    {
        $petani = Petani::where('user_id', auth()->id())->first();
        $kebun = $petani ? Kebun::where('petani_id', $petani->id)->get() : collect();

        return response()->json($kebun->map(fn ($k) => [
            'id'     => $k->id,
            'nama'   => $k->nama_kebun,
            'lokasi' => $k->lokasi,
            'luas'   => (float) $k->luas_lahan,
            'lat'    => $k->latitude !== null ? (float) $k->latitude : null,
            'lng'    => $k->longitude !== null ? (float) $k->longitude : null,
        ])->values());
    }
}