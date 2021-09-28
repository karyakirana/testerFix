<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalPembayaranDetilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_pembayaran_detil', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('jurnal_pembayaran_id')
                ->constrained('jurnal_pembayaran_master')
                ->onUpdate('cascade')
                ->onDelete('cascade')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurnal_pembayaran_detil');
    }
}
