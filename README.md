# Laravel 5 Persistent Settings
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mohd-isa/laravel-settings.svg)](https://github.com/mohd-isa/laravel-settings/releases)
[![Latest Version on Packagist](https://img.shields.io/packagist/dt/mohd-isa/laravel-settings.svg)](https://packagist.org/packages/mohd-isa/laravel-settings)
[![Build Status](https://travis-ci.org/mohd-isa/laravel-settings.svg?branch=master)](https://travis-ci.org/mohd-isa/laravel-settings)
[![StyleCI](https://styleci.io/repos/100166108/shield)](https://styleci.io/repos/100166108)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

Store and manage application wide settings for Laravel 5. Supports multiple and custom storage drivers.

## Installation
Install the package via composer using the following command
````
composer require mohd-isa/laravel-settings
````
Add the following service provider to config/app.php
````
Smartisan\Settings\SettingsServiceProvider::class,
````
Optionally, add the following facade to your config/app.php facades list
````
'Settings' => Smartisan\Settings\Facades\Settings::class,
````
Publish the configurations file to customize the package. This is optional for Laravel 5.5+
```
php artisan vendor:publish --provider="Smartisan\Settings\SettingsServiceProvider" --tag="config"
```
If you are going to use database driver, publish the table using the following command
```
php artisan settings:table
```

## Features
* Multiple storage drivers support
* Caching
* Support custom drivers
* PHP and Javascript Helper Functions
* Whitelist or Blacklist Settings

## Usage
### Set Method
Persist settings to the storage

````php
// single value
Settings::set('key', 'value');

// multiple values
Settings::set('key', [
    'k1' => 'v1',
    'k2' => 'v2'
]);

// dot notation
Settings::set('key.k1', 'v1');
````

### Get Method
Retrieves settings values from the storage. The result may be a single value or an array.

````php
// simple key
Settings::get('key', 'default');

// dot notation
Settings::get('key.k1', 'default');

// all settings
Settings::get();
````

### Forget Method
Removes given settings from the storage. If the key composites of multiple values, all values will be removed.

````php
Settings::forget('key');
````

### Has Method
Checks whether the given settings key exists or not.

````php
Settings::has('key');
````

### Flush Method
Removes all settings.

````php
Settings::flush();
````

### PHP Helper Function
Helper function is a handy function to set or get values from the setting storage. It accepts two parameters, key and default. Depends on the arguments number, set or get will be decided.

* Get

The result may be a simple value or an array depends on the key value.
````php
// simple key
settings('key', 'default');

// dot notation
settings('key.k1', 'default');
````

* Set

You can set multiple settings at once. If the key already exists, the value will be updated.
````php
settings([
   'k1' => 'v1',
   'k2' => [
      'k3' => 'v3',
      'k4' => 'v4'
   ]
])
````

### Javascript Helper Function
In order to use javascript helper function, you only have to use ``@settings`` custom directive in your blade files. This will allow you to access the settings through ```settings(key, default)``` javascript helper function.

If you don't want to allow access to sensetive settings values, use blacklist or whitelist configuration options in ```settings.php```

You can use dot notation to blacklist or whitelist multiple settings.

### Custom Drivers

The package allows you to implement your own custom driver without the need of modifying its underlying code.

* Step 1. Create Driver Class

Create the driver class in your app folder and make sure it implements Repository contract. The class name has the following naming convention: [Driver_Name]Driver.
````php
use Arados\Settings\Contracts\Repository;

class DropboxDriver implements Repository
{
   ...
}
````

* Step 2. Define Driver Config

If necessary, define your driver configuration in config/settings.php
````php
return [
 ...
   'drivers' => [
      'dropbox' => ..
   ]
]
````

* Step 3. Extend Settings Manager

In your service provider, you have to extend the settings manager to tell the package about the new driver
````php
public function register()
{
   ...
   $this->app['settings.manager']->extends('dropbox', function($app) {
      return new DropboxDriver(..);
   });
}
````

## Security
If you discover any security related issues, please email mohd.itcs@gmail.com instead of using the issue tracker.

## Credits
* [Mohammed Isa](https://github.com/mohd-isa)
* [All Contributors](https://github.com/mohd-isa/laravel-settings/graphs/contributors)

## License
The [MIT License (MIT)](https://github.com/mohd-isa/laravel-settings/blob/master/LICENSE.md). Please see License File for more information.
