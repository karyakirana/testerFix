<x-nano.table-standart id="tableSupplier">
    <thead>
    <tr>
        <td width="10%" class="text-center">ID</td>
        <td class="text-center">Supplier</td>
        <td class="text-center">Jenis Supplier</td>
        <td class="text-center">Telepon</td>
        <td class="none">Alamat</td>
        <td class="none">Keterangan</td>
        <td width="10%">Action</td>
    </tr>
    </thead>
    <tbody></tbody>
    <tfoot></tfoot>
</x-nano.table-standart>

@push('scripts')
    <script>

        function tableSupplier()
        {
            $('#tableSupplier').DataTable({
                order : [],
                responsive : true,
                ajax : {
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/data/supplier/list',
                    method : 'PATCH'
                },
                columns : [
                    {data : 'DT_RowIndex', orderable : false},
                    {data : 'namaSupplier'},
                    {data : 'jenis'},
                    {data : 'tlpSupplier'},
                    {data : 'alamatSupplier'},
                    {data : 'keteranganSupplier'},
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

        jQuery(document).ready(function (){
            tableSupplier();
        });

    </script>
@endpush
