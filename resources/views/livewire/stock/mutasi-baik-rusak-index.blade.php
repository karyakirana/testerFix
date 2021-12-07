<div>
    <x-mikro.card-custom :title="'Daftar Mutasi Stock Baik ke Rusak'">
        <x-slot name="toolbar">
            <button class="btn btn-primary" wire:click="newOrder">New Data</button>
        </x-slot>

        <div class="col-6 row mb-8">
            <label for="search" class="col-2 col-form-label">Search : </label>
            <div class="col-5">
                <input type="text" class="form-control" wire:model="search">
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <x-atom.table-th>ID</x-atom.table-th>
                    <x-atom.table-th>Tgl Mutasi</x-atom.table-th>
                    <x-atom.table-th>Gudang Asal</x-atom.table-th>
                    <x-atom.table-th>Gudang Tujuan</x-atom.table-th>
                    <x-atom.table-th>Pembuat</x-atom.table-th>
                    <x-atom.table-th>Action</x-atom.table-th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataStockMutasi as $row)
                    <tr>
                        <x-atom.table-td>{{$row->kode}}</x-atom.table-td>
                        <x-atom.table-td>{{tanggalan_format($row->tgl_mutasi)}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->gudangAsal->branchName}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->gudangTujuan->branchName}}</x-atom.table-td>
                        <x-atom.table-td>{{ucfirst($row->user->name)}}</x-atom.table-td>
                        <x-atom.table-td>
                            <button type="button" class="btn btn-sm btn-clean btn-icon" title="edit" wire:click="editItem"><i class="la la-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-clean btn-icon" title="print" wire:click=""><i class="flaticon-technology"></i></button>
                        </x-atom.table-td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="6">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-mikro.card-custom>
</div>
