# Location

A field that stores the `lat`and `lng` values as well as further information of a place you can search like on google maps.

## Setup

Prepare your model with two float colums, e.g. `lat` and `lng` and set them fillable.

```php
$table->float('lat', 12, 9);
$table->float('lng', 12, 9);
```

In order to use the location field you need an Google-API-Key with the following APIs activated:

-   Maps JavaScript
-   Places API

Add this to your `lit` config.

```php
'location' => [
    'google_api_key' => env('GOOGLE_API_KEY'),
],
```

Now the map field can be used like this:

```php
$form->map('lat', 'lng');
```

You can save further information from the Places-API like this:

```php
$form->map('lat', 'lng', [
    'formatted_address' => 'address', // saves the formatted_address attribute in the address column of your model
]);
```
