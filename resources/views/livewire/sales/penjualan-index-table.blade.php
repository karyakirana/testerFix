<div>
    <x-mikro.card-custom :title="'Daftar Penjualan'">
        <x-slot name="toolbar"></x-slot>
        <div class="row mb-4">
            <div class="col-6"></div>
            <div class="col-6 row">
                <label class="col-md-4 col-form-label text-right">Search : </label>
                <div class="col-md-8">
                    <input type="text" wire:model="search" class="form-control">
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <x-atom.table-th :width="'10%'">ID</x-atom.table-th>
                    <x-atom.table-th :width="'13%'">Pembuat</x-atom.table-th>
                    <x-atom.table-th :width="'22%'">Customer</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Tgl Nota</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Tgl Tempo</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Jenis</x-atom.table-th>
                    <x-atom.table-th :width="'12%'">Total Bayar</x-atom.table-th>
                    <x-atom.table-th :width="'13%'">Action</x-atom.table-th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataPenjualan as $row)
                    <tr>
                        <x-atom.table-td :type="'center'">{{$row->id_jual}}</x-atom.table-td>
                        <x-atom.table-td>{{ucfirst($row->pengguna->name)}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->customer->nama_cust}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{tanggalan_format($row->tgl_nota)}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{tanggalan_format($row->tgl_tempo)}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{ucfirst($row->status_bayar)}}</x-atom.table-td>
                        <x-atom.table-td :type="'right'">{{rupiah_format($row->total_bayar)}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">
                            @if($row->sudahBayar === 'belum')
                                <button type="button" class="btn btn-sm btn-clean btn-icon" title="edit" wire:click="edit('{{$row->id_jual}}')"><i class="la la-edit"></i></button>
                            @endif
                            <button type="button" class="btn btn-sm btn-clean btn-icon" title="detail" wire:click="openPreview('{{$row->id_jual}}')"><i class="flaticon2-indent-dots"></i></button>
                            <button type="button" class="btn btn-sm btn-clean btn-icon" title="print" wire:click="printPreview('{{$row->id_jual}}')"><i class="flaticon-technology"></i></button>
                        </x-atom.table-td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="7">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $dataPenjualan->links() }}
    </x-mikro.card-custom>

    <x-nano.modal-large :title="'Detail Penjualan '.$penjualanId" id="modalPreview">
        <x-slot name="toolbar">{{$penjualanId}}</x-slot>
        <div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Customer</label>
                <div class="col-md-4">
                    <p class="form-control-plaintext">{{ $customer ?? '' }}</p>
                </div>
                <label class="col-md-2 col-form-label">Jenis</label>
                <div class="col-md-4">
                    <p class="form-control-plaintext">{{ $jenisBayar ?? '' }}</p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Tgl Nota</label>
                <div class="col-md-4">
                    <p class="form-control-plaintext">{{ $tglNota ?? '' }}</p>
                </div>
                <label class="col-md-2 col-form-label">Tgl Tempo</label>
                <div class="col-md-4">
                    <p class="form-control-plaintext">{{ $tglTempo ?? '' }}</p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Gudang</label>
                <div class="col-md-4">
                    <p class="form-control-plaintext">{{ $gudang ?? '' }}</p>
                </div>
                <label class="col-md-2 col-form-label">Keterangan</label>
                <div class="col-md-4">
                    <p class="form-control-plaintext">{{ $keterangan ?? '' }}</p>
                </div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <x-atom.table-th :width="'10%'">Kode</x-atom.table-th>
                    <x-atom.table-th :width="'30%'">Item</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Harga</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Jumlah</x-atom.table-th>
                    <x-atom.table-th :width="'10%'">Diskon</x-atom.table-th>
                    <x-atom.table-th :width="'20%'">Sub Total</x-atom.table-th>
                </tr>
            </thead>
            <tbody>
            @isset($penjualanDetail)
                @forelse($penjualanDetail as $row)
                    <tr>
                        <x-atom.table-td :type="'center'">{{$row->produk->kode_lokal}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->produk->nama_produk}}</x-atom.table-td>
                        <x-atom.table-td :type="'right'">{{rupiah_format($row->harga)}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{$row->jumlah}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">{{diskon_format($row->diskon, 1)}}%</x-atom.table-td>
                        <x-atom.table-td :type="'right'">{{rupiah_format($row->sub_total)}}</x-atom.table-td>
                    </tr>
                @empty
                @endforelse
            @endisset
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td>Total</td>
                    <td class="text-right">
                        @isset($penjualanDetail)
                        {{ rupiah_format($penjualanDetail->sum('sub_total')) }}
                        @endisset
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td>Biaya lain</td>
                    <td class="text-right">{{ isset($biayaLain) ? rupiah_format($biayaLain) : 0 }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td>PPN</td>
                    <td class="text-right">{{ isset($ppn) ? rupiah_format($ppn) : 0 }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td>Total Bayar</td>
                    <td class="text-right">{{ isset($totalBayar) ? rupiah_format($totalBayar) : 0 }}</td>
                </tr>
            </tfoot>
        </table>
    </x-nano.modal-large>

    @push('livewires')
        <script>
            window.livewire.on('openPreview', ()=>{
                $('#modalPreview').modal('show')
            })
            window.livewire.on('closePreview', ()=>{
                $('#modalPreview').modal('hide')
            })
        </script>
    @endpush
</div>
