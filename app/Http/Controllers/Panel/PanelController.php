<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Kebun;
use App\Models\Petani;
use Illuminate\Http\Request;

abstract class PanelController extends Controller
{
    private ?Petani $petaniCache = null;

    /** Data petani milik akun yang sedang login. */
    protected function petani(): Petani
    {
        return $this->petaniCache ??= Petani::untukUser(auth()->user());
    }

    /** Query kebun milik petani yang login. */
    protected function kebunSaya()
    {
        return Kebun::where('petani_id', $this->petani()->id)->orderBy('nama_kebun');
    }

    /** Tolak (404) kalau data bukan milik petani yang login. */
    protected function pastikanMilik($model): void
    {
        abort_unless((int) $model->petani_id === (int) $this->petani()->id, 404);
    }

    /** Validasi dengan pesan bahasa Indonesia. */
    protected function validasi(Request $request, array $rules, array $attributes = []): array
    {
        return $request->validate($rules, [
            'required'       => ':attribute wajib diisi.',
            'string'         => ':attribute harus berupa teks.',
            'numeric'        => ':attribute harus berupa angka.',
            'date'           => ':attribute harus berupa tanggal yang valid.',
            'in'             => ':attribute tidak valid.',
            'exists'         => ':attribute tidak valid.',
            'min'            => [
                'numeric' => ':attribute minimal :min.',
                'string'  => ':attribute minimal :min karakter.',
            ],
            'max'            => [
                'numeric' => ':attribute maksimal :max.',
                'string'  => ':attribute maksimal :max karakter.',
            ],
            'after_or_equal' => ':attribute tidak boleh lebih awal dari tanggal mulai.',
        ], $attributes);
    }
}