<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_styles', function (Blueprint $table) {
            $table->unsignedTinyInteger('map_background_size')->default(100)->after('map_background_position_y');
            $table->unsignedTinyInteger('brand_logo_size')->default(100)->after('brand_logo_position_y');
        });
    }

    public function down(): void
    {
        Schema::table('website_styles', function (Blueprint $table) {
            $table->dropColumn([
                'map_background_size',
                'brand_logo_size',
            ]);
        });
    }
};