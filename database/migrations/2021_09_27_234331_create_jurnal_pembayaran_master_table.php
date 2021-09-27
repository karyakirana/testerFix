<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalPembayaranMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_pembayaran_master', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembayaran');
            $table->unsignedBigInteger('user_id');
            $table->string('jenis_pembayaran');
            $table->date('tgl_pembayaran');
            $table->string('customer_id');
            $table->text('keterangan');
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
        Schema::dropIfExists('jurnal_pembayaran_master');
    }
}
