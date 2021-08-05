<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Daftar Penjualan</x-slot>
        <x-slot name="toolbar">
            <a href="{{ route('stockKeluarNew') }}" class="btn btn-primary font-weight-bolder">New Record</a>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="20%" class="text-center">Tgl Keluar</td>
                <td class="none">Asal Gudang</td>
                <td class="text-center">Jenis Keluar</td>
                <td class="text-center">Supplier</td>
                <td class="none">Customer</td>
                <td class="text-center">Nomor Penjualan</td>
                <td width="10%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
        </x-nano.table-standart>

    </x-mikro.card-custom>

</x-makro.list-data>

@push('scripts')
    <script>

        let listTable = function (){

            let initTable = function (){

                $('#listTable').DataTable({
                    order : [],
                    ordering : false,
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/stock/keluar/',
                        method : 'POST'
                    },
                    columns : [
                        {data : 'kode'},
                        {data : 'tgl_keluar', className: "text-right"},
                        {data : 'branch', className: "text-center"},
                        {data : 'jenis_keluar', className: "text-center"},
                        {data : 'supplier', className: "text-right"},
                        {data : 'customer', className: "text-right"},
                        {data : 'penjualan', className: "text-right"},
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

        function detilTable($idDetil)
        {
            $('')
        }

        // direct to edit page

        jQuery(document).ready(function (){
            listTable.init();
        });

    </script>
@endpush
