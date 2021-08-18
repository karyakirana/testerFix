<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Transaksi Retur Baik</x-slot>
        <x-slot name="toolbar">{{ $id_return ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <input type="text" name="id" value="{{ $id_return ?? '' }}" hidden>
                    <input type="text" name="idCustomer" value="{{ $idCustomer ?? '' }}" hidden>
                    <input type="text" name="diskonHidden" hidden>
                    <input type="text" name="idTemp" id="idTemp" value="{{ $idTemp ?? '' }}" hidden>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right" for="customer">Customer</label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="customer" id="customer" value="{{ $nama_customer ?? '' }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="btnCustomer">Customer</button>
                                </div>
                            </div>
                        </div>
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Nota</label>
                        <div class="col-lg-4">
                            <x-nano.input-datepicker name="tglNota" id="tglNota" value="{{ $tgl_nota ?? date('d-M-Y') }}" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Keterangan</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="keterangan" value="{{ $keterangan ?? '' }}">
                        </div>
                        <label class="col-lg-2 col-form-label text-lg-right">Gudang</label>
                        <div class="col-lg-4">
                            <select name="branch" id="branch" class="form-control" autocomplete="off">
                                <option disabled {{ (isset($branch)) ? '' : 'selected' }}>Silahkan Pilih</option>
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
                </form>
                <div class="example">
                    <div class="example-preview">
                        <form id="formTable">
                            <table class="table table-bordered" width="100%" id="tableTransaksi">
                                <thead>
                                <tr>
                                    <th width="30%">Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Sub Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6"></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td>
                                        <label class="col-form-label">Total</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" class="form-control" name="total" id="total" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td>
                                        <label class="col-form-label">PPN</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" class="form-control" name="ppn" id="ppn" value="{{ $ppn ?? '' }}" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td>
                                        <label class="col-form-label">Biaya Lain</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" class="form-control" name="biayaLain" id="biayaLain" value="{{ $biaya_lain ?? '' }}" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td>
                                        <label class="col-form-label">Total Bayar</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" class="form-control" name="totalBayar" id="totalBayar" value="{{ $total_bayar ?? '' }}" autocomplete="off">
                                    </td>
                                </tr>
                                </tfoot>
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
                            <label class="col-lg-4 col-form-label text-right">Harga</label>
                            <div class="col-lg-8">
                                <input type="text" name="harga" id="harga" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Diskon</label>
                            <div class="col-lg-8">
                                <input type="text" name="diskon" id="diskon" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right"></label>
                            <div class="col-lg-8">
                                <input type="text" name="hargaDiskon" id="hargaDiskon" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Jumlah</label>
                            <div class="col-lg-8">
                                <input type="text" name="jumlah" id="jumlah" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-right">Sub Total</label>
                            <div class="col-lg-8">
                                <input type="text" name="subTotal" id="subTotal" class="form-control" readonly>
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

    </x-mikro.card-custom>

    <x-nano.modal-large id="modalCustomer">
        <x-nano.table-customer />
    </x-nano.modal-large>

    <x-nano.modal-large id="modalProduk">
        <x-nano.table-produk />
    </x-nano.modal-large>

    @push('scripts')
        <script>

            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            })

            let modalCustomer = document.getElementById('modalCustomer');

            $('body').on('click', '#btnAddCustomer', function (){
                let dataEdit = $(this).data("value");
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/master/customer/'+dataEdit,
                    method: "GET",
                    dataType : "JSON",
                    success : function (data){
                        $('[name="idCustomer"]').val(data.id_cust);
                        $('[name="customer"]').val(data.nama_cust);
                        $('[name="diskonHidden"]').val(data.diskon);
                        $('#modalCustomer').modal('hide');
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            });

            $('#btnCustomer').on('click', function(){
                $('#modalCustomer').modal('show');
            });

            $('body').on('click', '#btnAddProduk', function (){
                let dataEdit = $(this).data("value");
                let diskon = $('[name="diskonHidden"]').val();
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/master/produk/list/'+dataEdit,
                    method: "GET",
                    dataType : "JSON",
                    success : function (data){
                        let harga = data.harga;
                        $('[name="idProduk"]').val(data.id_produk);
                        $('[name="produk"]').val(data.nama_produk+'\n'+data.kode_lokal+'\n'+data.cover+'\n'+data.nama_kat);
                        $('[name="harga"]').val(harga);
                        $('[name="diskon"]').val(diskon);
                        $('[name="hargaDiskon"]').val(harga - (harga*diskon/100));
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

            $('#btnProduk').on('click', function (){
                $('#modalProduk').modal('show');
            });

            function subTotal(){
                let harga = $('#harga').val();
                let diskon = $('#diskon').val();
                let hargaDiskon = harga - (harga*diskon/100);
                let jumlah = $('#jumlah').val();
                let subTotal = hargaDiskon * jumlah;
                $('#hargaDiskon').val(hargaDiskon);
                $('#subTotal').val(subTotal);
            }

            $('#diskon, #harga, #jumlah').on('keyup', function (){
                subTotal();
            });

            let detilTable = function () {

                let initTable = function (){
                    $('#tableTransaksi').DataTable({
                        order : [],
                        ordering : false,
                        responsive : true,
                        ajax : {
                            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url : '{{ url('/') }}'+'/data/penjualan/trans/'+$('#idTemp').val(),
                            method : 'POST'
                        },
                        columns : [
                            {data : 'produk'},
                            {data : 'harga', render : $.fn.dataTable.render.number( '.', ',', 0, ''), className: "text-right"},
                            {data : 'jumlah', className: "text-center"},
                            {data : 'diskon', className: "text-center"},
                            {data : 'sub_total', render : $.fn.dataTable.render.number( '.', ',', 0, ''), className: "text-right"},
                            {data : 'Action', responsivePriority: -1, className: "text-center"},
                        ],
                        columnDefs: [
                            {
                                targets : [-1],
                                orderable: false
                            }
                        ],
                        drawCallback : function(){
                            let total = $('#tableTransaksi').DataTable().column(-2).data().sum() ?? 0;
                            $('#total').val(formatter.format(total));
                            totalGrand();
                        }
                    });
                }

                return {
                    init : function (){
                        initTable();
                    }
                }
            }();

            function reloadTable()
            {
                $('#tableTransaksi').DataTable().ajax.reload();
            }

            function resetFormDetil()
            {
                $('#detilTrans').trigger('reset');
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
            }

            // CRUD detil trans start

            // get Data from table detil trans
            $('body').on('click', '#btnEdit', function (){
                let editData = $(this).data("value");
                $.ajax({
                    url : '{{ url('/') }}'+'/sales/temp/'+editData,
                    method : 'GET',
                    dataType : 'JSON',
                    success : function (data) {
                        resetFormDetil();
                        $('[name="idTransDetil"]').val(data.id);
                        $('[name="idProduk"]').val(data.idBarang);
                        $('[name="produk"]').val(data.nama_produk+'\n'+data.kode_lokal+'\n'+data.cover+'\n'+data.nama_kat);
                        $('[name="harga"]').val(data.harga);
                        $('[name="diskon"]').val(data.diskon);
                        $('[name="jumlah"]').val(data.jumlah);
                        subTotal();
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            });

            // store data from table detil trans
            $('#btnAddDetil').on('click', function (){
                let editData = $(this).data("value");
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/sales/temp',
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

            $('body').on('click', '#btnSoft', function (){
                let editData = $(this).data("value");
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{ url('/') }}'+'/sales/temp/'+editData,
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
            })

            jQuery(document).ready(function() {
                detilTable.init();
                $('#detilTrans').trigger('reset'); // reset form detil
                totalGrand();
            });

            // total Grand
            function totalGrand()
            {
                let total = $('#tableTransaksi').DataTable().column(-2).data().sum();
                let ppn = $('#ppn').val() ?? 0;
                let biayaLain = $('#biayaLain').val() ?? 0;

                let hasil = total + Number(biayaLain) + (total * Number(ppn) / 100);
                $('#totalBayar').val(formatter.format(hasil));
            }

            // event listener keyUp
            $('#ppn, #biayaLain').keyup(function (){
                totalGrand();
            });

            $('#btnSave').on('click', function(){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{route('returBaik')}}',
                    method: "POST",
                    dataType : "JSON",
                    data : $('#formGlobal, #formTable').serialize(),
                    success : function (data){
                        if (data.status){
                            window.location.href = '{{ route("returBaik") }}';
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

            $('#btnUpdate').on('click', function (){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : '{{route('returBaik')}}',
                    method: "PUT",
                    dataType : "JSON",
                    data : $('#formGlobal, #formTable').serialize(),
                    success : function (data){
                        if (data.status){
                            window.location.href = '{{ route("returBaik") }}';
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


        </script>
    @endpush
</x-makro.list-data>
