<?php

namespace RuangDeveloper\LaravelSettings\Traits;

trait HasSettings
{
    /**
     * Get the settings relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function settings(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(
            config('laravel-settings.model'),
            config('laravel-settings.morph_name'),
            config('laravel-settings.morph_type'),
            config('laravel-settings.morph_id'),
            config('laravel-settings.morph_owner_key')
        );
    }

    /**
     * Set a setting value.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setSetting(string $key, mixed $value): void
    {
        $this->settings()->updateOrCreate(
            [
                config('laravel-settings.key_name') => $key,
            ],
            [
                config('laravel-settings.value_name') => $value,
                config('laravel-settings.morph_type') => $this->getMorphClass(),
                config('laravel-settings.morph_id') => $this->getKey(),
            ]
        );
    }

    /**
     * Get a setting value.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        $setting = $this->settings()->where(config('laravel-settings.key_name'), $key)->first();

        if ($setting) {
            return $setting->value;
        }

        if (!is_null($default)) {
            return $default;
        }

        if (
            config('laravel-settings.model_defaults') &&
            array_key_exists($this->getMorphClass(), config('laravel-settings.model_defaults')) &&
            array_key_exists($key, config('laravel-settings.model_defaults')[$this->getMorphClass()])
        ) {
            return config('laravel-settings.model_defaults')[$this->getMorphClass()][$key];
        }

        return $default;
    }

    /**
     * Forget a setting value.
     * 
     * @param string $key
     * @return void
     */ 
    public function forgetSetting(string $key): void
    {
        $this->settings()->where(
            [
                config('laravel-settings.key_name') => $key,
                config('laravel-settings.morph_type') => $this->getMorphClass(),
                config('laravel-settings.morph_id') => $this->getKey(),
            ]
        )->delete();
    }
}
