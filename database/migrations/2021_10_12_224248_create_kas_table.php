<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas_trans', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('activeCash');
            $table->string('jenis');
            $table->unsignedBigInteger('account_id'); // pemasukan di mana
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('debet');
            $table->unsignedBigInteger('kredit');
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
        Schema::dropIfExists('kas_trans');
    }
}
