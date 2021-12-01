<x-metronics-layout>
    <livewire:kasir.set-nota-to-piutang />
    <x-nano.modal-large id="detailInfo">
        <livewire:sales.penjualan-detail-info />
    </x-nano.modal-large>
    <x-nano.modal-large id="modalNota" :title="'Penjualan Belum Bayar'">
        <livewire:table.penjualan-table />
    </x-nano.modal-large>
    @push('livewires')
        <script>
            window.livewire.on('showDetailInfo', ()=>{
                $('#detailInfo').modal('show');
            })

            window.livewire.on('showModalNota', ()=>{
                $('#modalNota').modal('show');
            })
            window.livewire.on('getPenjualan', ()=>{
                $('#modalNota').modal('hide');
            })
        </script>
    @endpush
</x-metronics-layout>
