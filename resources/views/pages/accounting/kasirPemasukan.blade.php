<x-makro.list-data>

    <x-mikro.card-custom>
        <x-slot name="title">Daftar Kas Masuk</x-slot>
        <x-slot name="toolbar">
            <a href="{{ route('kasir.pemasukan.transaksi') }}" class="btn btn-primary font-weight-bolder">New Record</a>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="20%" class="text-center">Tanggal</td>
                <td class="text-center">Account</td>
                <td class="text-center">Sub Account</td>
                <td class="none">Jumlah</td>
                <td class="text-center">Pembuat</td>
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
            function tableData()
            {
                $('#listTable').DataTable({
                    order : [],
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ route("kasir.pemasukan") }}',
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'id'},
                        {data : 'tgl_buat'},
                        {data : 'account_id'},
                        {data : ''},
                        {data : 'debet'},
                        {data : 'user_id'},
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

            jQuery(document).ready(function (){
                tableData();
            });
        </script>
    @endpush

</x-makro.list-data>
