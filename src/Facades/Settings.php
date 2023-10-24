<?php

namespace RuangDeveloper\LaravelSettings\Facades;

use Illuminate\Support\Facades\Facade;
use RuangDeveloper\LaravelSettings\Services\SettingsService;

/**
 * @method static void set(string $key, mixed $value)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void forget(string $key)
 * @method static void setWithModel(string $key, mixed $value, string $modelType, mixed $modelId)
 * @method static mixed getWithModel(string $key, string $modelType, mixed $modelId, mixed $default = null)
 * @method static void forgetWithModel(string $key, string $modelType, mixed $modelId)
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingsService::class;
    }
}
