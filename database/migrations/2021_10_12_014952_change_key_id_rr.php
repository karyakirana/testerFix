<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeKeyIdRr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_rusak', function (Blueprint $table) {
            $table->dropPrimary('rr_rusak');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_rusak', function (Blueprint $table) {
            $table->primary('rr_rusak');
        });
    }
}
