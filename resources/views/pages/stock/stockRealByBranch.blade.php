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
                        {data : 'stockOpname', className: "text-center"},
                        {data : 'stockIn', className: "text-center"},
                        {data : 'stockOut', className: "text-center"},
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
        </script>
    @endpush

</x-makro.list-data>
