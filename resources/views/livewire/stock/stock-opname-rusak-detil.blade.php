<div>
    @if(session()->has('message'))
        <x-atom.notification-all :type="danger">
            {{ session('message') }}
        </x-atom.notification-all>
    @endif
    <x-mikro.card-custom :title="'Transaksi Stock Opname Rusak'">
        <div class="row">
            <div class="col-8">
                <form class="form">
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Pelapor</label>
                        <div class="col-4">
                            <select wire:model.defer="pelapor" class="form-control">
                                <option selected>Dipilih</option>
                            @forelse($dataPelapor as $row)
                                    <option value="{{$row->id}}">{{$row->nama}}</option>
                                @empty
                                    <option>Tidak Ada Data</option>
                                @endforelse
                            </select>
                        </div>
                        <label class="col-2 col-form-label">Gudang</label>
                        <div class="col-4">
                            <select wire:model.defer="branch_id" class="form-control">
                                <option selected>Dipilih</option>
                            @forelse($dataGudang as $row)
                                    <option value="{{$row->id}}">{{$row->branchName}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Tanggal Input</label>
                        <div class="col-4">
                            <x-nano.input-datepicker id="tgl_input" wire:model.defer="tgl_input"/>
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
                        <x-atom.table-th :width="'45%'">Produk</x-atom.table-th>
                        <x-atom.table-th :width="'20%'">Jumlah</x-atom.table-th>
                        <x-atom.table-th :width="'20%'">Actions</x-atom.table-th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($stockOpnameRusakDetil as $index => $row)
                        <tr>
                            <x-atom.table-td>{{$row['produkName']}}</x-atom.table-td>
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
                        <input type="text" hidden wire:model.defer="produk">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Produk</label>
                            <div class="col-md-7">
                                <textarea type="text" class="form-control @error('produk') is-invalid @enderror" wire:model.defer="produkName" readonly></textarea>
                                @error('produk')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Jumlah</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control @error('jumlah') is-invalid @enderror"
                                       wire:model="jumlah">
                                @error('jumlah')
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
                        @if($stockOpnameRusakId)
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
                $('#tgl_input').on('change', function (e) {
                    let date = $(this).data("#tgl_input");
                    console.log(e.target.value);
                    @this.tgl_input = e.target.value;
                })
            </script>
        @endpush

</div>
