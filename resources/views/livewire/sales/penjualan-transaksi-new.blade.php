<div>

    @if(session()->has('message'))
        <div class="alert alert-custom alert-light-primary fade show mb-5" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">{{session('message')}}</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="ki ki-close"></i></span>
                </button>
            </div>
        </div>
    @endif

    <x-mikro.card-custom :title="'Data Penjualan '.$idPenjualan">

        <div class="row">
            <div class="col-md-8">

                <form id="formUtama">
                    <input type="text" hidden wire:model.defer="idPenjualan">
                    <input type="text" hidden wire:model.defer="customerId">
                    <input type="text" hidden wire:model.defer="diskonDariCustomer">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Customer</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control @error('customerId') is-invalid @enderror" readonly wire:model.defer="customer">
                                <div class="input-group-append">
                                    <button class="btn btn-warning" type="button" onclick="addCustomer()">Get</button>
                                </div>
                                @error('customerId')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <label class="col-md-2 col-form-label">Jenis Bayar</label>
                        <div class="col-md-4">
                            <select id="jenis" class="form-control @error('jenis') is-invalid @enderror" wire:model.defer="jenis">
                                <option selected>Di Pilih</option>
                                <option value="cash">Cash</option>
                                <option value="tempo">Tempo</option>
                            </select>
                            @error('jenis')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Tgl Nota</label>
                        <div class="col-md-4">
                            <x-nano.input-datepicker :hasError="$errors->has('tglNota')" wire:model.defer="tglNota"/>
                            @error('tglNota')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                        <label class="col-md-2 col-form-label">Tgl Tempo</label>
                        <div class="col-md-4">
                            <x-nano.input-datepicker :hasError="$errors->has('tglTempo')" wire:model.defer="tglTempo"/>
                            @error('tglTempo')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Gudang</label>
                        <div class="col-md-4">
                            <select name="gudang" id="gudang" class="form-control @error('gudangId') is-invalid @enderror " wire:model="gudangId">
                                <option selected>Di Pilih</option>
                                @forelse($jenisGudang as $row)
                                    <option value="{{$row->id}}">{{$row->branchName}}</option>
                                @empty
                                    <option value="">Tidak Ada Data</option>
                                @endforelse
                            </select>
                            @error('gudangId')
                            <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                        <label class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-4">
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
                                <x-atom.table-td :type="'right'">{{rupiah_format($item['harga'])}}</x-atom.table-td>
                                <x-atom.table-td :type="'center'">{{$item['jumlah']}}</x-atom.table-td>
                                <x-atom.table-td :type="'center'">{{$item['diskon']}}</x-atom.table-td>
                                <x-atom.table-td :type="'right'">{{rupiah_format($item['subTotal'])}}</x-atom.table-td>
                                <x-atom.table-td :type="'center'">
                                    <button class="btn btn-sm btn-clean btn-text-primary btn-hover-primary btn-icon" wire:click="editItem('{{$index}}')">
                                        <i class="la la-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-clean btn-text-primary btn-hover-primary btn-icon" wire:click="deleteItem('{{$index}}')">
                                        <i class="la la-trash"></i>
                                    </button>
                                </x-atom.table-td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">Tidak Ada Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <x-atom.table-td>Total</x-atom.table-td>
                            <td colspan="2">
                                <input type="text" class="form-control text-right"
                                       wire:model="totalRupiah"
                                       readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <x-atom.table-td>Biaya Lain</x-atom.table-td>
                            <td colspan="2">
                                <input type="number" class="form-control"
                                       wire:model="biayaLain"
                                       wire:keyup="hitungTotalBayar"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <x-atom.table-td>PPN</x-atom.table-td>
                            <td colspan="2">
                                <input type="number" class="form-control"
                                       wire:model="ppn"
                                       wire:keyup="hitungTotalBayar"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <x-atom.table-td>Total Bayar</x-atom.table-td>
                            <td colspan="2">
                                <input type="text" class="form-control text-right"
                                       wire:model="totalBayarRupiah"
                                       readonly>
                            </td>
                        </tr>
                    </tfoot>
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
                                <input type="text" class="form-control @error('produkHarga') is-invalid @enderror" wire:model="produkHarga" readonly>
                                @error('produkHarga')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Diskon</label>
                            <div class="col-md-7">
                                <input type="number" class="form-control @error('produkDiskon') is-invalid @enderror"
                                       wire:keyup="hitung"
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
                                       wire:keyup="hitung"
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
                        <button class="btn btn-primary mr-7" type="button" onclick="addProduk()">Produk</button>
                        @if($update)
                            <button class="btn btn-success" type="button" wire:click="updateItem">Update Produk</button>
                        @else
                            <button class="btn btn-success" type="button" wire:click="storeItem">Add Produk</button>
                        @endif
                    </div>
                    <div class="text-center mb-5">
                        @if($idPenjualan)
                            <button class="btn btn-danger" type="button" wire:click="updateAll">UPDATE ALL</button>
                        @else
                            <button class="btn btn-danger" type="button" wire:click="storeAll">SIMPAN ALL</button>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </x-mikro.card-custom>

    @push('livewires')
        <script>
            $('#tglNota').on('change', function (e) {
                let date = $(this).data("#tglNota");
                // eval(date).set('tglLahir', $('#tglLahir').val())
                console.log(e.target.value);
                @this.tglNota = e.target.value;
            })

            $('#tglTempo').on('change', function (e) {
                let date = $(this).data("#tglTempo");
                // eval(date).set('tglLahir', $('#tglLahir').val())
                console.log(e.target.value);
                @this.tglTempo = e.target.value;
            })
        </script>
    @endpush
</div>
