<div>
    <x-mikro.card-custom>
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
            @forelse($penjualanTempo as $row)
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
        {{ $penjualanTempo->links() }}
        <livewire:mikro.penjualan-detail />
    </x-mikro.card-custom>
</div>

