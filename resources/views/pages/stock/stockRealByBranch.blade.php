<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Daftar Stock {{ $branchName }} Real Time</x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="50%" class="text-center">Produk</td>
                <td class="text-center">Opname</td>
                <td class="text-center">Masuk</td>
                <td class="text-center">Keluar</td>
                <td class="text-center">Tersedia</td>
            </tr>
            </thead>
            <tbody></tbody>
        </x-nano.table-standart>

    </x-mikro.card-custom>

    <x-nano.modal-large id="modalDetil">
        <x-slot name="title">Detil Barang Keluar <span id="id_produk"></span></x-slot>

        <x-nano.table-standart id="detilTable" width="100%">
            <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="45%" class="text-center">ID Transaksi</th>
                <th width="5%" class="text-center">Jumlah</th>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </x-nano.table-standart>
    </x-nano.modal-large>

    @push('scripts')
        <script>

            jQuery(document).ready(function (){
                tableList();
            });

            function tableList()
            {
                $('#listTable').DataTable({
                    order : [],
                    ordering : true,
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/stock/real/branch/'+{{ $idBranch }},
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'idProduk', orderable : false},
                        {data : 'produk'},
                        {data : 'stockAkhir', className: "text-center"},
                        {data : 'stockMasuk', className: "text-center"},
                        {data : 'stockKeluar', className: "text-center"},
                        {data : 'tersedia', className: "text-center"},
                    ],
                    columnDefs: [
                        {
                            targets : [-1],
                            orderable: false
                        }
                    ],
                })
            }

            /**
             * stock keluar detil
             */
            // panggil modal isi table untuk stock keluar
            $('body').on('click', '#btnStockKeluar', function(){
                let dataShow = $(this).data("value");
                let url = '{{ url('/') }}'+'/stock/real/out/branch/'+dataShow+'/'+{{ $idBranch }};
                let column = [
                    {data : 'DT_RowIndex', orderable : false},
                    {data : 'penjualan'},
                    {data : 'jumlah', className: "text-center"},
                ];
                tableByProduk(url, column);
                $('#id_produk').text(dataShow);
                $('#modalDetil').modal('show'); // show bootstrap modal
            })

            // panggil modal isi table untuk stock opname
            $('body').on('click', '#btnStockOpname', function(){
                let dataShow = $(this).data("value");
                let url = '{{ url('/') }}'+'/stock/real/opname/branch/'+dataShow+'/'+{{ $idBranch }};
                let column = [
                    {data : 'DT_RowIndex', orderable : false},
                    {data : 'kode'},
                    {data : 'jumlah_stock', className: "text-center"},
                ];
                tableByProduk(url, column);
                $('#id_produk').text(dataShow);
                $('#modalDetil').modal('show'); // show bootstrap modal
            })

            // panggil modal isi table untuk stock masuk
            $('body').on('click', '#btnStockIn', function(){
                let dataShow = $(this).data("value");
                let url = '{{ url('/') }}'+'/stock/real/in/branch/'+dataShow+'/'+{{ $idBranch }};
                let column = [
                    {data : 'DT_RowIndex', orderable : false},
                    {data : 'kode'},
                    {data : 'jumlah', className: "text-center"},
                ];
                tableByProduk(url, column);
                $('#id_produk').text(dataShow);
                $('#modalDetil').modal('show'); // show bootstrap modal
            })

            // reset table when modal hide
            $('#modalDetil').on('hide.bs.modal', function (e) {
                $('#id_produk').empty();
                $('#detilTable').DataTable().destroy();
            })

            function tableByProduk(url, column)
            {
                $('#detilTable').DataTable({
                    order : [],
                    ordering : true,
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : url,
                        method : 'PATCH'
                    },
                    columns : column,
                    columnDefs: [
                        {
                            targets : [-1],
                            orderable: false
                        }
                    ],
                    footerCallback: function(row, data, start, end, display) {

                        var column = 2;
                        var api = this.api(), data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };

                        // Total over all pages
                        var total = api.column(column).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                        // Total over this page
                        var pageTotal = api.column(column, {page: 'current'}).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                        // Update footer
                        $(api.column(column).footer()).html(
                            KTUtil.numberString(pageTotal.toFixed(0)) + '<br/> ( ' + KTUtil.numberString(total.toFixed(0)) + ' total)',
                        );
                    },
                });
            }
        </script>
    @endpush

</x-makro.list-data>
