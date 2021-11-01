<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Penjualan</th>
                <th>Pembuat</th>
                <th>Status</th>
                <th>Status Bayar</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $row)
                <tr>
                    <td>{{ $row->id_jual }}</td>
                    <td>{{ $row->id_jual }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $penjualan->links() }}
</div>
