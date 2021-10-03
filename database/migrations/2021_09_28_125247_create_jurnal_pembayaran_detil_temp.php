<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalPembayaranDetilTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jp_detil_temp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jp_temp');
            $table->unsignedBigInteger('penjualan_id');
            $table->unsignedBigInteger('total_bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jp_detil_temp');
    }
}
