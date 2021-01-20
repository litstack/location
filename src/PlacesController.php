<?php

namespace Litstack\Location;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PlacesController
{
    public function search(Request $request)
    {
        $client = new Client();

        $response = $client->get("https://maps.googleapis.com/maps/api/place/textsearch/json?query={$request->search}&key=AIzaSyAzIwaEkXKG-UfZy7QVIdbFj6UuL1dOwao");

        return $response->getBody();
    }
}
