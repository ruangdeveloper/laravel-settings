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

### Forget a global setting

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

**Forget a setting**

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

Certainly, adding a "Contributing" section to your package's documentation is a great way to invite other developers to collaborate and make your package even better. Here's a sample "Contributing" section that you can include:

# Contributing

https://github.com/ruangdeveloper/laravel-settings/CONTRIBUTING.md

# License

https://github.com/ruangdeveloper/laravel-settings/LICENSE.md
