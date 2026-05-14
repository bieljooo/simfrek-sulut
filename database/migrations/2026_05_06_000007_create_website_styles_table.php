<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_styles', function (Blueprint $table) {
            $table->id();
            $table->string('map_background_image_path')->nullable();
            $table->unsignedTinyInteger('map_background_opacity')->default(18);
            $table->string('brand_logo_image_path')->nullable();
            $table->unsignedTinyInteger('brand_logo_opacity')->default(100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_styles');
    }
};