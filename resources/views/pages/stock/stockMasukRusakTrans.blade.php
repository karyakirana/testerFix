<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Transaksi Stock Masuk Rusak</x-slot>
        <x-slot name="toolbar">{{ $kode ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <input type="text" name="id" value="{{ $id ?? '' }}" hidden>
                    <input type="text" name="idSupplier" value="{{$supplier ?? ''}}" hidden autocomplete="off">
                    <input type="text" name="idTemp" id="idTemp" value="{{ $idTemp ?? '' }}" hidden>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right" for="supplier">Supplier</label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="supplier" id="supplier" value="{{ $namaSupplier ?? '' }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="btnSupplier">Supplier</button>
                                </div>
                            </div>
                        </div>
                        <label for="jenisBayar" class="col-lg-2 col-form-label text-lg-right">Gudang</label>
                        <div class="col-lg-4">
                            <select name="branch" id="branch" class="form-control" autocomplete="off">
                                <option disabled {{ (isset($branch)) ? 'selected' : '' }}>Silahkan Pilih</option>
                                @php
                                    $data_branch = \App\Models\Stock\BranchStock::latest()->get();
                                    $branch = $branch ?? '';
                                @endphp
                                @if($data_branch->count() > 0)
                                    @foreach($data_branch as $row)
                                        <option value="{{$row->id}}" {{ ($row->id == $branch) ? 'selected' : '' }}>{{$row->branchName}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Nota</label>
                        <div class="col-lg-4">
                            <x-nano.input-datepicker name="tglMasuk" id="tglMasuk" value="{{ $tgl_keluar ?? date('d-M-Y') }}" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Keterangan</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="keterangan" value="{{ $keterangan ?? '' }}">
                        </div>
                    </div>
                </form>
                <div class="example">
                    <div class="example-preview">
                        <form id="formTable">
                            <table class="table table-bordered" width="100%" id="tableTransaksi">
                                <thead>
                                <tr>
                                    <th width="30%">Produk</th>
                                    <th>Jumlah</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 example">
                <div class="example-preview">
                    <form action="#" class="form" id="detilTrans">
                        <input type="text" name="idTransDetil" hidden>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">ID Produk</label>
                            <div class="col-lg-8">
                                <input type="text" name="idProduk" id="idProduk" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Produk</label>
                            <div class="col-lg-8">
                                <textarea name="produk" id="produk" cols="30" rows="4" class="form-control" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Jumlah</label>
                            <div class="col-lg-8">
                                <input type="text" name="jumlah" id="jumlah" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="button" class="btn btn-success col-lg-3 offset-3" id="btnAddDetil">ADD</button>
                            <button type="button" class="btn btn-primary col-lg-4 offset-1" id="btnProduk">PRODUK</button>
                        </div>
                    </form>
                </div>
                <div class="example-preview text-center">
                    @if(isset($update))
                        <button class="btn btn-primary btn-lg" id="btnUpdate">SIMPAN & CETAK</button>
                    @else
                        <button class="btn btn-primary btn-lg" id="btnSave">SIMPAN & CETAK</button>
                    @endif
                </div>
            </div>
        </div>

        <x-nano.modal-large id="modalProduk">
            <x-nano.table-produk />
        </x-nano.modal-large>

        <x-nano.modal-large id="modalSupplier">
            <x-nano.table-supplier />
        </x-nano.modal-large>

    </x-mikro.card-custom>

    @push('scripts')
        <script>
            // show modal modal produk
            $('#btnProduk').on('click', function (){
                $('#modalProduk').modal('show');
            });

            // set data from table produk
            $('body').on('click', '#btnAddProduk', function (){
                let dataEdit = $(this).data("value");
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/master/produk/list/'+dataEdit,
                    method: "GET",
                    dataType : "JSON",
                    success : function (data){
                        let harga = data.harga;
                        $('[name="idProduk"]').val(data.id_produk);
                        $('[name="produk"]').val(data.nama_produk+'\n'+data.kode_lokal+'\n'+data.cover+'\n'+data.nama_kat);
                        $('#modalProduk').modal('hide');
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            });

            // call supplier
            $('#btnSupplier').on('click', function(){
                $('#modalSupplier').modal('show');
            });

            // set supplier
            $('body').on('click', '#btnAddSupplier', function (){
                let dataEdit = $(this).data("value");
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/master/supplier/'+dataEdit,
                    method: "GET",
                    dataType : "JSON",
                    success : function (data){
                        $('[name="idSupplier"]').val(data.id);
                        $('[name="supplier"]').val(data.namaSupplier);
                        $('#modalSupplier').modal('hide');
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            });

            // table transaksi
            function tableTransaksi()
            {
                $('#tableTransaksi').DataTable({
                    order : [],
                    ordering : false,
                    responsive : true,
                    ajax : {
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url : '{{ url('/') }}'+'/data/stock/temp/detil/'+$('#idTemp').val(),
                        method : 'PATCH'
                    },
                    columns : [
                        {data : 'produk'},
                        {data : 'jumlah', className: "text-center"},
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

            //reload
            function reloadTable()
            {
                $('#tableTransaksi').DataTable().ajax.reload();
            }

            // add to table transaksi
            $('#btnAddDetil').on('click', function(){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/stock/temp',
                    method : 'POST',
                    dataType : 'JSON',
                    data : $('#detilTrans, #formGlobal').serialize(),
                    success : function (data) {
                        if (data.status){
                            resetFormDetil();
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
            });

            // reset form
            function resetFormDetil()
            {
                $('#detilTrans').trigger('reset');
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
            }

            // get from table transaksi
            $('body').on('click', '#btnEdit', function (){
                let editData = $(this).data("value");
                $.ajax({
                    url : '{{ url('/') }}'+'/stock/temp/'+editData,
                    method : 'GET',
                    dataType : 'JSON',
                    success : function (data) {
                        resetFormDetil();
                        $('[name="idTransDetil"]').val(data.id);
                        $('[name="idProduk"]').val(data.idProduk);
                        $('[name="produk"]').val(data.produk.nama_produk+'\n'+
                            data.produk.kode_lokal+
                            '\n'+data.produk.cover+
                            '\n'+data.produk.kategori_harga.nama_kat);
                        $('[name="jumlah"]').val(data.jumlah);
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            });

            // delete from transaksi
            $('body').on('click', '#btnSoft', function (){
                let deleteData = $(this).data("value");
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/stock/temp/'+deleteData,
                    method : 'DELETE',
                    dataType : 'JSON',
                    success : function (data) {
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
            })

            // save data all
            $('#btnSave').on('click', function(){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/stock/masuk/',
                    method: "POST",
                    dataType : "JSON",
                    data : $('#formGlobal, #formTable').serialize(),
                    success : function (data){
                        if (data.status){
                            window.location.href = '{{ route("stokMasuk") }}';
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
            })

            // update data All
            $('#btnUpdate').on('click', function (){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/stock/masuk/',
                    method: "PUT",
                    dataType : "JSON",
                    data : $('#formGlobal, #formTable').serialize(),
                    success : function (data){
                        if (data.status){
                            window.location.href = '{{ route("stokMasuk") }}';
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
            })

            jQuery(document).ready(function (){
                tableTransaksi();
            });
        </script>
    @endpush

</x-makro.list-data>
