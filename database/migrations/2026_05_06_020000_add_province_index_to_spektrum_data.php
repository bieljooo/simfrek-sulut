<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('spektrum_data') || $this->hasIndex('spektrum_data', 'spektrum_data_province_index')) {
            return;
        }

        Schema::table('spektrum_data', function (Blueprint $table) {
            $table->index('PROVINCE');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('spektrum_data') || ! $this->hasIndex('spektrum_data', 'spektrum_data_province_index')) {
            return;
        }

        Schema::table('spektrum_data', function (Blueprint $table) {
            $table->dropIndex('spektrum_data_province_index');
        });
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }
};
