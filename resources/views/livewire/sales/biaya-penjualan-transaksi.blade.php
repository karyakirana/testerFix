<div class="row">

    <div class="col-md-8">
        <form action="#" id="formGlobal" class="form">
            <input type="text" name="id" value="{{ $id_jual ?? '' }}" hidden>
        </form>
        <table class="table border">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Qty</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->penjualanDetail as $row)
                    <tr>
                        <td>{{ $row->id_detil }}</td>
                        <td>{{ $row->produk->nama_produk }} - {{ $row->produk->cover }}</td>
                        <td>{{ $row->harga }}</td>
                        <td>{{ $row->diskon }}</td>
                        <td>{{ $row->jumlah }}</td>
                        <td>{{ $row->sub_total }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="3">PPN</td>
                    <td>{{ $penjualanMaster->ppn }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="3">PPN</td>
                    <td>{{ $penjualanMaster->ppn }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-4 border-2">
        <form class="form" id="formBiaya">
            <div class="mt-10">
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
            </div>
        </form>
        <div class="text-center mb-5">
            @if(isset($update))
                <button class="btn btn-primary btn-lg" id="btnUpdate">SIMPAN & CETAK</button>
            @else
                <button class="btn btn-primary btn-lg" id="btnSave">SIMPAN & CETAK</button>
            @endif
        </div>
    </div>

</div>
