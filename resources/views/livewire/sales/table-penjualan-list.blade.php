<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Customer</th>
                <th>Pembuat</th>
                <th>Status</th>
                <th>Status Bayar</th>
                <th>Jumlah Bayar</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $row)
                <tr>
                    <td>{{ $row->id_jual }}</td>
                    <td>{{ $row->customer->nama_cust }}</td>
                    <td>{{ $row->pengguna->name }}</td>
                    <td>{{ $row->status_bayar }}</td>
                    <td>{{ $row->sudahBayar }}</td>
                    <td>{{ $row->total_bayar }}</td>
                    <td>{{ $row->id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $penjualan->links() }}
</div>
