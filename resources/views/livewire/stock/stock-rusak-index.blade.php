<div>
    <x-mikro.card-custom>
        <x-slot name="title">Stock Rusak</x-slot>
        <div class="col-6 row mb-8">
            <label for="search" class="col-2 col-form-label">Search :</label>
            <div class="col-5">
                <input type="text" class="form-control" wire:model="search">
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="25%" class="text-center">Produk</td>
                <td width="25%" class="text-center">Cabang</td>
                <td width="10%" class="text-center">Masuk</td>
                <td width="10%" class="text-center">Keluar</td>
                <td width="10%" class="text-center">Tersedia</td>
            </tr>
            </thead>
            <tbody>
            @forelse($datainventory_real_rusak as $row)
                <tr>
                    <td class="text-center">{{ $row->idProduk }}</td>
                    <td class="text-center">{{ $row->produk->nama_produk }}</td>
                    <td class="text-center">{{ $row->branch->branchName }}</td>
                    <td class="text-center">
                        <button class="btn btn-hover-bg-light" id="modalStock">{{ $row->stockIn }}</button>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-hover-bg-light">{{ $row->stockOut }}</button>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-hover-bg-light">{{ $row->stockIn - $row->stockOut }}</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak Ada data</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $datainventory_real_rusak->links() }}

    </x-mikro.card-custom>
</div>
