<div>
    <x-mikro.card-custom>
        <x-slot name="title">Daftar Penjualan untuk Biaya Penjualan</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" data-toggle="modal" data-target="#formModal">New Record</button>
        </x-slot>

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
                    <td width="10%">Action</td>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualanAll as $row)
                    <tr>
                        <td>{{ $row->id_jual }}</td>
                        <td>{{ $row->customer->nama_cust }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tgl_nota)->locale('id_ID')->isoFormat('LL')}}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tgl_tempo)->locale('id_ID')->isoFormat('LL') }}</td>
                        <td>{{ $row->sudahBayar }}</td>
                        <td>{{ $row->status_bayar }}</td>
                        <td>{{ $row->total_bayar }}</td>
                        <td>{{ $row->pengguna->name }}</td>
                        <td>{{ $row->biaya_lain }}</td>
                        <td>{{ $row->id }}</td>
                    </tr>
                @empty
                    <tr colspan="10">
                        <td>Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $penjualanAll->links() }}
    </x-mikro.card-custom>
</div>
