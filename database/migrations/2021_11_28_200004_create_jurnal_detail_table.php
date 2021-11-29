<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jurnal_id');
            $table->string('jenis_jurnal');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('sub_account_id')->nullable();
            $table->bigInteger('debet');
            $table->bigInteger('kredit');
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
        Schema::dropIfExists('jurnal_detail');
    }
}
