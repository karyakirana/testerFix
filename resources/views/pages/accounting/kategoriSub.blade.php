<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Kategori Sub Akun</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" onclick="addData()">New Record</button>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="20%" class="text-center">Kategori</td>
                <td class="text-center">Deskripsi</td>
                <td class="none">Keterangan</td>
                <td width="10%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </x-nano.table-standart>

        <x-nano.modal-standart id="modalForm">
            <x-slot name="title">Kategori Sub Akun Form</x-slot>

            <form action="#" id="formModal">
                <input type="text" name="id" hidden>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kode</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="kode">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kategori</label>
                    <div class="col-9">
                        <select name="kategori" class="form-control select2" id="kategori" data-width="100%"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Nama Sub Kategori</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="namaSubKategori">
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

    </x-mikro.card-custom>

    @push('scripts')

        <script>
            jQuery(document).ready(function() {
                listData();
                select2Kategori();
            });

            function select2Kategori()
            {
                $('#kategori').select2({
                    placeholder : "Silahkan Pilih",
                    width : "resolve",
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        allowClear : true,
                        url : "{{ route('accountingKategori') }}",
                        type : "PUT",
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
                                        text: item.kode_kategori+' | '+item.deskripsi,
                                        id: item.id,
                                    }
                                })
                            }
                        },
                        cache : true,
                    }
                });
            }

            // add data
            $('#btnNew').click(function () {
                // reset form
                $('#formModal').trigger('reset');
                let newOption = new Option('', '', false, true);
                $('#kategori').append(newOption).trigger('change');
                // reset validate
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                // show modal
                $('#modalForm').modal('show');
            });

            // edit data
            $('body').on('click', '#btnEdit', function(){
                let dataEdit = $(this).data('value');
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/accounting/master/kategorisub/'+dataEdit,
                    method : "GET",
                    dataType : "JSON",
                    success : function (data){
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');
                        $('#formModal').trigger('reset'); // reset form on modals
                        $('[name="id"]').val(data.id);
                        $('[name="kode"]').val(data.kode_kategori_sub);
                        $('[name="namaSubKategori"]').val(data.deskripsi);
                        $('[name="keterangan"]').val(data.keterangan);
                        let dataSelect = {
                            text: data.id+' | '+data.deskripsi,
                            id: data.id,
                        }
                        let newOption = new Option(dataSelect.text, dataSelect.id, false, true);
                        $('#kategori').append(newOption).trigger('change');
                        $('#modalForm').modal('show');
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                })
            });

            // store data
            $('#btnSave').click(function () {
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ route("accountingSubKategori") }}',
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
                })
            });

            // delete data
            $('body').on('click', '#btnSoft', function () {
                let dataDelete = $(this).data('value');
                $.ajax({

                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/accounting/master/kategorisub/'+dataDelete,
                    method: "DELETE",
                    dataType : "JSON",
                    success : function (data){
                        if (data.status){
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
                })
            });

            // datatables
            function listData ()
            {
                $('#listTable').DataTable({
                    order : [],
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ route("accountingSubKategori") }}',
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'kode_kategori_sub', className: 'text-center'},
                        {data : 'deskripsi'},
                        {data : 'kategori.deskripsi'},
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

            // reload table
            function reloadTable()
            {
                $('#listTable').DataTable().ajax.reload();
            }
        </script>

    @endpush

</x-makro.list-data>
