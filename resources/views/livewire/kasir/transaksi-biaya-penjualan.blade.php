<div>
    <x-mikro.card-custom>
        <x-slot name="title">Tambahan Biaya Penjualan</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" data-toggle="modal" data-target="#formModal">{{ $idPenjualan }}</button>
        </x-slot>

        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-5 col-form-label">Nomor</label>
                            <div class="col-7">
                                <div class="form-control-plaintext">{{$dataPenjualan->id_jual}}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 col-form-label">Customer</label>
                            <div class="col-7">
                                <div class="form-control-plaintext">{{$dataPenjualan->customer->nama_cust}}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 col-form-label">Jenis</label>
                            <div class="col-7">
                                <div class="form-control-plaintext">{{$dataPenjualan->status_bayar}}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 col-form-label">Keterangan</label>
                            <div class="col-7">
                                <div class="form-control-plaintext">{{$dataPenjualan->keterangan}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-5 col-form-label">Tgl Nota</label>
                            <div class="col-7">
                                <div class="form-control-plaintext">{{tanggalan_format($dataPenjualan->tgl_nota)}}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 col-form-label">Tgl Tempo</label>
                            <div class="col-7">
                                <div class="form-control-plaintext">{{tanggalan_format($dataPenjualan->tgl_tempo)}}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 col-form-label">Status</label>
                            <div class="col-7">
                                <div class="form-control-plaintext">{{$dataPenjualan->sudahBayar}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Kuantiti</th>
                            <th class="text-center">Diskon</th>
                            <th class="text-center">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPenjualan->detilPenjualan as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $row->produk->nama_produk }}</td>
                                <td>{{ rupiah_format($row->harga) }}</td>
                                <td class="text-center">{{ $row->jumlah }}</td>
                                <td class="text-center">{{ diskon_format($row->diskon, 2) }}%</td>
                                <td class="text-right">{{ rupiah_format($row->sub_total) }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td>Total</td>
                            <td class="text-right">{{ rupiah_format($dataPenjualan->detilPenjualan->sum('sub_total')) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>PPN</td>
                            <td class="text-right">{{ rupiah_format($dataPenjualan->ppn) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>Biaya Lain</td>
                            <td class="text-right">{{ rupiah_format($dataPenjualan->biaya_lain) }}</td>
                        </tr>
                        @forelse($itemBiaya as $index => $item)
                            <tr>
                                <td colspan="4" class="text-right">
                                    <button type="button" wire:click="editBiaya({{$index}})" class="btn btn-sm btn-clean">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="deleteBiaya({{$index}})" class="btn btn-sm btn-clean">
                                        delete
                                    </button>
                                </td>
                                <td>{{ $item['namaTagihan'] }}</td>
                                <td class="text-right">{{ rupiah_format($item['nominal']) }}</td>
                            </tr>
                        @empty
                        @endforelse
                        <tr>
                            <td colspan="4"></td>
                            <td>Total Bayar</td>
                            <td class="text-right">
                                @if($totalBayar > 0)
                                {{ rupiah_format($totalBayar) }}
                                @else
                                {{ rupiah_format($dataBayar) }}
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-4">
                <div>
                    <form>
                        <h3 class="font-size-lg mb-8 text-center"> Input Biaya</h3>
                        <input type="text" hidden wire:model.defer="indexBiaya">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Tagihan</label>
                            <div class="col-8">
                                <select name="tagihan" id="tagihan" class="form-control"
                                        wire:model.defer="tagihan">
                                    <option>Select Account</option>
                                    @forelse($selectBiaya as $biaya)
                                        <option value="{{$biaya->id}}">{{ $biaya->account_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <span></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Nominal</label>
                            <div class="col-8">
                                <input type="text" class="form-control" wire:model.defer="nominal">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Keterangan</label>
                            <div class="col-8">
                                <input type="text" class="form-control" wire:model.defer="keterangan">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-warning" wire:click="resetFormBiaya">Reset</button>

                            @if($update)
                                <button type="button" class="btn btn-primary" wire:click="updateBiaya">update</button>
                            @else
                                <button type="button" class="btn btn-primary" wire:click="addBiaya">Save</button>
                            @endif
                        </div>
                        <div class="form-group text-center">
                            <button type="button" class="btn btn-lg btn-primary" wire:click="simpanPenjualan">Save All</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-mikro.card-custom>
</div>
