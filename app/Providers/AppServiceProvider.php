<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // PETA-KEBUN: simpan latitude/longitude dari form kebun petani
        \App\Models\Kebun::saving(function ($k) {
            $r = request();
            if (! $r->routeIs('petani.kebun*')) return;
            if (! $r->has('latitude') && ! $r->has('longitude')) return;
            $lat = $r->input('latitude'); $lng = $r->input('longitude');
            if (is_numeric($lat) && is_numeric($lng) && abs((float) $lat) <= 90 && abs((float) $lng) <= 180) {
                $k->latitude = round((float) $lat, 7);
                $k->longitude = round((float) $lng, 7);
            } else {
                $k->latitude = null;
                $k->longitude = null;
            }
        });
        //
    }
}
