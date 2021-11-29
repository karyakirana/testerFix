<div>
    <x-nano.modal-large id="produkModal" :title="'Data Produk'" wire:ignore.self>
        <div class="col-md-6 row mb-6">
            <label class="col-2">Search :</label>
            <div class="col-5">
                <input type="text" class="form-control" wire:model="search">
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <x-atom.table-th>ID</x-atom.table-th>
                    <x-atom.table-th>Produk</x-atom.table-th>
                    <x-atom.table-th>Cover</x-atom.table-th>
                    <x-atom.table-th>Hal</x-atom.table-th>
                    <x-atom.table-th>Kategori</x-atom.table-th>
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
                        <x-atom.table-td :type="'center'">{{$row->kategoriHarga->nama_kat}}</x-atom.table-td>
                        <x-atom.table-td :type="'right'">{{rupiah_format($row->harga)}}</x-atom.table-td>
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
        {{ $dataProduk->links() }}
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
