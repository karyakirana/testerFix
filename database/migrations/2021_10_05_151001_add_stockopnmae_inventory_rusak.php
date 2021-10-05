<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockopnmaeInventoryRusak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_real_rusak', function (Blueprint $table) {

            $table->bigInteger('stockOpname')->after('branchId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_real_rusak', function (Blueprint $table) {
            $table->dropColumn('branchId');
        });
    }
}
