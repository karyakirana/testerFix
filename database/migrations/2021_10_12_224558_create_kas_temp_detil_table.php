<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasTempDetilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas_temp_detil', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kas_temp');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('debet')->default('0');
            $table->unsignedBigInteger('kredit')->default('0');
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
        Schema::dropIfExists('kas_temp_detil');
    }
}
