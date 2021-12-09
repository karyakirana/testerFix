<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanNotaCashDetilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaan_nota_cash_detil', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerimaan_nota_cash_id');
            $table->unsignedBigInteger('penjualan_id');
            $table->bigInteger('total_bayar');
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
        Schema::dropIfExists('penerimaan_nota_cash_detil');
    }
}
