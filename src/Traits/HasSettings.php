<?php

namespace RuangDeveloper\LaravelSettings\Traits;

use Illuminate\Support\Facades\Cache;
use RuangDeveloper\LaravelSettings\Enums\Type;
use RuangDeveloper\LaravelSettings\Services\SettingsService;
use RuangDeveloper\LaravelSettings\Supports\Support;

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
        $this->getSettingService()->setWithModel($key, $value, $this->getMorphClass(), $this->getKey());
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
        return $this->getSettingService()->getWithModel($key, $this->getMorphClass(), $this->getKey(), $default);
    }

    /**
     * Forget a setting value.
     * 
     * @deprecated Use deleteSetting() instead.
     * @param string $key
     * @return void
     */
    public function forgetSetting(string $key): void
    {
        $this->getSettingService()->forgetWithModel($key, $this->getMorphClass(), $this->getKey());
    }

    /**
     * Delete a setting item
     * 
     * @param string $key
     * @return void
     */
    public function deleteSetting(string $key): void
    {
        $this->getSettingService()->deleteWithModel($key, $this->getMorphClass(), $this->getKey());
    }

    /**
     * Get setting value and cast it to a specific type.
     * 
     * @param string $key
     * @param Type
     * @param mixed $default
     * @return mixed
     */
    public function getSettingAs(string $key, Type $type, mixed $default = null): mixed
    {
        return $this->getSettingService()->getAs($key, $type, $default, $this->getMorphClass(), $this->getKey());
    }

    /**
     * Get setting value and cast it to string.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSettingString(string $key, mixed $default = null): mixed
    {
        return $this->getSettingAs($key, Type::String, $default);
    }

    /**
     * Get setting value and cast it to integer.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSettingInteger(string $key, mixed $default = null): mixed
    {
        return $this->getSettingAs($key, Type::Integer, $default);
    }

    /**
     * Get setting value and cast it to float.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSettingFloat(string $key, mixed $default = null): mixed
    {
        return $this->getSettingAs($key, Type::Float, $default);
    }

    /**
     * Get setting value and cast it to boolean.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSettingBoolean(string $key, mixed $default = null): mixed
    {
        return $this->getSettingAs($key, Type::Boolean, $default);
    }

    /**
     * Get setting value and cast it to array.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSettingArray(string $key, mixed $default = null): mixed
    {
        return $this->getSettingAs($key, Type::Array, $default);
    }

    /**
     * Get setting value and cast it to object.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSettingObject(string $key, mixed $default = null): mixed
    {
        return $this->getSettingAs($key, Type::Object, $default);
    }

    private function getSettingService(): SettingsService
    {
        return app(SettingsService::class);
    }
}
