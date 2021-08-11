<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockAkhirMasterToStockakhir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockakhir', function (Blueprint $table) {
            $table->bigInteger('id_stock_akhir')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stockakhir', function (Blueprint $table) {
            $table->dropColumn(['id_stock-akhir']);
        });
    }
}
