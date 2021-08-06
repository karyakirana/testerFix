<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Transaksi Penjualan</x-slot>
        <x-slot name="toolbar">{{ $kode ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <input type="text" name="id" value="{{ $id ?? '' }}" hidden>
                    <input type="text" name="idCustomer" hidden>
                    <input type="text" name="diskonHidden" hidden>
                    <input type="text" name="idTemp" id="idTemp" value="{{ $idTemp ?? '' }}" hidden>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right" for="customer">Supplier</label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="customer" id="customer" value="{{ $nama_customer ?? '' }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="btnSupplier">Supplier</button>
                                </div>
                            </div>
                        </div>
                        <label for="jenisBayar" class="col-lg-2 col-form-label text-lg-right">Jenis Bayar</label>
                        <div class="col-lg-4 col-form-label">
                            @if(isset($status_bayar))
                                <div class="radio-inline">
                                    <label class="radio radio-success">
                                        <input type="radio" name="jenisBayar" value="Tempo" checked="{{ ($status_bayar == 'Tempo') ? 'checked' : ''}}"><span></span>Tempo
                                    </label>
                                    <label class="radio radio-success">
                                        <input type="radio" name="jenisBayar" value="Tunai" checked="{{ ($status_bayar == 'Tempo') ? 'checked' : ''}}"><span></span>Tunai
                                    </label>
                                </div>
                            @else
                                <div class="radio-inline">
                                    <label class="radio radio-success">
                                        <input type="radio" name="jenisBayar" value="Tempo" checked="checked"><span></span>Tempo
                                    </label>
                                    <label class="radio radio-success">
                                        <input type="radio" name="jenisBayar" value="Tunai"><span></span>Tunai
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Nota</label>
                        <div class="col-lg-4">
                            <x-nano.input-datepicker name="tglNota" id="tglNota" value="{{ $tgl_nota ?? date('d-M-Y') }}" autocomplete="off"/>
                        </div>
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Tempo</label>
                        <div class="col-lg-4">
                            @php
                                $tglTempo = $tgl_tempo ?? date('d-M-Y', strtotime(" +2 months"));
                            @endphp
                            <x-nano.input-datepicker name="tglTempo" id="tglTempo" value="{{ $tglTempo }}" autocomplete="off"/>
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
                                    <th>Harga</th>
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

            jQuery(document).ready(function (){
                tableTransaksi();
            });
        </script>
    @endpush

</x-makro.list-data>
