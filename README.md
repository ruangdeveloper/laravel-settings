# Laravel Settings

Introducing "Laravel Settings" – a powerful and flexible Laravel package designed to simplify the management of application settings, both at the global and model-specific levels. With this package, developers can effortlessly store, retrieve, and customize settings within the database, enhancing the configuration and flexibility of Laravel-based projects.

Whether you need to manage site-wide configurations, user preferences, or any other form of customizable settings, "Laravel Settings" provides an elegant solution to streamline the process. It offers a clean and intuitive API, ensuring that you can get started quickly without any steep learning curve.

## Key Features:

- Global and Model-Specific Settings: "Laravel Settings" allows you to define both global settings, which apply to your entire application, and model-specific settings, which are associated with specific Eloquent models.

- Easy Configuration: You can define settings in a configuration file, making it a breeze to set up and manage your application's settings.

- Customizable: The package offers the flexibility to create custom setting types, ensuring that you can handle various data types, validation rules, and display options.

- Eloquent Integration: "Laravel Settings" seamlessly integrates with Laravel's Eloquent ORM, enabling you to link settings to specific database records.

## Requirements

- Laravel ^10.0

## Installation:

To get started with "Laravel Settings," follow these simple installation steps:

Install the package via Composer:

```bash
composer require ruangdeveloper/laravel-settings
```

**Publish the configuration file:**

```bash
php artisan vendor:publish --provider="RuangDeveloper\LaravelSettings\LaravelSettingsServiceProvider" --tag="config"
```

Configure the settings in `config/laravel-settings.php` according to your application's requirements.

**Publish the migration file**

```bash
php artisan vendor:publish --provider="RuangDeveloper\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
```

## Usage

Let's take a quick look at how you can use "Laravel Settings" to manage a global setting for your application.

**Set a global setting**

```php
use RuangDeveloper\LaravelSettings\Facades\Settings;

// setting the global site title
Settings::set('site_title', 'Your Awesome Website');

```

**Get a global setting**

```php
use RuangDeveloper\LaravelSettings\Facades\Settings;

// retrieve the global site title
$siteTItle = Settings::get('site_title');

// you may want to add a default fallback value if the setting
// with provided key doesn't exists in the database
$siteTitle = Settings::get('site_title', 'Your Default Awesome Website');
```

**Get a setting and cast it to a specific type**

```php
use RuangDeveloper\LaravelSettings\Enums\Type;
use RuangDeveloper\LaravelSettings\Facades\Settings;

// retrieve the global site title
$siteTitle = Settings::getAs('site_title', Type::String);

// you may want to add a default fallback value if the setting
// with provided key doesn't exists in the database
$siteTitle = Settings::getAs('site_title', Type::String, 'Your Default Awesome Website');
```

Available types:

- String
- Integer
- Float
- Boolean
- Array
- Object

You can also use the following methods to get a setting and cast it to a specific type:

- `Settings::getString('key', $default)`
- `Settings::getInteger('key', $default)`
- `Settings::getFloat('key', $default)`
- `Settings::getBoolean('key', $default)`
- `Settings::getArray('key', $default)`
- `Settings::getObject('key', $default)`


### Delete a global setting

Now, if you want to delete the setting

```php
use RuangDeveloper\LaravelSettings\Facades\Settings;

Settings::delete('site_title');
```

### Forget a global setting (deprecated, use delete instead)

Now, if you want to delete the setting

```php
use RuangDeveloper\LaravelSettings\Facades\Settings;

Settings::forget('site_title');
```

## Model Specific Setting

This package allow you to link the setting to a specific model. For example, you may want to store user's preferences.

**Model Configuration**
The first step before linking the setting to a specific model, you need to configure your model to use the `HasSettings` traits.

```php
use Illuminate\Database\Eloquent\Model;
use RuangDeveloper\LaravelSettings\Traits\HasSettings;

class User extends Model
{
    use HasSettings; // use the HasSettings trait

    protected $guarded = [];
}

```

Now, you can use the setting for an user.

**Set a setting**

```php
$user = App\Models\User::find(1);
$user->setSetting('subscribe_newsletter', true);
```

