<?php

namespace RuangDeveloper\LaravelSettings;

use Illuminate\Support\ServiceProvider;
use RuangDeveloper\LaravelSettings\Services\SettingsService;

class LaravelSettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-settings.php', 'laravel-settings');

        $this->app->bind(SettingsService::class, function ($app) {
            return new SettingsService(config('laravel-settings.model'));
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations/2023_10_24_103227_create_settings_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_settings_table.php'),
            ], 'migrations');

            $this->publishes([
                __DIR__ . '/../config/laravel-settings.php' => config_path('laravel-settings.php'),
            ], 'config');
        }
    }
}
