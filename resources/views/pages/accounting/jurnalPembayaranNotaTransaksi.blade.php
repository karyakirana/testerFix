<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Transaksi Pembayaran Nota</x-slot>
        <x-slot name="toolbar">{{ $id_jual ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-12">
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
                        <label class="col-lg-2 col-form-label text-lg-right">Pembayaran</label>
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
                        <label class="col-lg-2 col-form-label text-lg-right">Jenis</label>
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
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right">Keterangan</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="keterangan" value="{{ $keterangan ?? '' }}">
                        </div>
                        <label class="col-lg-2 col-form-label text-lg-right"></label>
                        <button type="button" class="btn btn-success btn-lg" id="btnNota">Add Nota</button>
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
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="example-preview text-center mt-2">
                    @if(isset($update))
                        <button class="btn btn-primary btn-lg" id="btnUpdate">SIMPAN & CETAK</button>
                    @else
                        <button class="btn btn-primary btn-lg" id="btnSave">SIMPAN & CETAK</button>
                    @endif
                </div>
            </div>

        </div>

    </x-mikro.card-custom>

</x-makro.list-data>
