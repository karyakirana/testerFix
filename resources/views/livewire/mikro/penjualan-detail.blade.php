<div>

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
    @endpush
</div>
