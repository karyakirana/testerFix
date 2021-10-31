<table class="table table-bordered" style="width: fit-content">
    <thead>
        <tr>
            <td></td>
            <td>Item</td>
            <td>Harga</td>
            <td>Qty</td>
            <td>Diskon</td>
            <td>Sub Total</td>
        </tr>
    </thead>
    <tbody>
        @foreach($penjualanDetail as $row)
            <tr>
                <td>{{$row->produk->kode_lokal}}</td>
                <td>{{$row->produk->nama_produk}} {{$row->produk->cover}}</td>
                <td>{{$row->harga}}</td>
                <td>{{$row->jumlah}}</td>
                <td>{{$row->diskon}}</td>
                <td>{{$row->sub_total}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
