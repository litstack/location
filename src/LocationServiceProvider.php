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

        $this->app[\Ignite\Application\Kernel::class]->addMiddleware(LocationMiddleware::class);
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
