<x-nano.table-standart id="listTable">
    <thead>
    <tr>
        <td width="10%" class="text-center">ID</td>
        <td width="10%" class="text-center">Lokal</td>
        <td width="25%" class="text-center">Produk</td>
        <td class="text-center">Harga</td>
        <td class="text-center">Kategori</td>
        <td class="text-center">Kat Harga</td>
        <td class="none text-center">Penerbit</td>
        <td class="none text-center">Cover</td>
        <td class="none text-center">Hal</td>
        <td class="none text-center">Size</td>
        <td class="none text-center">Deksripsi</td>
        <td width="10%">Action</td>
    </tr>
    </thead>
    <tbody></tbody>
    <tfoot></tfoot>
</x-nano.table-standart>

@push('scripts')
    <script>
        let produkTable = function (){

            let initTable = function(){
                $('#listTable').DataTable({
                    order : [],
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/produk/list',
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'id_produk'},
                        {data : 'kode_lokal'},
                        {data : 'nama_produk'},
                        {data : 'harga', render : $.fn.dataTable.render.number( '.', ',', 0, ''), className: "text-right"},
                        {data : 'kategori'},
                        {data : 'kategoriHarga', className: "text-center"},
                        {data : 'penerbit'},
                        {data : 'cover'},
                        {data : 'hal'},
                        {data : 'size'},
                        {data : 'deskripsi'},
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
            }
        }();

        jQuery(document).ready(function() {
            produkTable.init();
        });
    </script>
@endpush
