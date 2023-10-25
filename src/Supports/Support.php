<?php

namespace RuangDeveloper\LaravelSettings\Supports;

class Support
{
    /**
     * Get the cache key.
     * 
     * @param string $key
     * @param string|null $modelType
     * @param mixed|null $modelId
     * @return string
     */
    public static function getCacheKey(string $key, string $modelType = null, mixed $modelId = null): string
    {
        $cacheKey = config('laravel-settings.cache_prefix') . '.' . $key;

        if ($modelType) {
            $cacheKey .= '.' . $modelType;
        }

        if ($modelId) {
            $cacheKey .= '.' . $modelId;
        }

        return $cacheKey;
    }
}
