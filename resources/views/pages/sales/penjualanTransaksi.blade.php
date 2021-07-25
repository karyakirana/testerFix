<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Transaksi Penjualan</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right" for="customer">Customer</label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="customer" id="customer" value="{{ $customer ?? '' }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="btnCustomer">Customer</button>
                                </div>
                            </div>
                        </div>
                        <label for="jenisBayar" class="col-lg-2 col-form-label text-lg-right">Jenis Bayar</label>
                        <div class="col-lg-4 col-form-label">
                            <div class="radio-inline">
                                <label class="radio radio-success">
                                    <input type="radio" name="jenisBayar" value="Tempo"><span></span>Tempo
                                </label>
                                <label class="radio radio-success">
                                    <input type="radio" name="jenisBayar" value="Tunai"><span></span>Tunai
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Nota</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="tglNota" id="tglNota">
                        </div>
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Tempo</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="tglTempo" id="tglTempo">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Keterangan</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="keterangan">
                        </div>
                    </div>
                </form>
                <div class="example">
                    <div class="example-preview">
                        <form id="formTable">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Sub Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr></tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td>
                                            <label class="col-form-label">PPN</label>
                                        </td>
                                        <td colspan="2">
                                            <input type="text" class="form-control" name="ppn" id="ppn">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 example">
                <div class="example-preview">
                    <form action="#" class="form">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">ID Produk</label>
                            <div class="col-lg-8">
                                <input type="text" name="idProduk" id="idProduk" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Produk</label>
                            <div class="col-lg-8">
                                <textarea name="produk" id="produk" cols="30" rows="4" class="form-control" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Harga</label>
                            <div class="col-lg-8">
                                <input type="text" name="harga" id="harga" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Diskon</label>
                            <div class="col-lg-8">
                                <input type="text" name="diskon" id="diskon" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right"></label>
                            <div class="col-lg-8">
                                <input type="text" name="idProduk" id="idProduk" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Jumlah</label>
                            <div class="col-lg-8">
                                <input type="text" name="jumlah" id="jumlah" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Sub Total</label>
                            <div class="col-lg-8">
                                <input type="text" name="subTotal" id="subTotal" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <button class="btn btn-success col-lg-3 offset-3">SIMPAN</button>
                            <button class="btn btn-primary col-lg-4 offset-1">PRODUK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </x-mikro.card-custom>

</x-makro.list-data>
