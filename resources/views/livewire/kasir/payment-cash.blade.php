<div>
    <x-mikro.card-custom>
        <x-slot name="title">Pembayaran Cash</x-slot>
        <x-slot name="toolbar">
            <select name="" id="" class="form-control" wire:model="metodepembayaran">
                @forelse($accountKas as $row)
                    <option value="{{ $row->id }}">{{ $row->account_name }}</option>
                @empty
                @endforelse
            </select>
        </x-slot>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nomor</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Penjual</th>
                            <th class="text-center">Total Bayar</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarPenjualanDibayar as $index => $row)
                        <tr>
                            <td class="text-center">{{ $row->id_jual }}</td>
                            <td>{{ $row->customer->nama_cust }}</td>
                            <td class="text-center">{{ $row->pengguna->name }}</td>
                            <td class="text-right">{{ rupiah_format($row->total_bayar + $row->biayaPenjualan->sum('nominal')) }}</td>
                            <td class="text-center">
                                <button type="button" wire:click="deletePenjualan({{$index}})" class="btn btn-sm btn-clean">
                                    delete
                                </button>
                                <button type="button" wire:click="penjualanDetail({{$row->id_jual}})" class="btn btn-sm btn-clean">
                                    detail
                                </button>

                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-slot name="footer">
            <div class="text-right">
                <button class="btn btn-primary">Save</button>
            </div>

        </x-slot>
    </x-mikro.card-custom>
</div>
