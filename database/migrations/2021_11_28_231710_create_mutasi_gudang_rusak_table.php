<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiGudangRusakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_gudang_rusak', function (Blueprint $table) {
            $table->id();
            $table->string('activeCash');
            $table->string('kode');
            $table->unsignedBigInteger('gudang_asal');
            $table->unsignedBigInteger('gudang_tujuan');
            $table->date('tgl_mutasi');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('mutasi_gudang_rusak');
    }
}
