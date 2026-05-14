<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('spektrum_data')) {
            return;
        }

        Schema::create('spektrum_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('CLNT_ID')->nullable();
            $table->string('NO_SIMF', 50)->nullable();
            $table->string('APPL_ID', 50)->nullable();
            $table->string('AP_PRJ_IDENT', 50)->nullable();
            $table->integer('SITE_ID')->nullable();
            $table->string('CLNT_NAME')->nullable();
            $table->string('AD_NAME')->nullable();
            $table->string('AD_FIRST_NAME')->nullable();
            $table->string('STATUS_SIMF', 100)->nullable();
            $table->text('CORR_ADDR')->nullable();
            $table->string('PHONE', 50)->nullable();
            $table->string('FAX', 50)->nullable();
            $table->string('SERVICE', 100)->nullable();
            $table->string('SUBSERVICE', 100)->nullable();
            $table->string('TRANS_TYPE', 100)->nullable();
            $table->decimal('FREQ', 12, 4)->nullable();
            $table->decimal('FREQ_PAIR', 12, 4)->nullable();
            $table->integer('NUM_STATION')->nullable();
            $table->decimal('ERP_PWR_DBM', 12, 4)->nullable();
            $table->decimal('EQ_PWR', 12, 4)->nullable();
            $table->decimal('GAIN', 12, 4)->nullable();
            $table->decimal('LOSS', 12, 4)->nullable();
            $table->decimal('BWIDTH', 12, 4)->nullable();
            $table->decimal('IB', 12, 4)->nullable();
            $table->decimal('IP', 12, 4)->nullable();
            $table->decimal('HDDP', 12, 4)->nullable();
            $table->decimal('HDLP', 12, 4)->nullable();
            $table->string('ZONA', 50)->nullable();
            $table->string('STN_TYPE', 50)->nullable();
            $table->string('EQUIP_TYPE', 100)->nullable();
            $table->string('BHP', 100)->nullable();
            $table->string('EQ_MFR', 100)->nullable();
            $table->string('TX_EQP_ID', 100)->nullable();
            $table->string('CONFIG_PLZN_CODE', 50)->nullable();
            $table->string('EQ_MDL', 100)->nullable();
            $table->string('ANT_MFR', 100)->nullable();
            $table->string('ANT_MDL', 100)->nullable();
            $table->decimal('HGT_ANT', 12, 4)->nullable();
            $table->decimal('AZIM', 12, 8)->nullable();
            $table->decimal('ELEV_ANGLE', 12, 8)->nullable();
            $table->string('MASTER_PLZN_CODE', 50)->nullable();
            $table->string('EMIS_CLASS_1', 50)->nullable();
            $table->string('STN_NAME')->nullable();
            $table->text('STN_ADDR')->nullable();
            $table->string('CALLSIGN', 100)->nullable();
            $table->decimal('SID_LONG', 12, 6)->nullable();
            $table->decimal('SID_LAT', 12, 6)->nullable();
            $table->decimal('CIRCUIT_LEN', 12, 4)->nullable();
            $table->integer('LAT_DEG')->nullable();
            $table->decimal('LAT_MIN', 12, 4)->nullable();
            $table->decimal('LAT_SEC', 12, 4)->nullable();
            $table->char('LAT_DIR_IND', 1)->nullable();
            $table->integer('LONG_DEG')->nullable();
            $table->decimal('LONG_MIN', 12, 4)->nullable();
            $table->decimal('LONG_SEC', 12, 4)->nullable();
            $table->char('LONG_DIR_IND', 1)->nullable();
            $table->text('AREA_OF_SERVICE')->nullable();
            $table->integer('MAX_COV_RADIUS')->nullable();
            $table->decimal('H_ASL', 12, 4)->nullable();
            $table->integer('NUM_SETS')->nullable();
            $table->string('CURR_LIC_NUM', 100)->nullable();
            $table->date('APPL_DATE')->nullable();
            $table->date('LICENCE_DATE')->nullable();
            $table->date('VALIDITY_DATE')->nullable();
            $table->integer('UMUR_ISR')->nullable();
            $table->string('VILLAGE', 100)->nullable();
            $table->string('CITY', 100)->nullable();
            $table->string('DISTRICT', 100)->nullable();
            $table->string('PROVINCE', 100)->nullable();
            $table->integer('SV_ID')->nullable();
            $table->integer('SS_ID')->nullable();
            $table->string('TO_APPL_ID', 100)->nullable();
            $table->string('TO_CLNT_ID', 100)->nullable();
            $table->string('LINK_ID', 100)->nullable();
            $table->date('MASA_LAKU')->nullable();
            $table->date('ARCHIV_DATE')->nullable();
            $table->string('TO_SITE_ID', 100)->nullable();
            $table->string('STASIUN_LAWAN')->nullable();
            $table->string('RECEV_TYPE', 100)->nullable();
            $table->string('TO_CALLSIGN', 100)->nullable();
            $table->integer('TO_LAT_DEG')->nullable();
            $table->integer('TO_LAT_MIN')->nullable();
            $table->decimal('TO_LAT_SEC', 12, 4)->nullable();
            $table->char('TO_LAT_DIR_IND', 1)->nullable();
            $table->integer('TO_LONG_DEG')->nullable();
            $table->integer('TO_LONG_MIN')->nullable();
            $table->decimal('TO_LONG_SEC', 12, 4)->nullable();
            $table->char('TO_LONG_DIR_IND', 1)->nullable();
            $table->string('COST_CAT', 50)->nullable();
            $table->string('AP_REQUEST_TYPE', 50)->nullable();
            $table->date('TGL_QUERY')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('NO_SIMF');
            $table->index('CLNT_NAME');
            $table->index('SERVICE');
            $table->index('STATUS_SIMF');
            $table->index('CITY');
            $table->index('CALLSIGN');
            $table->index(['SID_LAT', 'SID_LONG']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spektrum_data');
    }
};
