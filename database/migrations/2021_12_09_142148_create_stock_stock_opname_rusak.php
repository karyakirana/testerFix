<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockStockOpnameRusak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_opname_rusak', function (Blueprint $table) {
            $table->id();
            $table->string('activeCash');
            $table->string('kode');
            $table->unsignedInteger('user');
            $table->unsignedInteger('pelapor');
            $table->unsignedInteger('branch_id');
            $table->text('tgl_input');
            $table->text('keterangan');
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
        Schema::dropIfExists('stock_opname_rusak');
    }
}
