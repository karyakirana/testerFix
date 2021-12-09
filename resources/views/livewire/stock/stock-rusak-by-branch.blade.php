<div>
    <x-mikro.card-custom :title="'Inventory Rusak Gudang '.ucfirst($gudang)">
        <div class="row mb-6">
            <div class="col-3">
                <input type="text" class="form-control" wire:model="search" placeholder="search">
            </div>
        </div>
        <x-slot name="toolbar">
            <label class="col-5 label-for-col">Item</label>
            <div class="col-7">
                <select class="form-control" wire:model="paginate">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="all">All</option>
                </select>
            </div>
        </x-slot>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <x-atom.table-th :width="'10%'">ID</x-atom.table-th>
                    <x-atom.table-th>Produk</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Stock In</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Stock Out</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Stock Now</x-atom.table-th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventoryRusak as $row)
                    <tr>
                        <x-atom.table-td :type="'center'">{{$row->idProduk}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->produk->nama_produk}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">
                            <a href="JavaScript:Void(0);" class="text-primary font-weight-bolder" wire:click="stockInByProduk('{{$row->idProduk}}')">{{$row->stockIn}}</a>
                        </x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{$row->stockOut}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{$row->stockIn - $row->stockOut}}</x-atom.table-td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($paginate != 'all')
            {{$inventoryRusak->links()}}
        @endif
        <div>
        </div>
    </x-mikro.card-custom>

    <x-nano.modal-large :title="'Daftar Stock Masuk'" id="modalStockMasuk">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <x-atom.table-th :width="'15%'">ID Transaksi</x-atom.table-th>
                    <x-atom.table-th :width="'15%'">Jenis Transaksi</x-atom.table-th>
                    <x-atom.table-th>User</x-atom.table-th>
                    <x-atom.table-th :width="'15%'">Jumlah</x-atom.table-th>
                </tr>
            </thead>
            <tbody>
            @isset($dataIn)
                @forelse($dataIn as $row)
                    <tr>
                        <x-atom.table-td :type="'center'">{{$row->stockMasukRusak->kode}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{$row->stockMasukRusak->jenis}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->stockMasukRusak->customer->nama_cust}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{$row->jumlah}}</x-atom.table-td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak Ada Data</td>
                    </tr>
                @endforelse
            @endisset
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center">
                        @isset($dataIn)
                            {{$dataIn->sum('jumlah')}}
                        @endisset
                    </td>
                </tr>
            </tfoot>
        </table>
    </x-nano.modal-large>

    @push('livewires')
        <script>
            livewire.on('showStockInProduk', ()=>{
                $('#modalStockMasuk').modal('show');
            })

            livewire.on('hideStockInProduk', ()=>{
                $('#modalStockMasuk').modal('hide');
            })
        </script>
    @endpush
</div>
