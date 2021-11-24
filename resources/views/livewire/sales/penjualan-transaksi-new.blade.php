<div>
    <x-mikro.card-custom :title="'Data Penjualan'">

        <div class="row">
            <div class="col-md-8">

                <form id="formUtama">
                    <input type="text" hidden wire:model.defer="idPenjualan">
                    <input type="text" hidden wire:model.defer="idCustomer">
                    <input type="text" hidden wire:model.defer="diskonDariCustomer">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Customer</label>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control @error('customerId') is-invalid @enderror" readonly wire:model.defer="customer">
                                <div class="input-group-append">
                                    <button class="btn btn-warning" type="button" onclick="addCustomer()">Get</button>
                                </div>
                            </div>

                            @error('customerId')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                        <label class="col-md-2 col-form-label">Pembuat</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly value="{{auth()->user()->name}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Tgl Nota</label>
                        <div class="col-md-3">
                            <x-nano.input-datepicker :hasError="$errors->has('tglNota')" wire:model.defer="tglNota"/>
                            @error('tglNota')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                        <label class="col-md-2 col-form-label">Tgl Tempo</label>
                        <div class="col-md-3">
                            <x-nano.input-datepicker :hasError="$errors->has('tglTempo')" wire:model.defer="tglTempo"/>
                            @error('tglTempo')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-3">
                            <select id="jenis" class="form-control @error('jenis') is-invalid @enderror" wire:model.defer="jenis">
                                <option value="cash">Cash</option>
                                <option value="tempo">Tempo</option>
                            </select>
                            @error('jenis')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                        <label class="col-md-2 col-form-label">Gudang</label>
                        <div class="col-md-3">
                            <select name="gudang" id="gudang" class="form-control" wire:model.defer="gudangId">
                                @forelse($jenisGudang as $row)
                                    <option value="{{$row->id}}">{{$row->branchName}}</option>
                                @empty
                                    <option value="">Tidak Ada Data</option>
                                @endforelse
                            </select>
                            @error('customerId')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" wire:model.defer="keterangan">
                        </div>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <x-atom.table-th :width="'10%'">ID</x-atom.table-th>
                            <x-atom.table-th :width="'30%'">Item</x-atom.table-th>
                            <x-atom.table-th :width="'15%'">Harga</x-atom.table-th>
                            <x-atom.table-th :width="'15%'">Jumlah</x-atom.table-th>
                            <x-atom.table-th :width="'10%'">Diskon</x-atom.table-th>
                            <x-atom.table-th :width="'20%'">Sub Total</x-atom.table-th>
                            <x-atom.table-th :width="'10%'"></x-atom.table-th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailPenjualan as $index =>$item)
                            <tr>
                                <x-atom.table-td :type="'center'">{{$item['kodeLokal']}}</x-atom.table-td>
                                <x-atom.table-td>{{$item['item']}}</x-atom.table-td>
                                <x-atom.table-td :type="'right'">{{$item['harga']}}</x-atom.table-td>
                                <x-atom.table-td :type="'center'">{{$item['jumlah']}}</x-atom.table-td>
                                <x-atom.table-td :type="'center'">{{$item['diskon']}}</x-atom.table-td>
                                <x-atom.table-td :type="'right'">{{$item['subTotal']}}</x-atom.table-td>
                                <x-atom.table-td :type="'center'">
                                    {{$index}}
                                </x-atom.table-td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">Tidak Ada Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
            <div class="col-md-4">
                <div class="border border-1">
                    <form id="formProduk" class="mt-5 ml-3">
                        <h3 class="font-size-lg text-dark font-weight-bold mb-6">Input Produk</h3>
                        <input type="text" hidden wire:model.defer="produkId">
                        <input type="text" hidden wire:model.defer="produkSubTotal">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Produk</label>
                            <div class="col-md-7">
                                <textarea type="text" class="form-control @error('produkId') is-invalid @enderror" wire:model.defer="produkName" readonly></textarea>
                                @error('produkId')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Harga</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control @error('produkHarga') is-invalid @enderror" wire:model="produkHarga">
                                @error('produkHarga')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Diskon</label>
                            <div class="col-md-7">
                                <input type="number" class="form-control @error('produkDiskon') is-invalid @enderror"
                                       wire:keyup="hitungHargaDiskon"
                                       wire:model="produkDiskon">
                                @error('produkDiskon')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Harga Diskon</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" wire:model="hargaSudahDiskon" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Jumlah</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control @error('produkJumlah') is-invalid @enderror"
                                       wire:keyup="hitungSubTotal"
                                       wire:model="produkJumlah">
                                @error('produkJumlah')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Sub Total</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" wire:model.defer="hargaSubTotal" readonly>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mb-5">
                        <button class="btn btn-primary" type="button" onclick="addProduk()">Add Produk</button>
                        <button class="btn btn-success" type="button" wire:click="storeItem">Simpan Produk</button>
                    </div>
                    <div class="text-center mb-5">
                        <button class="btn btn-danger" type="button">SIMPAN ALL</button>
                    </div>
                </div>

            </div>
        </div>

    </x-mikro.card-custom>
</div>
