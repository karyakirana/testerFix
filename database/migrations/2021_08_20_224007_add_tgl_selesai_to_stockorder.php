<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTglSelesaiToStockorder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_preorder', function (Blueprint $table) {
            $table->date('tgl_selesai')->after('tgl_order')->nullable();
            $table->string('status')->after('tgl_selesai')->nullable();
            $table->string('status_bayar')->after('status')->nullable();
            $table->text('keterangan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_preorder', function (Blueprint $table) {
            //
        });
    }
}
