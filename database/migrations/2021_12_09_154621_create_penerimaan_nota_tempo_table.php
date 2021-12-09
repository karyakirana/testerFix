<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanNotaTempoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaan_nota_tempo', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('debet_account_id');
            $table->unsignedInteger('kredit_account_id');
            $table->bigInteger('nominal_penerimaan');
            $table->string('activeCash');
            $table->string('kode_penerimaan_tempo')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('customer_id');
            $table->date('tgl_penerimaan_tempo');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('penerimaan_nota_tempo');
    }
}
