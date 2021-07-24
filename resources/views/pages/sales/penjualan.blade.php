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
                <td width="20%" class="text-center">Customer</td>
                <td class="none">Cabang</td>
                <td class="text-center">Tgl Nota</td>
                <td class="text-center">Tgl Tempo</td>
                <td class="none">Status</td>
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

        <x-nano.modal-standart id="modalDetil">
            <x-slot name="title">Detil Penjualan</x-slot>

            <x-nano.table-standart id="detilTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
            </x-nano.table-standart>

        </x-nano.modal-standart>

    </x-mikro.card-custom>

    @push('scripts')
        <script>
            let tableList = document.getElementById('listTable');
            let detilList = document.getElementById('detilTable');

            // table detil
            function detil(id)
            {
                $(detilList).DataTable({
                    order : [],
                    responsive : true,
                    ajax : {
                        header : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{url('/')}}'+'/data/penjualandetil/'+id,
                        method : 'PATCH',
                    },
                    columns : [
                        {data : 'DT_RowIndex'},
                        {data : 'produk'},
                        {data : 'jumlah'},
                        {data : 'harga'},
                        {data : 'diskon'},
                        {data : 'sub_total'},
                    ]
                })
            }

            $(tableList).DataTable({
                order : [],
                responsive : true,
                ajax : {
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ route("penjualanList") }}',
                    method : 'PATCH'
                },
                columns : [
                    {data : 'id_jual'},
                    {data : 'customer'},
                    {data : 'branch'},
                    {data : 'tgl_nota'},
                    {data : 'tgl_tempo'},
                    {data : 'sudahBayar'},
                    {data : 'status_bayar', className: "text-center"},
                    {data : 'total_bayar', render : $.fn.dataTable.render.number( '.', ',', 0, ''), className: "text-right"},
                    {data : 'user'},
                    {data : 'ppn'},
                    {data : 'biaya_lain'},
                    {data : 'keterangan'},
                    {data : 'print', className: "text-center"},
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
