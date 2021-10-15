<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Akun</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" onclick="addData()">New Record</button>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="10%" class="text-center">Kategori</td>
                <td width="10%" class="text-center">Sub Kategori</td>
                <td class="text-center">Akun</td>
                <td class="none">Keterangan</td>
                <td width="10%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </x-nano.table-standart>

        <x-nano.modal-standart id="modalForm">
            <x-slot name="title">Akun Form</x-slot>

            <form action="#" id="formModal">
                <input type="text" name="id" hidden>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kode</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="kode_account">
                    </div>
                </div>
                @livewire('accounting.selected-account-kategori-sub')
                <div class="form-group row">
                    <label class="col-3 col-form-label">Akun</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="namaAkun">
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

            // store data
            $('#btnSave').on('click', function (){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ route("accountingAccount") }}',
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
            })

            // edit data
            $('body').on('click', '#btnEdit', function ($row){
                let dataEdit = $(this).data('value');
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/accounting/master/account/'+dataEdit,
                    method : "GET",
                    dataType : "JSON",
                    success : function (data){
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');
                        $('#formModal').trigger('reset'); // reset form on modals
                        $('[name="id"]').val(data.id);
                        $('[name="kode_account"]').val(data.kode_account);
                        $('[name="kategori"]').val(data.account_kategori.kategori_id).change();
                        $('[name="subKategori"]').val(data.kategori_sub_id);
                        $('[name="namaAkun"]').val(data.account_name);
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
            })

            // datatables
            function listData ()
            {
                $('#listTable').DataTable({
                    order : [],
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ route("accountingAccount") }}',
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'kode_account'},
                        {data : 'account_kategori.kategori.deskripsi'},
                        {data : 'account_kategori.deskripsi'},
                        {data : 'account_name'},
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
