<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchStockMasukRusak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_masuk_rusak', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('kode');
            $table->unsignedBigInteger('mutasi_id')->nullable()->after('tgl_masuk_rusak');
            $table->string('jenis')->after('id');
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
            $table->dropColumn(['branch_id', 'mutasi_id', 'jenis']);
        });
    }
}
