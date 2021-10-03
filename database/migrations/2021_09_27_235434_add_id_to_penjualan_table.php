<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdToPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            // drop primary
            $table->dropPrimary('id_jual');
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $table->id()->first();

            // drop primary

            $table->unique('id_jual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropUnique('id_jual');
            $table->primary('id_jual');
        });
    }
}
