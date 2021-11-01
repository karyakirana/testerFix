<x-makro.list-data>
    <x-mikro.card-custom>
        <x-slot name="title">Tambahan Biaya Penjualan</x-slot>
        <x-slot name="toolbar">{{ $id_jual ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <input type="text" name="id" value="{{ $id_jual ?? '' }}" hidden>
                </form>
                <x-TablePenjualanBiaya :id-penjualan="$id_jual" />
            </div>
            <div class="col-lg-4 border">
                <div class="mt-10">
                    <form class="form" id="formBiaya">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Akun Biaya</label>
                            <div class="col-lg-8">
                                <x-SelectAccountingAccount :deskripsiKategori="'biaya penjualan'" name="selectBiaya"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Akun Hutang</label>
                            <div class="col-lg-8">
                                <x-SelectAccountingAccount :deskripsiKategori="'hutang biaya penjualan'" name="selectHutang"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Nominal</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="nominal">
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="button" class="btn btn-success col-lg-3 offset-3" id="btnAddDetil">ADD</button>
                            <button type="button" class="btn btn-primary col-lg-4 offset-1" id="btnProduk">PRODUK</button>
                        </div>
                    </form>
                </div>
                <div class="text-center mb-5">
                    @if(isset($update))
                        <button class="btn btn-primary btn-lg" id="btnUpdate">SIMPAN & CETAK</button>
                    @else
                        <button class="btn btn-primary btn-lg" id="btnSave">SIMPAN & CETAK</button>
                    @endif
                </div>

            </div>
        </div>
    </x-mikro.card-custom>

    <x-nano.modal-large id="modalLedger"></x-nano.modal-large>
</x-makro.list-data>
