<div>
    @if(session()->has('message'))
    <x-atom.notification-all :type="'danger'" wire:ignore.self>
        {{ session('message') }}
    </x-atom.notification-all>
    @endif

    <x-mikro.card-custom :title="'Daftar Penjualan Belum Bayar'">

        <div class="col-5 row mb-6">
            <label class="col-2 col-form-label">Search :</label>
            <div class="col-8">
                <input type="text" class="form-control" wire:model="search">
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <x-atom.table-th>ID</x-atom.table-th>
                <x-atom.table-th>Pembuat</x-atom.table-th>
                <x-atom.table-th>Customer</x-atom.table-th>
                <x-atom.table-th>Tgl Nota</x-atom.table-th>
                <x-atom.table-th>Tgl Tempo</x-atom.table-th>
                <x-atom.table-th>Jenis</x-atom.table-th>
                <x-atom.table-th>Total Bayar</x-atom.table-th>
                <x-atom.table-th>Action</x-atom.table-th>
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
                        <button type="button" class="btn btn-sm btn-clean btn-icon" title="edit"><i class="la la-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-clean btn-icon" title="detail" wire:click="showDetailInfo('{{$row->id_jual}}')"><i class="flaticon2-indent-dots"></i></button>
                        <button type="button" class="btn btn-sm btn-clean btn-icon" title="print"><i class="flaticon-technology"></i></button>
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

    <x-nano.modal-large :title="'Nota :  '.$penjualanId" id="detailInfoModal">
        <livewire:sales.penjualan-detail-info />
        <x-slot name="footer">
            <button type="button" class="btn btn-primary" wire:click="konfirmasi('setPiutang')">Set Piutang</button>
            <button type="button" class="btn btn-primary" wire:click="konfirmasi('setLunas')">Set Lunas</button>
            <button type="button" class="btn btn-primary" wire:click="konfirmasi('tambahBiaya')">Tambah Biaya</button>
            <button type="button" class="btn btn-danger" wire:click="hideDetailInfo">Cancel</button>
        </x-slot>
    </x-nano.modal-large>

    <x-nano.modal-standart :title="'Konformasi'" id="modalKonfirmasi" wire:ignore.self>
        Apakah Anda yakin?
        <x-slot name="footer">
            <button class="btn btn-primary" wire:click="{{$hasilKonfirmasi}}()">Yakin</button>
            <button class="btn btn-danger">Tidak</button>
        </x-slot>
    </x-nano.modal-standart>

    @push('livewires')
        <script>
            window.livewire.on('showDetailInfo', ()=>{
                $('#detailInfoModal').modal('show');
            })

            window.livewire.on('hideDetailInfo', ()=>{
                $('#detailInfoModal').modal('hide');
            })

            window.livewire.on('showModalKonfirmasi', ()=>{
                $('#modalKonfirmasi').modal('show')
            })
            window.livewire.on('hideModalKonfirmasi', ()=>{
                $('#modalKonfirmasi').modal('hide')
            })
        </script>
    @endpush
</div>
