<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiGudang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_gudang', function (Blueprint $table) {
            $table->id();
            $table->string('activeCash');
            $table->string('kode');
            $table->bigInteger('branchAsal');
            $table->bigInteger('branchTujuan');
            $table->date('tgl_mutasi');
            $table->bigInteger('id_user');
            $table->text('keterangan')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('mutasi_gudang');
    }
}
