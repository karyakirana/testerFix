<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Daftar Mutasi Antar Gudang</x-slot>
        <x-slot name="toolbar">
            <a href="{{ route('mutasiGudangNew') }}" class="btn btn-primary font-weight-bolder">New Record</a>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="20%" class="text-center">Tgl Mutasi</td>
                <td class="text-center">Asal</td>
                <td class="text-center">Tujuan</td>
                <td class="text-center">Pembuat</td>
                <td class="none">Keterangan</td>
                <td width="15%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
        </x-nano.table-standart>

    </x-mikro.card-custom>

    <x-nano.modal-large id="modalDetil">
        <x-slot name="title">Detil</x-slot>

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
        <script>

            // direct to edit
            $('body').on('click', '#btnEdit', function (){
                let editData = $(this).data("value");
                window.location.href = '{{ url('/') }}'+'/stock/mutasi/edit/'+editData;
            })

            let listTable = function (){

                let initTable = function (){

                    $('#listTable').DataTable({
                        order : [],
                        ordering : false,
                        responsive : true,
                        ajax : {
                            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url : '{{route('mutasiLits')}}',
                            method : 'PATCH'
                        },
                        columns : [
                            {data : 'kode'},
                            {data : 'tgl_mutasi', className: "text-center"},
                            {data : 'branchAsal', className: "text-center"},
                            {data : 'branchTujuan', className: "text-center"},
                            {data : 'user', className: "text-center"},
                            {data : 'keterangan', className: "text-center"},
                            {data : 'Action', responsivePriority: -1, className: "text-center"},
                        ],
                        columnDefs: [
                            {
                                targets : [-1],
                                orderable: false
                            }
                        ],
                    });
                };

                return {
                    init : function (){
                        initTable();
                    }
                };
            }();

            // jquery click show data
            $('body').on('click', '#btnShow', function(){
                let dataShow = $(this).data("value");
                detilTable(dataShow);
                $('#modalDetil').modal('show'); // show bootstrap modal
            })

            // reset table when modal hide
            $('#modalDetil').on('hide.bs.modal', function (e) {

                $('#detilTable').DataTable().destroy();
            })

            // detil table by id from stock_keluar
            function detilTable(idStockKeluar)
            {
                $('#detilTable').DataTable({
                    order : [],
                    ordering : false,
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/stock/mutasi/'+idStockKeluar,
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'DT_RowIndex', orderable : false},
                        {data : 'produk'},
                        {data : 'jumlah', className: "text-center"},
                    ],
                    columnDefs: [
                        {
                            targets : [-1],
                            orderable: false
                        }
                    ],
                });
            }

            // direct to edit page

            jQuery(document).ready(function (){
                listTable.init();
            });

        </script>
    @endpush

</x-makro.list-data>

