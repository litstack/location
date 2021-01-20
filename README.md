# Location

## Setup

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