**Get a setting**

```php
$user = App\Models\User::find(1);
$isSubscribed = $user->getSetting('subscribe_newsletter');

// you may want to add a default fallback value if the setting
// with provided key doesn't exists in the database
$isSubscribed = $user->getSetting('subscribe_newsletter', false);
//
```

**Get a setting and cast it to a specific type**

```php
use RuangDeveloper\LaravelSettings\Enums\Type;
use RuangDeveloper\LaravelSettings\Facades\Settings;

$user = App\Models\User::find(1);
$isSubscribed = $user->getSettingAs('subscribe_newsletter', Type::Boolean);

// you may want to add a default fallback value if the setting
// with provided key doesn't exists in the database
$isSubscribed = $user->getSettingAs('subscribe_newsletter', Type::Boolean, false);
```

Available types:

- String
- Integer
- Float
- Boolean
- Array
- Object

You can also use the following methods to get a setting and cast it to a specific type:

- `$yourModel->getSettingString('key', $default)`
- `$yourModel->getSettingInteger('key', $default)`
- `$yourModel->getSettingFloat('key', $default)`
- `$yourModel->getSettingBoolean('key', $default)`
- `$yourModel->getSettingArray('key', $default)`
- `$yourModel->getSettingObject('key', $default)`

**Delete a setting**

```php
$user = App\Models\User::find(1);
$user->deleteSetting('subscribe_newsletter');
```

**Forget a setting (deprecated, use delete instead)**

```php
$user = App\Models\User::find(1);
$user->forgetSetting('subscribe_newsletter');
```

## Default Settings

You may want add default settings value for global or model specific settings. You can add these settings value in the configuration file located at `configs/laravel-settings.php`.

> Note: if you cannot find the config file, you need to publish the configuration first.

**Default global settings**

```php
return [
    // others config

    'defaults' => [
        'site_title' => 'Your Awesome Site',
    ],
];
```

Now, you will always get the default site title setting if there is no setting stored in the database.

```php
use RuangDeveloper\LaravelSettings\Facades\Settings;


$siteTItle = Settings::get('site_title');

echo $siteTitle; // Your Awesome Site
```

**Default model specific settings**

```php
return [
    // others config

    'model_defaults' => [
        'App\Models\User' => [
            'subscribe_newsletter' => false
        ],
    ],
];
```

Now, you will always get the default subscribe newsletter setting when you try to retrieve it from an user that didn't have setting for subscribe newsletter.

```php
$user = App\Models\User::find(1);
$isSubscribed = $user->getSetting('subscribe_newsletter');

echo $isSubscribed // false
```

## Cache

As you know, this package uses a database to store setting values. Every time you retrieve a setting value, it means you are making a query to the database. This is not a problem for small-scale applications, but it can have a significant impact when used in large-scale applications. To work around this, you can enable caching to store setting values in the cache, so the query is only performed once when you first attempt to retrieve a setting value.

To enable caching, you can go to the file `config/laravel-settings.php` and change the value of `with_cache` to true. You can also set the prefix and lifetime there.

```php
<?php

return [
    // Change this to true if you want to use the cache feature.
    'with_cache' => true,

    // The cache key prefix that will be used to store the settings in the cache.
    'cache_prefix' => 'laravel-settings',

    // Cache lifetime in seconds.
    'cache_lifetime' => 60 * 60 * 24 * 7, // 7 days

    // other config
];
```

## Customization

### Using Your Own Model

While this package covers almost everything that you need to manage the settings, you may want to use your own model. To do this, you can extends the Setting model from this package.

```php
use RuangDeveloper\LaravelSettings\Models\Setting;

class CustomSetting extends Setting
{
    //
}
```

After that, you need to tell this package to use the model you just created. Please update in the `configs/laravel-settings.php`.

```php
return [
    // others config

    'model' => App\Models\CustomSetting::class,
];
```

# Contributing

https://github.com/ruangdeveloper/laravel-settings/blob/main/CONTRIBUTING.md

# License

https://github.com/ruangdeveloper/laravel-settings/blob/main/LICENSE.md
