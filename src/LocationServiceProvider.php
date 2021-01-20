<?php

namespace Litstack\Location;

use Ignite\Support\Facades\Form;
use Ignite\Support\Facades\Lit;
use Ignite\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LocationServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        Form::field('map', MapField::class);
        Lit::script(__DIR__.'/../dist/location.js');

        if ($key = config('lit.location.google_api_key')) {
            Lit::script("https://maps.googleapis.com/maps/api/js?key={$key}&libraries=places");
        }
    }

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::post('maps/places', [PlacesController::class, 'search']);
    }
}
