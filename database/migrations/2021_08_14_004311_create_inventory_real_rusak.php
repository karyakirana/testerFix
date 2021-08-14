<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryRealRusak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_real_rusak', function (Blueprint $table) {
            $table->string('idProduk');
            $table->bigInteger('branchId');
            $table->bigInteger('stockIn')->default('0');
            $table->bigInteger('stockOut')->default('0');
            $table->bigInteger('stockNow')->default('0');
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
        Schema::dropIfExists('inventory_real_rusak');
    }
}
