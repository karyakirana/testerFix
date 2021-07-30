<x-nano.table-standart id="tableCustomer">
    <thead>
        <tr>
            <td width="10%" class="text-center">ID</td>
            <td class="text-center">Nama</td>
            <td class="text-center">Diskon</td>
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
        // datatable
        $('#tableCustomer').DataTable({
            order : [],
            responsive : true,
            ajax : {
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url : '{{ url('/') }}'+'/data/customer',
                method : 'POST'
            },
            columns : [
                {data : 'DT_RowIndex', orderable : false},
                {data : 'nama_cust'},
                {data : 'diskon'},
                {data : 'telp_cust'},
                {data : 'addr_cust'},
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
    </script>
@endpush
