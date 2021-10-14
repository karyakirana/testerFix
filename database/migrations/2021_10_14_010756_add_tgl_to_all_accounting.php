<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTglToAllAccounting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kas_trans', function (Blueprint $table) {
            $table->date('tgl_buat')->after('activeCash');
        });
        Schema::table('ledger', function (Blueprint $table) {
            $table->date('tgl_buat')->after('activeCash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
