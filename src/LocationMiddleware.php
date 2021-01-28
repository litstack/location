<?php

namespace Litstack\Location;

use Closure;
use Ignite\Support\Facades\Lit;
use Illuminate\Http\Request;

class LocationMiddleware
{
    /**
     * Register application services.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($key = config('lit.location.google_api_key')) {
            Lit::script("https://maps.googleapis.com/maps/api/js?key={$key}&libraries=places&language=".Lit::getLocale());
        }

        return $next($request);
    }
}
