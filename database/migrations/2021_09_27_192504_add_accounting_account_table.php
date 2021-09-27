<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountingAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_account', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_sub_id');
            $table->integer('kode_account')->unique();
            $table->string('account_name');
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // foreign key
            $table->foreignId('kategori_sub_id')
                ->constrained('accounting_kategori_sub')
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
        Schema::dropIfExists('accounting_account');
    }
}
