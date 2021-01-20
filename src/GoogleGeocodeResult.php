<?php

namespace Litstack\Location;

class GoogleGeocodeResult
{
    protected $result;

    public function __construct(array $result)
    {
        $this->result = $result;
    }

    public function geometry()
    {
        return $this->result['geometry'] ?? null;
    }

    public function lat()
    {
        return $this->location()['lat'] ?? null;
    }

    public function lng()
    {
        return $this->location()['lng'] ?? null;
    }

    public function location()
    {
        return $this->geometry()['location'] ?? null;
    }

    public function formattedAddress()
    {
        return $this->result['formatted_address'] ?? null;
    }

    public function streetNumber()
    {
        return $this->findAddressComponent('street_number');
    }

    public function streetName()
    {
        return $this->findAddressComponent('route');
    }

    public function state()
    {
        return $this->findAddressComponent('sublocality');
    }

    public function city()
    {
        return $this->findAddressComponent('locality');
    }

    public function country()
    {
        return $this->findAddressComponent('country');
    }

    public function postalCode()
    {
        return $this->findAddressComponent('postal_code');
    }

    public function findAddressComponent($type)
    {
        foreach ($this->result['address_components'] ?? [] as $component) {
            if (in_array($type, $component['types'])) {
                return $component['long_name'] ?? null;
            }
        }
    }
}
