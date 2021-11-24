<div>
    <x-nano.modal-large :title="'Data Customer'" id="customerModal" wire:ignore.self>
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
                @forelse($dataCustomer as $row)
                    <tr>
                        <x-atom.table-td :type="'center'">{{$row->id_cust}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->nama_cust}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->addr_cust}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">
                            <button class="btn btn-clean" wire:click="setCustomer('{{$row->id_cust}}')">set</button>
                        </x-atom.table-td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="4">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $dataCustomer->links() }}
    </x-nano.modal-large>
    @push('livewires')
        <script>
            function addCustomer()
            {
                $('#customerModal').modal('show');
            }
            window.livewire.on('closeCustomerModal', ()=>{
                $('#customerModal').modal('hide');
            });
        </script>
    @endpush
</div>
