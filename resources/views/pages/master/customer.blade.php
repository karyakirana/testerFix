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

        <x-nano.modal-standart id="modalForm">
            <x-slot name="title">Kategori Produk Form</x-slot>
            <form action="#" id="formModal">
                <input type="text" name="id" hidden>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Nama</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="nama">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Diskon</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="diskon">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Telepon</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="telepon">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Alamat</label>
                    <div class="col-9">
                        <textarea name="alamat" id="alamat" class="form-control" cols="2" rows="2"></textarea>
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

                // jquery click edit
                $('body').on('click', '#btnEdit', function(){
                    let dataEdit = $(this).data("value");
                    editData(dataEdit)
                });

                // jquery click delete
                $('body').on('click', '#btnSoft', function (){
                    let dataDelete = $(this).data('value');
                    deletedata(dataDelete);
                })

                // jquery click save
                $('#btnSave').on('click', function (){
                    savedata();
                });

                // datatable
                $(tableList).DataTable({
                    order : [],
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/customer',
                        method : 'PATCH'
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

                // reload datatables
                function reloadTable(){
                    $(tableList).DataTable().ajax.reload();
                }

                // function show modals form for add data
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

                // function get data and show modal for show the data
                function editData(id)
                {
                    $.ajax({
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/master/customer/'+id,
                        method: "GET",
                        dataType : "JSON",
                        success : function (data){
                            $('.invalid-feedback').remove();
                            $('.is-invalid').removeClass('is-invalid');
                            $('#formModal').trigger('reset'); // reset form on modals
                            $('[name="id"]').val(data.id_cust);
                            $('[name="nama"]').val(data.nama_cust);
                            $('[name="diskon"]').val(data.diskon);
                            $('[name="telepon"]').val(data.telp_cust);
                            $('[name="alamat"]').val(data.addr_cust);
                            $('[name="keterangan"]').val(data.keterangan);
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

                // function deleting data
                function deletedata(id)
                {
                    $.ajax({
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/master/customer/'+id,
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
                    });

                }

                // function to save and update data to database
                function savedata()
                {
                    $.ajax({
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ route("customer") }}',
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
