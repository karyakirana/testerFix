<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdRrId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rr_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('retur_rusak_id')->after('id_detail_rr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rr_detil', function (Blueprint $table) {
            $table->dropColumn('retur_rusak_id');
        });
    }
}
