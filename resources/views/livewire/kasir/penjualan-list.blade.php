<div>
    <x-mikro.card-custom>
        <x-slot name="title">Daftar Penjualan untuk Biaya Penjualan</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" data-toggle="modal" data-target="#formModal">New Record</button>
        </x-slot>

        <div class="mb-5 col-6">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Search :</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" wire:model="search">
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td width="10%" class="text-center">ID</td>
                    <td width="20%" class="text-center">Customer</td>
                    <td class="text-center">Tgl Nota</td>
                    <td class="text-center">Tgl Tempo</td>
                    <td class="text-center">Status</td>
                    <td class="text-center">Jenis Bayar</td>
                    <td class="text-center">Total Bayar</td>
                    <td class="text-center">Pembuat</td>
                    <td class="none text-center">Biaya Lain</td>
                    <td width="15%" class="text-center">Action</td>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualanAll as $row)
                    <tr>
                        <td>{{ $row->id_jual }}</td>
                        <td>{{ $row->customer->nama_cust }}</td>
                        <td class="text-center">{{ tanggalan_format($row->tgl_nota)}}</td>
                        <td class="text-center">{{ tanggalan_format($row->tgl_tempo) }}</td>
                        <td class="text-center">{{ $row->sudahBayar }}</td>
                        <td class="text-center">{{ $row->status_bayar }}</td>
                        <td class="text-right">{{ rupiah_format($row->total_bayar + $row->biayaPenjualan->sum('nominal')) }}</td>
                        <td class="text-center">{{ $row->pengguna->name }}</td>
                        <td class="text-right">
                            {{rupiah_format($row->biayaPenjualan->sum('nominal') + $row->biaya_lain)}}
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-clean"
                                    data-toggle="modal"
                                    data-target="#detailPenjualan"
                                    wire:click="dataDetail({{$row->id}})"
                                    title="Detail">
                                Detail
                            </button>
                            <div class="dropdown dropdown-inline">
                                <button type="button" class="btn btn-sm btn-clean"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        title="Biaya">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <button type="button" class="btn btn-sm btn-clean dropdown-item"
                                            wire:click="tambahBiaya({{$row->id}})">
                                        Tambah Biaya
                                    </button>
                                    <button type="button" class="btn btn-sm btn-clean dropdown-item">
                                        Edit Biaya
                                    </button>
                                    <button type="button" class="btn btn-sm btn-clean dropdown-item">
                                        Set Piutang
                                    </button>
                                    <button type="button" class="btn btn-sm btn-clean dropdown-item">
                                        Set Cash
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $penjualanAll->links() }}
    </x-mikro.card-custom>

    <x-nano.modal-large id="detailPenjualan" wire:ignore.self :closeModal="'closePegawai'">
        <x-slot name="title">
            Nomor Penjualan
        </x-slot>

        <div class="row">
            <div class="col-6">
                <div class="form-group row">
                    <label class="col-4 col-form-label">Customer</label>
                    <div class="col-8">
                        <div class="form-control-plaintext">{{ $dataDetail['customer'] ?? '' }}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">Pembuat</label>
                    <div class="col-8">
                        <span class="form-control-plaintext">{{ $dataDetail['pembuat'] ?? '' }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">Jenis</label>
                    <div class="col-8">
                        <span class="form-control-plaintext">{{ $dataDetail['jenis'] ?? '' }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">Gudang</label>
                    <div class="col-8">
                        <span class="form-control-plaintext">{{ $dataDetail['gudang'] ?? '' }}</span>
                    </div>
                </div>

            </div>
            <div class="col-6">
                <div class="form-group row">
                    <label class="col-4 col-form-label">Tanggal Nota</label>
                    <div class="col-8">
                        <span class="form-control-plaintext">{{ $dataDetail['tglNota'] ?? '' }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">Tanggal Tempo</label>
                    <div class="col-8">
                        <span class="form-control-plaintext">{{ $dataDetail['tglTempo'] ?? '' }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">Status</label>
                    <div class="col-8">
                        <span class="form-control-plaintext">{{ $dataDetail['status'] ?? '' }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">Keterangan</label>
                    <div class="col-8">
                        <span class="form-control-plaintext">{{ $dataDetail['keterangan'] ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td class="text-center">Kode</td>
                    <td class="text-center">Item</td>
                    <td class="text-center">Harga</td>
                    <td class="text-center">Kuantiti</td>
                    <td class="text-center">Diskon</td>
                    <td class="text-center">Sub-Total</td>
                </tr>
            </thead>
            <tbody>
                @isset($dataDetail['penjualanDetail'])
                    @forelse($dataDetail['penjualanDetail'] as $detail)
                        <tr>
                            <td class="text-center">{{$detail->produk->kode_lokal}}</td>
                            <td>{{$detail->produk->nama_produk}} - {{ $detail->produk->cover }}</td>
                            <td class="text-right">{{rupiah_format($detail->harga)}}</td>
                            <td class="text-center">{{$detail->jumlah}}</td>
                            <td class="text-center">{{diskon_format($detail->diskon, 2)}}%</td>
                            <td class="text-right">{{rupiah_format($detail->sub_total)}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak Ada Data</td>
                        </tr>
                    @endforelse
                @endisset
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td>Total</td>
                    <td class="text-right">
                        @isset($dataDetail['penjualanDetail'])
                            {{ rupiah_format($dataDetail['penjualanDetail']->sum('sub_total')) ?? '' }}
                        @endisset
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td>PPn</td>
                    <td class="text-right">{{ isset($dataDetail['ppn']) ? rupiah_format($dataDetail['ppn']) : '' }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td>Biaya Lain</td>
                    <td class="text-right">{{ isset($dataDetail['biaya_lain']) ? rupiah_format($dataDetail['biaya_lain']) : '' }}</td>
                </tr>
                @isset($biayaPenjualan)
                    @forelse($biayaPenjualan as $row)
                        <tr>
                            <td colspan="4"></td>
                            <td>{{ $row->account->account_name }}</td>
                            <td class="text-right">{{ rupiah_format($row->nominal) }}</td>
                        </tr>
                    @empty
                    @endforelse
                @endisset
                <tr>
                    <td colspan="4"></td>
                    <td>Total Bayar</td>
                    <td class="text-right">{{ isset($dataDetail['totalBayar']) ? rupiah_format($dataDetail['totalBayar']) : '' }}</td>
                </tr>
            </tfoot>
        </table>

        <x-slot name="footer">
            <button type="button" wire:click.prevent="closePegawai" class="btn btn-primary" data-dismiss="modal">Tambah Biaya</button>
            <button type="button" wire:click.prevent="tambahBiaya({{ $dataDetail['id'] ?? '' }})" class="btn btn-primary" data-dismiss="modal">Edit Biaya</button>
            <button type="button" wire:click.prevent="closePegawai" class="btn btn-primary" data-dismiss="modal">Set Piutang</button>
            <button type="button" wire:click.prevent="closePegawai" class="btn btn-primary" data-dismiss="modal">Bayar</button>
            <button type="button" wire:click.prevent="closePegawai" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </x-slot>

    </x-nano.modal-large>

    @push('livewires')
        <script>
            window.livewire.on('penjualanUpdate', ()=>{
                $('#detailPenjualan').modal('hide');
            });

            window.livewire.on('closeModalDetailPegawai', ()=>{
                $('#detailPenjualan').modal('hide');
            });

            // datepicker
            $('#tglLahir').on('change', function (e) {
                let date = $(this).data("#tglLahir");
                // eval(date).set('tglLahir', $('#tglLahir').val())
                console.log(e.target.value);
                @this.tglLahir = e.target.value;
            })
        </script>
    @endpush
</div>
