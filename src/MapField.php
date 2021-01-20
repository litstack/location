<?php

namespace Litstack\Location;

use Closure;
use Ignite\Crud\BaseField;
use Illuminate\Support\Str;

class MapField extends BaseField
{
    /**
     * Vue component name.
     *
     * @var string
     */
    protected $component = 'lit-map';

    protected $filler;

    protected $keys = [];

    public function __construct($lat, $lng, array $keys = [])
    {
        parent::__construct("{$lat}_{$lng}");

        $this->latKey($lat);
        $this->lngKey($lng);

        $this->keys = $keys;

        $this->title('Location');
    }

    /**
     * Set field defaults.
     *
     * @return void
     */
    public function mount()
    {
        $this->zoom(16);
        $this->height('400px');
    }

    public function latKey($key)
    {
        $this->setAttribute('lat_key', $key);
    }

    public function lngKey($key)
    {
        $this->setAttribute('lng_key', $key);
    }

    public function fillModel($model, $attributeName, $attributeValue)
    {
        if (! is_array($attributeValue)) {
            return;
        }

        $geo = new GoogleGeocodeResult($attributeValue);

        $model->{$this->lat_key} = $geo->lat();
        $model->{$this->lng_key} = $geo->lng();

        foreach ($this->keys as $key => $attribute) {
            $method = Str::camel($key);

            if (! method_exists($geo, $method)) {
                continue;
            }

            $model->{$attribute} = $geo->{$method}();
        }
    }

    public function fill(Closure $closure)
    {
        $this->filler = $closure;
    }

    /**
     * Set initial map zoom.
     *
     * @param  int   $zoom
     * @return $this
     */
    public function zoom(int $zoom)
    {
        $this->setAttribute('zoom', $zoom);

        return $this;
    }

    /**
     * Set the container height.
     *
     * @param  string $height
     * @return $this
     */
    public function height($height)
    {
        $this->setAttribute('height', $height);

        return $this;
    }
}
