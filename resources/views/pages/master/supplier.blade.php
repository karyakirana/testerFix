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
                <td width="10%" class="text-center">Jenis</td>
                <td width="25%" class="text-center">Supplier</td>
                <td class="none">Alamat</td>
                <td class="none">NPWP</td>
                <td class="none">Email</td>
                <td class="none">Keterangan</td>
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
                    <label class="col-3 col-form-label">ID Supplier</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="idSupplier" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Jenis Supplier</label>
                    <div class="col-9">
                        <select name="jenisSupplier" id="jenisSupplier" class="form-control select2" data-width="100%"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Nama</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="nama">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Alamat</label>
                    <div class="col-9">
                        <textarea name="alamat" id="alamat" class="form-control" cols="2" rows="2"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Telepon</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="telepon">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">NPWP</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="npwp">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Email</label>
                    <div class="col-9">
                        <input type="email" class="form-control" name="email">
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

                $('body').on('click', '#btnSoft', function (){
                    let dataDelete = $(this).data('value');
                    deletedata(dataDelete);
                })

                $('#btnSave').on('click', function (){
                    savedata();
                });

                $(tableList).DataTable({
                    order : [],
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ route("suppliercrud") }}',
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'kodeSupplier'},
                        {data : 'jenis'},
                        {data : 'namaSupplier'},
                        {data : 'alamatSupplier'},
                        {data : 'npwpSupplier'},
                        {data : 'emailSupplier'},
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

                function reloadTable(){
                    $(tableList).DataTable().ajax.reload();
                }

                $('#jenisSupplier').select2({
                    placeholder : "Silahkan Pilih",
                    width : "resolve",
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        allowClear : true,
                        url : "{{ route('select2JenisSupplier') }}",
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
                                        text: item.jenis,
                                        id: item.id,
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
                    $('#jenisSupplier').append(newOption).trigger('change');
                    $('#modalForm').modal('show'); // show bootstrap modal
                }

                function editData(id)
                {
                    $.ajax({
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/master/supplier/'+id,
                        method: "GET",
                        dataType : "JSON",
                        success : function (data){
                            $('.invalid-feedback').remove();
                            $('.is-invalid').removeClass('is-invalid');
                            $('#formModal').trigger('reset'); // reset form on modals
                            $('[name="id"]').val(data.id);
                            $('[name="idSupplier"]').val(data.kodeSupplier);
                            $('[name="jenisSupplier"]').val(data.jenisSupplier);
                            $('[name="nama"]').val(data.namaSupplier);
                            $('[name="alamat"]').val(data.alamatSupplier);
                            $('[name="telepon"]').val(data.tlpSupplier);
                            $('[name="npwp"]').val(data.npwpSupplier);
                            $('[name="email"]').val(data.emailSupplier);
                            $('[name="keterangan"]').val(data.keteranganSupplier);
                            let dataSelect2_1 = {
                                text: data.jenis,
                                id: data.jenisSupplier,
                            }
                            let newOption = new Option(dataSelect2_1.text, dataSelect2_1.id, false, true);
                            $('#jenisSupplier').append(newOption).trigger('change');
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
                        url : '{{ route("supplier") }}',
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

                function deletedata(id)
                {
                    $.ajax({
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{url('/')}}'+'/master/supplier/'+id,
                        method : 'DELETE',
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
            </script>
        @endpush
    </x-mikro.card-custom>

</x-makro.list-data>
