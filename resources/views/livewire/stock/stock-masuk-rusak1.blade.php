<div>
    <x-mikro.card-custom>

        <x-slot name="title">Transaksi Stock Masuk Rusak</x-slot>
        <x-slot name="toolbar">{{ $kode ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <input type="text" name="id" value="{{ $id ?? '' }}" hidden>
                    <input type="text" name="idSupplier" value="{{$supplier ?? ''}}" hidden autocomplete="off">
                    <input type="text" name="idTemp" id="idTemp" value="{{ $idTemp ?? '' }}" hidden>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right" for="supplier">Supplier</label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="supplier" id="supplier" wire:model="supplier" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" name="supplier" id="supplier" type="button" onclick="addSupplier()">Supplier</button>
                                </div>
                            </div>
                        </div>
                        <label for="jenisBayar" class="col-lg-2 col-form-label text-lg-right">Gudang</label>
                        <div class="col-lg-4">
                            <select name="branch" id="branch" class="form-control" autocomplete="off">
                                <option disabled {{ (isset($branch)) ? 'selected' : '' }}>Silahkan Pilih</option>
                                @php
                                    $branchId = \App\Models\Stock\BranchStock::latest()->get();
                                    $branch = $branch ?? '';
                                @endphp
                                @if($branchId->count() > 0)
                                    @foreach($branchId as $row)
                                        <option value="{{$row->id}}" {{ ($row->id == $branch) ? 'selected' : '' }}>{{$row->branchName}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Masuk</label>
                        <div class="col-lg-4">
                            <x-nano.input-datepicker name="tglMasuk" id="tglMasuk" wire:model="tgl_masuk_rusak" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Keterangan</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="keterangan" wire:model="keterangan">
                        </div>
                    </div>
                </form>
                <div class="example">
                    <div class="example-preview">
                        <form id="formTable">
                            <table class="table table-bordered" width="100%" id="tableTransaksi">
                                <thead>
                                <tr>
                                    <th class="text-center">Produk</th>
                                    <th class="text-center" width="20%">Jumlah</th>
                                    <th class="text-center" width="20%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($detailStockRusak as $index => $item)
                                    <tr>
                                        <td>{{ $item['nama_produk'] }}</td>
                                        <td class="text-center">{{ $item['jumlahStock'] }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-clean" wire:click="editItem({{$index}})">Edit</button>
                                            <button class="btn btn-clean" wire:click="deleteItem({{$index}})">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="4">Tidak Ada Data</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 example">
                <div class="example-preview">
                    <form action="#" class="form" id="idProduk">
                        <input type="text" name="idTransDetil" hidden>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">ID Produk</label>
                            <div class="col-lg-8">
                                <input type="text" name="idProduk" id="idProduk" wire:model.defer="idProduk" class="form-control @error('idProduk') is-invalid @enderror" readonly>
                                @error('idProduk') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Produk</label>
                            <div class="col-lg-8">
                                <textarea name="namaProduk" id="namaProduk" wire:model.defer="namaProduk" cols="30" rows="4" class="form-control" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Jumlah</label>
                            <div class="col-lg-8">
                                <input type="text" name="jumlah" id="jumlah" wire:model.defer="jumlah" class="form-control @error('jumlah') is-invalid @enderror">
                                @error('jumlah') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="button" class="btn btn-success col-lg-3 offset-3" wire:click="storeItem">ADD</button>
                            <button type="button" class="btn btn-primary col-lg-4 offset-1" onclick="addProduk()">PRODUK</button>
                        </div>
                    </form>
                </div>
                <div class="example-preview text-center">
                        <button class="btn btn-primary btn-lg" wire:click="storeAll">SIMPAN & CETAK</button>
                </div>
            </div>
        </div>


    </x-mikro.card-custom>

</div>
