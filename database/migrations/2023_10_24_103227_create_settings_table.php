<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id('id');
            $table->string(config('laravel-settings.key_name'));
            $table->json(config('laravel-settings.value_name'))->nullable();
            $table->nullableMorphs(config('laravel-settings.morph_name'));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
