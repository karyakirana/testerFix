<x-makro.list-data>

    <x-mikro.card-custom>
        <x-slot name="title">Daftar Produk</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" onclick="addData()">New Record</button>
        </x-slot>

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

        <x-nano.modal-standart id="modalForm">
            <x-slot name="title">Produk Form</x-slot>
            <form action="#" id="formModal">
                <input type="text" name="id" hidden>
                <div class="form-group row">
                    <label class="col-3 col-form-label">ID Produk</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="idProduk" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kategori</label>
                    <div class="col-9">
                        <select name="kategori" id="kategori" class="form-control select2" data-width="100%"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kategori Harga</label>
                    <div class="col-9">
                        <select name="kategoriHarga" id="kategoriHarga" class="form-control select2" data-width="100%"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Produk</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="nama">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kode Lokal</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="kodeLokal">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Harga</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="harga">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Penerbit</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="penerbit">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Hal</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="hal">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Cover</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="cover">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Size</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="size">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Keterangan</label>
                    <div class="col-9">
                        <textarea name="keterangan" id="keterangan" class="form-control" cols="2" rows="2"></textarea>
                    </div>
                </div>
            </form>

            <x-slot name="footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold" id="btnSave">Save changes</button>
            </x-slot>
        </x-nano.modal-standart>

        @push('scripts')
            <script>
                let btnNew = document.getElementById('btnNew');
                let btnSave = document.getElementById('btnSave');
                let tableList = document.getElementById('listTable');
                let saveMethod;

                $('body').on('click', '#btnEdit', function(){
                    let dataEdit = $(this).data("value");
                    editData(dataEdit)
                });

                $('#btnSave').on('click', function (){
                    savedata();
                });

                $(tableList).DataTable({
                    order : [],
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ route("produkcrud") }}',
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

                function reloadTable(){
                    $(tableList).DataTable().ajax.reload();
                }

                $('#kategori').select2({
                    placeholder : "Silahkan Pilih",
                    width : "resolve",
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        allowClear : true,
                        url : "{{ route('select2Kategori') }}",
                        type : "POST",
                        dataType : "json",
                        data : function (params) {
                            return {
                                q : params.term
                            }
                        },
                        processResults : function (data) {
                            return {
                                results : $.map(data, function (item){
                                    return {
                                        text: item.nama+' | '+item.id_lokal,
                                        id: item.id_kategori,
                                    }
                                })
                            }
                        },
                        cache : true,
                    }
                });

                $('#kategoriHarga').select2({
                    placeholder : "Silahkan Pilih",
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        allowClear : true,
                        url : "{{ route('select2KategoriHarga') }}",
                        type : "POST",
                        dataType : "json",
                        width : "resolve",
                        data : function (params) {
                            return {
                                q : params.term
                            }
                        },
                        processResults : function (data) {
                            return {
                                results : $.map(data, function (item){
                                    return {
                                        text: item.nama_kat,
                                        id: item.id_kat_harga,
                                    }
                                })
                            }
                        },
                        cache : true,
                    }
                });

                function addData()
                {
                    $('#formModal').trigger('reset'); // reset form on modals
                    $('.invalid-feedback').remove();
                    $('.is-invalid').removeClass('is-invalid');
                    let newOption = new Option('', '', false, true);
                    $('#kategori').append(newOption).trigger('change');
                    let newOption_2 = new Option('', '', false, true);
                    $('#kategoriHarga').append(newOption_2).trigger('change');
                    $('#modalForm').modal('show'); // show bootstrap modal
                }

                function editData(id)
                {
                    $.ajax({
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/produk/list/'+id,
                        method: "GET",
                        dataType : "JSON",
                        success : function (data){
                            $('.invalid-feedback').remove();
                            $('.is-invalid').removeClass('is-invalid');
                            $('#formModal').trigger('reset'); // reset form on modals
                            $('[name="id"]').val(data.id_produk);
                            $('[name="idProduk"]').val(data.id_produk);
                            $('[name="nama"]').val(data.nama_produk);
                            $('[name="kodeLokal"]').val(data.kode_lokal);
                            $('[name="harga"]').val(data.harga);
                            $('[name="penerbit"]').val(data.penerbit);
                            $('[name="hal"]').val(data.hal);
                            $('[name="cover"]').val(data.cover);
                            $('[name="size"]').val(data.size);
                            $('[name="keterangan"]').val(data.deskripsi);
                            let dataSelect2_1 = {
                                text: data.nama_kategori+' | '+data.id_lokal,
                                id: data.id_kategori,
                            }
                            let newOption = new Option(dataSelect2_1.text, dataSelect2_1.id, false, true);
                            $('#kategori').append(newOption).trigger('change');
                            let dataSelect2 = {
                                text: data.nama_kat,
                                id: data.id_kat_harga,
                            }
                            let newOption_2 = new Option(dataSelect2.text, dataSelect2.id, false, true);
                            $('#kategoriHarga').append(newOption_2).trigger('change');
                            $('#modalForm').modal('show');
                        },
                        error : function (jqXHR, textStatus, errorThrown)
                        {
                            swal.fire({
                                html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                            });
                        }
                    });

                }

                function savedata()
                {
                    $.ajax({
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ route("produkList") }}',
                        method : "POST",
                        dataType : "JSON",
                        data : $('#formModal').serialize(),
                        success : function (data){
                            if (data.status){
                                $('#modalForm').modal('hide');
                                reloadTable();
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown){
                            $('.invalid-feedback').remove();
                            $('.is-invalid').removeClass('is-invalid');
                            for (const property in jqXHR.responseJSON.errors) {
                                // console.log(`${property}: ${jqXHR.responseJSON.errors[property]}`);
                                $('[name="'+`${property}`+'"').addClass('is-invalid').after('<div class="invalid-feedback" style="display: block;">'+`${jqXHR.responseJSON.errors[property]}`+'</div>');
                                $("#alertText").empty();
                                $("#alertText").append("<li>"+`${jqXHR.responseJSON.errors[property]}`+"</li>");
                            }
                        }
                    });
                }
            </script>
        @endpush
    </x-mikro.card-custom>

</x-makro.list-data>
