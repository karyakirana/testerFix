<div>
    <x-mikro.card-custom :title="'Daftar Stock Opname Rusak'">
        <x-slot name="toolbar">
            <button class="btn btn-primary" wire:click="newData">New Data</button>
        </x-slot>

        <table class="table table-bordered">
            <thead>
            <tr>
                <x-atom.table-th>ID</x-atom.table-th>
                <x-atom.table-th>Gudang</x-atom.table-th>
                <x-atom.table-th>Tanggal Input</x-atom.table-th>
                <x-atom.table-th>Keterangan</x-atom.table-th>
                <x-atom.table-th>Action</x-atom.table-th>
            </tr>
            </thead>
            <tbody>
                @forelse($dataOpname as $row)
                <tr>
                    <x-atom.table-td>{{$row->kode}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->branch->branchName}}</x-atom.table-td>
                    <x-atom.table-td>{{tanggalan_format($row->tgl_input)}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->keterangan}}</x-atom.table-td>
                    <x-atom.table-td>
                        <button type="button" class="btn btn-sm btn-clean btn-icon" title="edit" wire:click="editItem"><i class="la la-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-clean btn-icon btn-hover-danger" title="edit" wire:click="deleteItem"><i class="la la-trash"></i></button>
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
