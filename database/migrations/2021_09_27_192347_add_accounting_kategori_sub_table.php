<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountingKategoriSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_kategori_sub', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->integer('kode_kategori_sub')->unique();
            $table->string('deskripsi');
            $table->text('keterangan');
            $table->softDeletes();
            $table->timestamps();

            // foreign key
            $table->foreignId('kategori_id')
                ->constrained('accounting_kategori')
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
        Schema::dropIfExists('accounting_kategori_sub');
    }
}
