<?php

namespace RuangDeveloper\LaravelSettings\Services;

use Illuminate\Support\Facades\Cache;
use RuangDeveloper\LaravelSettings\Supports\Support;

class SettingsService
{
    /**
     * The model class name.
     * 
     * @var string
     */
    protected $model;

    /**
     * Create a new SettingsService instance.
     * 
     * @param string $model
     * @return void
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Set a setting value.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->storeSetting($key, $value);
    }

    /**
     * Get a setting value.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->findSetting($key, null, null, $default);
    }

    /**
     * Forget a setting value.
     * 
     * @param string $key
     * @return void
     */
    public function forget(string $key): void
    {
        $this->deleteSetting($key);
    }

    /**
     * Set a setting value with model.
     * 
     * @param string $key
     * @param mixed $value
     * @param string $modelType
     * @param mixed $modelId
     * @return void
     */
    public function setWithModel(string $key, mixed $value, string $modelType, mixed $modelId): void
    {
        $this->storeSetting($key, $value, $modelType, $modelId);
    }

    /**
     * Get a setting value with model.
     * 
     * @param string $key
     * @param string $modelType
     * @param mixed $modelId
     * @param mixed $default
     * @return mixed
     */
    public function getWithModel(string $key, string $modelType, mixed $modelId, mixed $default = null): mixed
    {
        return $this->findSetting($key, $modelType, $modelId, $default);
    }

    /**
     * Forget a setting value with model.
     * 
     * @param string $key
     * @param string $modelType
     * @param mixed $modelId
     * @return void
     */
    public function forgetWithModel(string $key, string $modelType, mixed $modelId): void
    {
        $this->deleteSetting($key, $modelType, $modelId);
    }

    /**
     * Store a setting value.
     * 
     * @param string $key
     * @param mixed $value
     * @param string $modelType
     * @param mixed $modelId
     * @return void
     */
    private function storeSetting(string $key, mixed $value, string $modelType = null, mixed $modelId = null): void
    {

        $this->model::updateOrCreate(
            [
                config('laravel-settings.key_name') => $key,
                config('laravel-settings.morph_type') => $modelType,
                config('laravel-settings.morph_id') => $modelId,
            ],
            [config('laravel-settings.value_name') => $value]
        );

        if (config('laravel-settings.with_cache')) {
            Cache::forget(Support::getCacheKey($key, $modelType, $modelId));
        }
    }

    /**
     * Find a setting value.
     * 
     * @param string $key
     * @param string $modelType
     * @param mixed $modelId
     * @param mixed $default
     * @return mixed
     */
    private function findSetting(string $key, string $modelType = null, mixed $modelId = null, mixed $default = null): mixed
    {
        if (config('laravel-settings.with_cache')) {
            $cacheKey = Support::getCacheKey($key, $modelType, $modelId);

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
        }

        $setting = $this->model::where([
            config('laravel-settings.key_name') => $key,
            config('laravel-settings.morph_type') => $modelType,
            config('laravel-settings.morph_id') => $modelId,
        ])->first();

        if ($setting) {
            $value = $setting->value;

            if (config('laravel-settings.with_cache')) {
                Cache::put($cacheKey, $value, config('laravel-settings.cache_lifetime'));
            }

            return $value;
        }

        if (!is_null($default)) {
            return $default;
        }

        if (
            config('laravel-settings.model_defaults') &&
            array_key_exists($modelType, config('laravel-settings.model_defaults')) &&
            array_key_exists($key, config('laravel-settings.model_defaults')[$modelType])
        ) {
            $value = config('laravel-settings.model_defaults')[$modelType][$key];
            return $value;
        }

        if (config('laravel-settings.defaults') && array_key_exists($key, config('laravel-settings.defaults'))) {
            $value = config('laravel-settings.defaults')[$key];
            return $value;
        }

        return $default;
    }

    /**
     * Delete a setting.
     * 
     * @param string $key
     * @param string $modelType
     * @param mixed $modelId
     * @return void
     */
    private function deleteSetting(string $key, string $modelType = null, mixed $modelId = null): void
    {
        $this->model::where([
            config('laravel-settings.key_name') => $key,
            config('laravel-settings.morph_type') => $modelType,
            config('laravel-settings.morph_id') => $modelId,
        ])->delete();

        if (config('laravel-settings.with_cache')) {
            Cache::forget(Support::getCacheKey($key, $modelType, $modelId));
        }
    }
}
