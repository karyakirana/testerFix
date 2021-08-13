<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Daftar Stock Keluar</x-slot>
        <x-slot name="toolbar">
            @if(isset($branch))
                <a href="#" id="btnTableAll" class="btn btn-primary font-weight-bolder">Stock Semua</a>
            @foreach($branch as $row)
                    <a href="#" id="btnTableSelect" class="btn btn-primary font-weight-bolder" data-value="{{ $row->id }}">Stock {{ $row->branchName }}</a>
                @endforeach
            @endif
            <a href="#" class="btn btn-primary font-weight-bolder">Stock Akhir Detail</a>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="20%" class="text-center">Gudang</td>
                <td>Produk</td>
                <td class="text-center">Jumlah</td>
            </tr>
            </thead>
            <tbody></tbody>
        </x-nano.table-standart>

    </x-mikro.card-custom>

    @push('scripts')
        <script>

            // button table by gudang
            $('body').on('click', '#btnTableSelect', function (){
                let id = $(this).data('value');
                // destroy table
                $('#listTable').DataTable().destroy();
                listTable(id);
            })

            // button table all
            $('#btnTableAll').on('click', function(){
                // destroy table
                $('#listTable').DataTable().destroy();
                // reload table
                listTable();
            });

            function listTable(id = null)
            {
                $('#listTable').DataTable({
                    order : [],
                    ordering : false,
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/stock/akhir/detil/'+branch,
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

        </script>
    @endpush

</x-makro.list-data>
