<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Kategori Akun</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" onclick="addData()">New Record</button>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td class="text-center">Kategori</td>
                <td class="text-center">Deskripsi</td>
                <td width="10%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </x-nano.table-standart>

        <x-nano.modal-standart id="modalForm">
            <x-slot name="title">Kategori Akun Form</x-slot>

            <form action="#" id="formModal">
                <input type="text" name="id" hidden>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kode</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="kode">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Nama Kategori</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="namaKategori">
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
            // add data
            $('#btnNew').click(function () {
                // reset form
                $('#formModal').trigger('reset');
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
                    url : '{{ url('/') }}'+'/accounting/master/kategori/'+dataEdit,
                    method : "get",
                    dataType : "JSON",
                    success : function (data){
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');
                        $('#formModal').trigger('reset'); // reset form on modals
                        $('[name="id"]').val(data.id);
                        $('[name="kode"]').val(data.kode_kategori);
                        $('[name="namaKategori"]').val(data.deskripsi);
                        $('[name="keterangan"]').val(data.keterangan);
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
                    url : '{{ route("accountingKategori") }}',
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
                    url : '{{ url('/') }}'+'/accounting/master/kategori/'+dataDelete,
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
                        url : '{{ route("accountingKategori") }}',
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'kode_kategori'},
                        {data : 'deskripsi'},
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

            jQuery(document).ready(function() {
                listData();
            });
        </script>

    @endpush

</x-makro.list-data>
