<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Daftar Penjualan</x-slot>
        <x-slot name="toolbar">
            <a href="{{ route('salesNew') }}" class="btn btn-primary font-weight-bolder">New Record</a>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="10%" class="text-center">Customer</td>
                <td width="10%" class="text-center">Cabang</td>
                <td class="text-center">Tgl Nota</td>
                <td class="text-center">Tgl Tempo</td>
                <td class="text-center">Status</td>
                <td class="text-center">Jenis Bayar</td>
                <td class="text-center">Total Bayar</td>
                <td class="none text-center">Pembuat</td>
                <td class="none text-center">PPN</td>
                <td class="none text-center">Biaya Lain</td>
                <td class="none text-center">Keterangan</td>
                <td width="10%">Print</td>
                <td width="10%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </x-nano.table-standart>

    </x-mikro.card-custom>

    @push('scripts')
        <script>
            let tableList = document.getElementById('listTable');

            $(tableList).DataTable({
                order : [],
                responsive : true,
                ajax : {
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ route("produkcrud") }}',
                    method : 'PATCH'
                },
                columns : [
                    {data : 'id_produk'},
                    {data : 'kode_lokal'},
                    {data : 'nama_produk'},
                    {data : 'harga', render : $.fn.dataTable.render.number( '.', ',', 0, ''), className: "text-right"},
                    {data : 'kategori'},
                    {data : 'kategoriHarga', className: "text-center"},
                    {data : 'penerbit'},
                    {data : 'cover'},
                    {data : 'hal'},
                    {data : 'size'},
                    {data : 'deskripsi'},
                    {data : 'Action', responsivePriority: -1, className: "text-center"},
                ],
                columnDefs: [
                    {
                        targets : [-1],
                        orderable: false
                    }
                ],
            });
        </script>
    @endpush

</x-makro.list-data>
