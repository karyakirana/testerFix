<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriTipeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_tipe', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_tipe_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_tipe', function (Blueprint $table) {
            $table->dropColumn('kategori_tipe_id');
        });
    }
}
