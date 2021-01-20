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
