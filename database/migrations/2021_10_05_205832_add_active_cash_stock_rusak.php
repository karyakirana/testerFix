<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveCashStockRusak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_masuk_rusak', function (Blueprint $table) {
            $table->string('activeCash')->after('id');
        });

        Schema::table('stock_keluar_rusak', function (Blueprint $table) {
            $table->string('activeCash')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_masuk_rusak', function (Blueprint $table) {
            $table->dropColumn('activeCash');
        });

        Schema::table('stock_keluar_rusak', function (Blueprint $table) {
            $table->dropColumn('activeCash');
        });
    }
}
