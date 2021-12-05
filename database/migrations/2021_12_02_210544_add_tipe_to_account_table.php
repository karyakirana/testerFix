<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipeToAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_account', function (Blueprint $table) {
            $table->unsignedBigInteger('accounting_tipe_id')->after('kategori_sub_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_account', function (Blueprint $table) {
            $table->dropColumn('accounting-tipe-id');
        });
    }
}
