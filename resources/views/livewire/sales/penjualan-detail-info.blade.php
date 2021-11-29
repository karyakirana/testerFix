<div>
    <form class="form">
        <div class="form-group row">
            <label class="col-2 col-form-label">Customer</label>
            <div class="col-4">
                <p class="form-control-plaintext">{{$penjualan->customer->nama_cust ?? ''}}</p>
            </div>
            <label class="col-2 col-form-label">Jenis</label>
            <div class="col-4">
                <p class="form-control-plaintext">{{$penjualan->status_bayar ?? ''}}</p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Tgl Nota</label>
            <div class="col-4">
                <p class="form-control-plaintext">{{ isset($penjualan->tgl_nota) ? tanggalan_format($penjualan->tgl_nota) : ''}}</p>
            </div>
            <label class="col-2 col-form-label">Tgl Tempo</label>
            <div class="col-4">
                <p class="form-control-plaintext">{{ isset($penjualan->tgl_tempo) ? tanggalan_format($penjualan->tgl_tempo) : ''}}</p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Gudang</label>
            <div class="col-4">
                <p class="form-control-plaintext">{{$penjualan->branch->branchName ?? ''}}</p>
            </div>
            <label class="col-2 col-form-label">Keterangan</label>
            <div class="col-4">
                <p class="form-control-plaintext">{{$penjualan->keterangan ?? ''}}</p>
            </div>
        </div>
    </form>
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
            <td class="text-right">{{ isset($penjualan->biaya_lain) ? rupiah_format($penjualan->biaya_lain) : 0 }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>PPN</td>
            <td class="text-right">{{ isset($penjualan->ppn) ? rupiah_format($penjualan->ppn) : 0 }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td>Total Bayar</td>
            <td class="text-right">{{ isset($penjualan->total_bayar) ? rupiah_format($penjualan->total_bayar) : 0 }}</td>
        </tr>
        </tfoot>
    </table>
</div>
