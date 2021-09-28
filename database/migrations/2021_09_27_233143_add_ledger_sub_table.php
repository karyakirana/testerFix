<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLedgerSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_sub', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('debit');
            $table->bigInteger('kredit');
            $table->softDeletes();
            $table->timestamps();

            // foreign key
            $table->foreignId('account_sub_id')
                ->constrained('accounting_account_sub')
                ->onUpdate('cascade')
                ->onDelete('cascade')->after('id');

            $table->foreignId('ledger_id')
                ->constrained('ledger')
                ->onUpdate('cascade')
                ->onDelete('cascade')->after('account_sub_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger_sub');
    }
}
