<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteTableAkuntansi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('akuntansi_buku_besar_aktiva');
        Schema::dropIfExists('akuntansi_buku_besar_biaya');
        Schema::dropIfExists('akuntansi_buku_besar_hutang');
        Schema::dropIfExists('akuntansi_buku_besar_modal');
        Schema::dropIfExists('akuntansi_buku_besar_pendapatan');
        Schema::dropIfExists('akuntansi_cash_flow');
        Schema::dropIfExists('akuntansi_jurnal_pembelian_table');
        Schema::dropIfExists('akuntansi_jurnal_penjualan_table');
        Schema::dropIfExists('akuntansi_jurnal_umum_table');
        Schema::dropIfExists('akuntansi_kategori');
        Schema::dropIfExists('akuntansi_sub_kategori');
        Schema::dropIfExists('akuntansi_sub_pembantu');
        Schema::dropIfExists('bukubesar_akuntansi');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
