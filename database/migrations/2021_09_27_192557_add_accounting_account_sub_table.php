<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountingAccountSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_account_sub', function (Blueprint $table) {
            $table->id();
            $table->integer('kode_account_sub')->unique();
            $table->string('sub_name');
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // foreign key
            $table->foreignId('account_id')
                ->constrained('accounting_account')
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
        Schema::dropIfExists('accounting_account_sub');
    }
}
