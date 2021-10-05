<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Daftar Stock Akhir</x-slot>
        <x-slot name="toolbar">
            <a href="{{ route('stockAkhirDetil') }}" class="btn btn-success font-weight-bolder m-5">Stock Akhir Detail</a>
            <a href="{{ route('stockAkhirNew') }}" class="btn btn-primary font-weight-bolder">Stock Akhir Baru</a>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="20%" class="text-center">Gudang</td>
                <td class="text-center">Tgl Input</td>
                <td class="text-center">Pencatat</td>
                <td class="text-center">Pembuat</td>
                <td class="none">Keterangan</td>
                <td width="15%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
        </x-nano.table-standart>

    </x-mikro.card-custom>

    <x-nano.modal-large id="modalDetil">
        <x-slot name="title">Detil Penjualan</x-slot>

        <x-nano.table-standart id="detilTable" width="100%">
            <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="45%" class="text-center">Produk</th>
                <th width="5%" class="text-center">Jumlah</th>
            </tr>
            </thead>
        </x-nano.table-standart>
    </x-nano.modal-large>

    @push('scripts')

    @endpush

</x-makro.list-data>
