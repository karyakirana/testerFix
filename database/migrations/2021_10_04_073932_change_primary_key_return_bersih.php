<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePrimaryKeyReturnBersih extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_bersih', function (Blueprint $table) {
            $table->dropPrimary('id_return');
        });

        Schema::table('return_bersih', function (Blueprint $table) {
            $table->id()->first();

            // drop primary

            $table->unique('id_return');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_bersih', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropUnique('id_return');
            $table->primary('id_return');
        });
    }
}
