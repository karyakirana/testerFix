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
        <script>

            jQuery(document).ready(function (){
                listable();
            });

            function listable()
            {
                $('#listTable').DataTable({
                    order : [],
                    ordering : false,
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/stock/akhir/',
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'kode'},
                        {data : 'branch', className: "text-center"},
                        {data : 'tglInput', className: "text-center"},
                        {data : 'pencatat', className: "text-center"},
                        {data : 'user', className: "text-center"},
                        {data : 'keterangan'},
                        {data : 'Action', responsivePriority: -1, className: "text-center"},
                    ],
                    columnDefs: [
                        {
                            targets : [-1],
                            orderable: false
                        }
                    ],
                });
            }

            // reset table when modal hide
            $('#modalDetil').on('hide.bs.modal', function (e) {

                $('#detilTable').DataTable().destroy();
            })

            // jquery click show data
            $('body').on('click', '#btnShow', function(){
                let dataShow = $(this).data("value");
                detilTable(dataShow);
                $('#modalDetil').modal('show'); // show bootstrap modal
            })

            function detilTable(id)
            {
                $('#detilTable').DataTable({
                    order : [],
                    ordering : false,
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/stock/akhir/list/'+id,
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
                })
            }

        </script>
    @endpush

</x-makro.list-data>
