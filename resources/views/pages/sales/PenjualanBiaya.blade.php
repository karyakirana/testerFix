<x-makro.list-data>
    <x-mikro.card-custom>
        <x-slot name="title">Tambahan Biaya Penjualan</x-slot>
        <x-slot name="toolbar">{{ $id_jual ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <input type="text" name="id" value="{{ $id_jual ?? '' }}" hidden>
                </form>
                <x-TablePenjualanBiaya :id-penjualan="$id_jual" />
            </div>
        </div>
    </x-mikro.card-custom>

    <x-nano.modal-large id="modalLedger"></x-nano.modal-large>
</x-makro.list-data>
