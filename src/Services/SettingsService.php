<?php

namespace RuangDeveloper\LaravelSettings\Services;

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
        $this->model::updateOrCreate(
            [config('laravel-settings.key_name') => $key],
            [config('laravel-settings.value_name') => $value]
        );
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
        $setting = $this->model::where([
            config('laravel-settings.key_name') => $key,
            config('laravel-settings.morph_type') => null,
            config('laravel-settings.morph_id') => null,
        ])->first();

        if ($setting) {
            return $setting->value;
        }

        if (!is_null($default)) {
            return $default;
        }

        if (config('laravel-settings.defaults') && array_key_exists($key, config('laravel-settings.defaults'))) {
            return config('laravel-settings.defaults')[$key];
        }

        return $default;
    }

    /**
     * Forget a setting value.
     * 
     * @param string $key
     * @return void
     */
    public function forget(string $key): void
    {
        $this->model::where([
            config('laravel-settings.key_name') => $key,
            config('laravel-settings.morph_type') => null,
            config('laravel-settings.morph_id') => null,
        ])->delete();
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
        $this->model::updateOrCreate(
            [
                config('laravel-settings.key_name') => $key,
                config('laravel-settings.morph_type') => $modelType,
                config('laravel-settings.morph_id') => $modelId,
            ],
            [
                'value' => $value,
            ]
        );
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
        $setting = $this->model::where([
            config('laravel-settings.key_name') => $key,
            config('laravel-settings.morph_type') => $modelType,
            config('laravel-settings.morph_id') => $modelId,
        ])->first();

        if ($setting) {
            return $setting->value;
        }

        if (!is_null($default)) {
            return $default;
        }

        if (
            config('laravel-settings.model_defaults') &&
            array_key_exists($modelType, config('laravel-settings.model_defaults')) &&
            array_key_exists($key, config('laravel-settings.model_defaults')[$modelType])
        ) {
            return config('laravel-settings.model_defaults')[$modelType][$key];
        }

        return $default;
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
        $this->model::where([
            config('laravel-settings.key_name') => $key,
            config('laravel-settings.morph_type') => $modelType,
            config('laravel-settings.morph_id') => $modelId,
        ])->delete();
    }
}
