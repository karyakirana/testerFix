<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->bigInteger('branch');
            $table->string('jenis_keluar'); // penjualan atau rusak
            $table->bigInteger('supplier')->nullable();
            $table->string('customer')->nullable();
            $table->string('penjualan')->nullable();
            $table->string('users');
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
        Schema::dropIfExists('stock_keluar');
    }
}
