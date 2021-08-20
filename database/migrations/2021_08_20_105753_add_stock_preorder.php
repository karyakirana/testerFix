<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockPreorder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_preorder', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->bigInteger('supplier');
            $table->date('tgl_order');
            $table->bigInteger('pembuat');
            $table->text('keterangan');
            $table->string('activeCash');
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
        Schema::dropIfExists('stock_preorder');
    }
}
