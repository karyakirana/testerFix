<div>
    <x-nano.modal-large id="produkModal" :title="'Data Produk'">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <x-atom.table-th>ID</x-atom.table-th>
                    <x-atom.table-th>Produk</x-atom.table-th>
                    <x-atom.table-th>Cover</x-atom.table-th>
                    <x-atom.table-th>Hal</x-atom.table-th>
                    <x-atom.table-th>Harga</x-atom.table-th>
                    <x-atom.table-th>Action</x-atom.table-th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataProduk as $row)
                    <tr>
                        <x-atom.table-td :type="'center'">{{$row->kode_lokal}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->nama_produk}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{$row->cover}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{$row->hal}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->harga}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">
                            <button class="btn btn-clean" wire:click="setProduk('{{$row->id_produk}}')">set</button>
                        </x-atom.table-td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data Tidak Ada</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-nano.modal-large>
    @push('livewires')
        <script>
            function addProduk()
            {
                $('#produkModal').modal('show');
            }

            window.livewire.on('closeProdukModal', ()=>{
                $('#produkModal').modal('hide');
            })
        </script>
    @endpush
</div>
