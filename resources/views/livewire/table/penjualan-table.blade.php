<div>
    <div class="col-6 row mb-6">
        <label for="search" class="col-2 col-form-label">Search :</label>
        <div class="col-6">
            <input type="text" class="form-control" wire:model="search">
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <x-atom.table-th :width="'15%'">ID</x-atom.table-th>
                <x-atom.table-th :width="'25%'">Customer</x-atom.table-th>
                <x-atom.table-th :width="'15%'">Tgl Nota</x-atom.table-th>
                <x-atom.table-th :width="'15%'">Tgl Tempo</x-atom.table-th>
                <x-atom.table-th :width="'20%'">Total Bayar</x-atom.table-th>
                <x-atom.table-th></x-atom.table-th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualan as $row)
                <tr>
                    <x-atom.table-td :type="'center'">{{$row->id_jual}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->customer->nama_cust}}</x-atom.table-td>
                    <x-atom.table-td :type="'center'">{{tanggalan_format($row->tgl_nota)}}</x-atom.table-td>
                    <x-atom.table-td :type="'center'">{{$row->tgl_tempo ? tanggalan_format($row->tgl_tempo) : ''}}</x-atom.table-td>
                    <x-atom.table-td :type="'right'">{{rupiah_format($row->total_bayar)}}</x-atom.table-td>
                    <x-atom.table-td>
                        <button class="btn btn-danger btn-hover-light" wire:click="setPenjualan({{$row->id}})">SET</button>
                    </x-atom.table-td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="6">Tidak Ada Data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $penjualan->links() }}
</div>
