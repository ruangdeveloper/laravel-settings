<?php

return [
    // Change this to true if you want to use the cache feature.
    'with_cache' => false,

    // The cache key prefix that will be used to store the settings in the cache.
    'cache_prefix' => 'laravel-settings',

    // Cache lifetime in seconds.
    'cache_lifetime' => 60 * 60 * 24 * 7, // 7 days

    // The model that will be used to retrieve and store settings.
    // You can change this if you want to use a different model.
    'model' => \RuangDeveloper\LaravelSettings\Models\Setting::class,

    // The field name that will be used to store the key in the table.
    // You can change this if you want to use a different name for the key while using custom migration.
    'key_name' => 'key',

    // The field name that will be used to store the value in the table.
    // You can change this if you want to use a different name for the value while using custom migration.
    'value_name' => 'value',

    // The morph name that will be used to build the relationship.
    // You can change this if you want to use a different name for the relationship while using custom migration.
    'morph_name' => 'model',

    // The morph type that will be used to build the relationship.
    // You can change this if you want to use a different name for the relationship while using custom migration.
    'morph_type' => 'model_type',

    // The morph id that will be used to build the relationship.
    // You can change this if you want to use a different name for the relationship while using custom migration.
    'morph_id' => 'model_id',

    // The morph owner key that will be used to build the relationship.
    // You can change this if you want to use a different name for the relationship while using custom migration.
    'morph_owner_key' => 'id',

    // The default settings that will be used if the settings are not found in the database.
    // Please note that this will always be used if you call the get method without a default value or the default value is null.
    // The key is the setting key and the value is the setting value.
    // You can add more default settings here.
    'defaults' => [
        // 'your_setting_key' => 'your_setting_value',
    ],

    // The default settings that will be used if the model specific settings are not found in the database.
    // Please note that this will always be used if you call the get method without a default value or the default value is null.
    // The key is the model morph class name alias and the value is the array of settings.
    // You can add more default settings here.
    'model_defaults' => [
        // App\Models\User::class => [
        //     'your_setting_key' => 'your_setting_value',
        // ],
    ],
];
