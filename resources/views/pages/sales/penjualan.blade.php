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

        <x-nano.modal-large id="modalDetil">
            <x-slot name="title">Detil Penjualan</x-slot>

            <x-nano.table-standart id="detilTable" width="100%">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="45%" class="text-center">Produk</th>
                        <th width="5%" class="text-center">Jumlah</th>
                        <th width="20%" class="text-center">Harga</th>
                        <th width="5%" class="text-center">Diskon</th>
                        <th width="20%" class="text-center">Sub Total</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-left">Total Biaya :</td>
                        <td></td>
                        <td id="total" class="text-right"></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-left">PPN :</td>
                        <td></td>
                        <td id="ppn" class="text-right"></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-left">Biaya Lain :</td>
                        <td></td>
                        <td id="biayalain" class="text-right"></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-left">Jumlah :</td>
                        <td></td>
                        <td id="totalBayar" class="text-right"></td>
                    </tr>
                </tfoot>
            </x-nano.table-standart>
        </x-nano.modal-large>

    </x-mikro.card-custom>

    @push('scripts')
        <script>

            $('body').on('click', '#btnEdit', function(){
                let editData = $(this).data("value");
                window.location.href = '{{ url('/') }}'+'/sales/edit/'+editData;
            })

            jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
                return this.flatten().reduce( function ( a, b ) {
                    if ( typeof a === 'string' ) {
                        a = a.replace(/[^\d.-]/g, '') * 1;
                    }
                    if ( typeof b === 'string' ) {
                        b = b.replace(/[^\d.-]/g, '') * 1;
                    }

                    return a + b;
                }, 0 );
            } );

            let tableList = document.getElementById('listTable');
            let detilList = document.getElementById('detilTable');
            let tokenCsrf = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

            // jquery click show data
            $('body').on('click', '#btnShow', function(){
                let dataShow = $(this).data("value");
                detil(dataShow);
                showData(dataShow);
                $('#modalDetil').modal('show'); // show bootstrap modal
            })

            $('#modalDetil').on('hide.bs.modal', function (e) {

                $(detilList).DataTable().destroy();
            })

            function showData(id)
            {
                $.ajax({
                    url : '{{ url('/') }}'+'/sales/list/'+id,
                    method: "GET",
                    dataType : "JSON",
                    success : function (data){
                        $('#ppn').html(data.ppn);
                        $('#biayalain').html(data.biaya_lain);
                        $('#totalBayar').html(data.total_bayar);
                    },
                })
            }

            // table detil
            function detil(id)
            {
                $(detilList).DataTable({
                    order : [],
                    responsive : true,
                    autoWidth: false,
                    ajax : {
                        // header : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),"id": id},
                        url : '{{url('/')}}'+'/api/data/penjualandetil/'+id,
                        method : 'POST',
                    },
                    columns : [
                        {data : 'DT_RowIndex', className: "text-center", width: '5%'},
                        {data : 'produk'},
                        {data : 'jumlah', className: "text-center"},
                        {data : 'harga', render : $.fn.dataTable.render.number( '.', ',', 0, ''), className: "text-right"},
                        {data : 'diskon', className: "text-center"},
                        {data : 'sub_total', render : $.fn.dataTable.render.number( '.', ',', 0, ''), className: "text-right"},
                    ],
                    drawCallback : function (){
                        let api = this.api();
                        let display = $.fn.dataTable.render.number( '.', ',', 0, '' ).display;
                        $('#total').html(
                            display(api.column(-1).data().sum())
                        );
                    }
                });
            }

            function reloadTable(){
                $(tableList).DataTable().ajax.reload();
            }

            $(tableList).DataTable({
                order : [],
                responsive : true,
                ajax : {
                    headers : tokenCsrf,
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
                    {data : 'total_bayar', className: "text-right"},
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
