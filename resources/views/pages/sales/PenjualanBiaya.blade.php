<x-makro.list-data>
    <x-mikro.card-custom>
        <x-slot name="title">Tambahan Biaya Penjualan</x-slot>
        <x-slot name="toolbar">{{ $id_jual ?? '' }}</x-slot>

        <div class="row">
            <div class="col-lg-8">
                <form action="#" id="formGlobal" class="form">
                    <input type="text" name="id" value="{{ $id_jual ?? '' }}" hidden>
                    <input type="text" name="idCustomer" value="{{ $idCustomer ?? '' }}" hidden>
                    <input type="text" name="diskonHidden" hidden>
                    <input type="text" name="idTemp" id="idTemp" value="{{ $idTemp ?? '' }}" hidden>
                </form>
            </div>
        </div>
    </x-mikro.card-custom>
</x-makro.list-data>
