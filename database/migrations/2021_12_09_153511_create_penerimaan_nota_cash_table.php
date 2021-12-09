<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanNotaCashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaan_nota_cash', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('debet_account_id');
            $table->unsignedInteger('kredit_account_id');
            $table->bigInteger('nominal_penerimaan');
            $table->string('activeCash');
            $table->string('kode_penerimaan_cash')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('customer_id');
            $table->date('tgl_penerimaan_cash');
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
        Schema::dropIfExists('penerimaan_nota_cash');
    }
}
