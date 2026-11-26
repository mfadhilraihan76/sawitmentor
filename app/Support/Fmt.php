<?php

namespace App\Support;

use Carbon\Carbon;

class Fmt
{
    public static function rp($nilai): string
    {
        $n = (float) $nilai;

        return ($n < 0 ? '-' : '') . 'Rp' . number_format(abs($n), 0, ',', '.');
    }

    public static function kg($nilai): string
    {
        return rtrim(rtrim(number_format((float) $nilai, 2, ',', '.'), '0'), ',');
    }

    public static function tgl($tanggal, string $format = 'd/m/Y'): string
    {
        return $tanggal ? Carbon::parse($tanggal)->format($format) : '-';
    }
}