<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('journal_id');
            $table->string('activeCash');
            $table->unsignedBigInteger('journal_ref');
            $table->bigInteger('debet');
            $table->bigInteger('kredit');
            $table->softDeletes();
            $table->timestamps();

            //foreign key
            $table->foreignId('account_id')
                ->constrained('accounting_account')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger');
    }
}
