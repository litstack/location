<?php

namespace Litstack\Location;

use Ignite\Crud\Form;
use Ignite\Application\Kernel;
use Ignite\Foundation\Litstack;
use Ignite\Support\Facades\Lit;
use Ignite\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class LocationServiceProvider extends ServiceProvider
{
    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->callAfterResolving('lit.form', function(Form $form) {
            $form->field('map', MapField::class);
        });
        $this->callAfterResolving('lit', function(Litstack $lit) {
            $lit->script(__DIR__.'/../dist/location.js');
        });
        $this->callAfterResolving(Kernel::class, function(Kernel $kernel) {
            $kernel->addMiddleware(LocationMiddleware::class);
        });
        $this->callAfterResolving('router', function(Router $router) {
            $router->post('maps/places', [PlacesController::class, 'search']);
        });
    }
}
