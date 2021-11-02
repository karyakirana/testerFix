<x-makro.list-data>
    <x-mikro.card-custom>
        <x-slot name="title">Tambahan Biaya Penjualan</x-slot>
        <x-slot name="toolbar">{{ $id_jual ?? '' }}</x-slot>

        <livewire:sales.biaya-penjualan-transaksi :idPenjualan="$id_jual" :idTemp="$idTemporary"/>

    </x-mikro.card-custom>

    <x-nano.modal-large id="modalLedger"></x-nano.modal-large>
</x-makro.list-data>
