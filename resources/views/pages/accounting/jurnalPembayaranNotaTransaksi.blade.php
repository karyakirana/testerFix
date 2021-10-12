<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Transaksi Pembayaran Nota</x-slot>
        <x-slot name="toolbar">{{ $id_jual ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <input type="text" name="id" value="{{ $id_jual ?? '' }}" hidden>
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
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Jurnal</label>
                        <div class="col-lg-4">
                            <x-nano.input-datepicker name="tglNota" id="tglNota" value="{{ $tgl_nota ?? date('d-M-Y') }}" autocomplete="off"/>
                        </div>
                        <label class="col-lg-2 col-form-label text-lg-right">Tgl Pembayaran</label>
                        <div class="col-lg-4">
                            @php
                                $tglTempo = $tgl_tempo ?? date('d-M-Y', strtotime(" +2 months"));
                            @endphp
                            <x-nano.input-datepicker name="tglTempo" id="tglTempo" value="{{ $tglTempo }}" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Pembayaran</label>
                        <div class="col-lg-4">
                            <select name="branch" id="branch" class="form-control" autocomplete="off">
                                <option disabled {{ (isset($branch)) ? '' : 'selected' }}>Silahkan Pilih</option>
                                <option value="">Tunai</option>
                                <option value="">Transfer</option>
                            </select>
                        </div>
                        <label class="col-lg-2 col-form-label text-lg-right">Jenis Bank</label>
                        <div class="col-lg-4">
                            <select name="branch" id="branch" class="form-control" autocomplete="off">
                                <option disabled {{ (isset($branch)) ? '' : 'selected' }}>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Rekening</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="keterangan" value="{{ $keterangan ?? '' }}">
                        </div>
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
                                    <th width="10%"></th>
                                    <th>Nomor Nota</th>
                                    <th>Tgl Nota</th>
                                    <th>Tgl Tempo</th>
                                    <th>Biaya Lain</th>
                                    <th>Sub Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>100/PJ/2021</td>
                                    <td>10-Oct-2021</td>
                                    <td>10-Oct-2021</td>
                                    <td>9.000.000</td>
                                    <td>39.000.000</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3">
                                        Unsur Biaya Lain<br>
                                        Karung : 1.000.000<br>
                                        Ekspedisi : 2.000.000<br>
                                        Angkutan : 6.000.000
                                    </td>
                                </tr>
                                </tbody>
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
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-lg-4">
                <div class="example">
                    <div class="example-preview text-center mt-2">
                        <form action="#">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-right">Nota</label>
                                <div class="col-lg-8">
                                    <input type="text" name="nota" id="nota" class="form-control" readonly="" value="100/PJ/2021">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-right">Biaya Lain</label>
                                <div class="col-lg-8">
                                    <input type="text" name="nota" id="nota" class="form-control" readonly="" value="9.000.000">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-right">Ekspedisi</label>
                                <div class="col-lg-8">
                                    <input type="text" name="nota" id="nota" class="form-control" readonly="" value="2.000.000">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-right">Karung</label>
                                <div class="col-lg-8">
                                    <input type="text" name="nota" id="nota" class="form-control" readonly="" value="1.000.000">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-right">Transportasi Lain</label>
                                <div class="col-lg-8">
                                    <input type="text" name="nota" id="nota" class="form-control" readonly="" value="6.000.000">
                                </div>
                            </div>
                            <div class="form-group row">
                                <button type="button" class="btn btn-success col-lg-3 offset-3" id="btnAddDetil">ADD</button>
                                <button type="button" class="btn btn-primary col-lg-4 offset-1" id="btnProduk">PRODUK</button>
                            </div>
                        </form>
                        @if(isset($update))
                            <button class="btn btn-primary btn-lg" id="btnUpdate">SIMPAN & CETAK</button>
                        @else
                            <button class="btn btn-primary btn-lg" id="btnSave">SIMPAN & CETAK</button>
                        @endif
                    </div>
                </div>
            </div>

        </div>


    </x-mikro.card-custom>

</x-makro.list-data>
