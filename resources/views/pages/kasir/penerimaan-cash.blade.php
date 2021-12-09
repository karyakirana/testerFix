<x-metronics-layout>
    <x-mikro.card-custom :title="'Daftar Penerimaan Cash'">
        <x-slot name="toolbar">
            <a href="{{route('kasir.penerimaan.cash.transaksi')}}" class="btn btn-primary">New Data</a>
        </x-slot>
        <livewire:kasir.daftar-penerimaan-cash />
    </x-mikro.card-custom>
</x-metronics-layout>
