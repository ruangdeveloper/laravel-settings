<?php

namespace RuangDeveloper\LaravelSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'value' => 'json',
    ];

    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo(
            config('laravel-settings.morph_name'),
            config('laravel-settings.morph_type'),
            config('laravel-settings.morph_id'),
            config('laravel-settings.morph_owner_key')
        );
    }
}
