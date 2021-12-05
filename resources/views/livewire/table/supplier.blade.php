<div>
    <x-nano.modal-large :title="'Data Supplier'" id="supplierModal" wire:ignore.self>
        <div class="col-md-6 row mb-6">
            <label for="" class="col-form-label col-2">Search : </label>
            <div class="col-5">
                <input type="text" class="form-control" wire:model="search">
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <x-atom.table-th>ID</x-atom.table-th>
                <x-atom.table-th>Nama</x-atom.table-th>
                <x-atom.table-th>Alamat</x-atom.table-th>
                <x-atom.table-th>Action</x-atom.table-th>
            </tr>
            </thead>
            <tbody>
            @forelse($dataSupplier as $row)
                <tr>
                    <x-atom.table-td :type="'center'">{{$row->kodeSupplier}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->namaSupplier}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->alamatSupplier}}</x-atom.table-td>
                    <x-atom.table-td :type="'center'">
                        <button class="btn btn-clean" wire:click="setSupplier('{{$row->id}}')">set</button>
                    </x-atom.table-td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="4">Tidak Ada Data</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $dataSupplier->links() }}
    </x-nano.modal-large>
    @push('livewires')
        <script>
            function addSupplier()
            {
                $('#supplierModal').modal('show');
            }
            window.livewire.on('closeSupplierModal', ()=>{
                $('#supplierModal').modal('hide');
            });
        </script>
    @endpush
</div>
