<div>
    @if(session()->has('message'))
        <x-atom.notification-all :type="danger">
            {{ session('message') }}
        </x-atom.notification-all>
    @endif
    <x-mikro.card-custom :title="'Mutasi Baik Ke Rusak'">
        <div class="row">
            <div class="col-8">
                <form class="form">
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Gudang Asal</label>
                        <div class="col-4">
                            <select id="gudangTujuan" class="form-control" wire:model.defer="gudangAsal">
                                <option selected>Dipilih</option>
                                @forelse($dataGudangAsal as $row)
                                    <option value="{{$row->id}}">{{$row->branchName}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <label class="col-2 col-form-label">Gudang Tujuan</label>
                        <div class="col-4">
                            <select class="form-control" wire:model.defer="gudangTujuan">
                                <option selected>Dipilih</option>
                                @forelse($dataGudangTujuan as $row)
                                    <option value="{{$row->id}}">{{$row->branchName}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Tanggal Mutasi</label>
                        <div class="col-4">
                            <x-nano.input-datepicker id="tglMutasi" wire:model.defer="tglMutasi"/>
                        </div>
                        <label class="col-2 col-form-label">Keterangan</label>
                        <div class="col-4">
                            <input type="text" class="form-control" wire:model.defer="keterangan">
                        </div>
                    </div>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <x-atom.table-th :width="'15%'">Kode</x-atom.table-th>
                            <x-atom.table-th :width="'45%'">Item</x-atom.table-th>
                            <x-atom.table-th :width="'20%'">Jumlah</x-atom.table-th>
                            <x-atom.table-th :width="'20%'">Actions</x-atom.table-th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mutasiBaikRusakDetail as $index => $row)
                            <tr>
                                <x-atom.table-td :type="'center'">{{$row['kodeLokal']}}</x-atom.table-td>
                                <x-atom.table-td>{{$row['item']}}</x-atom.table-td>
                                <x-atom.table-td :type="'center'">{{$row['jumlah']}}</x-atom.table-td>
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
                            <td class="text-center" colspan="4">Tidak Ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <div class="border border-1">

                    <form id="formProduk" class="mt-5 ml-3">
                        <h3 class="font-size-lg text-dark font-weight-bold mb-6">Input Produk</h3>
                        <input type="text" hidden wire:model.defer="produkId">
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
                            <label class="col-md-4 col-form-label">Jumlah</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control @error('produkJumlah') is-invalid @enderror"
                                       wire:model="produkJumlah">
                                @error('produkJumlah')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
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
                        @if($mutasiBaikRusakId)
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
                $('#tglMutasi').on('change', function (e) {
                    let date = $(this).data("#tglMutasi");
                    console.log(e.target.value);
                    @this.tglMutasi = e.target.value;
                })
            </script>
    @endpush
</div>
