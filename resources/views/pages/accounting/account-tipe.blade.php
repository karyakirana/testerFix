<x-metronics-layout>
    <x-mikro.card-custom :title="'Daftar Tipe Account'">
        <x-slot name="toolbar">
            <button class="btn btn-primary" id="addData">Add Data</button>
        </x-slot>
        <livewire:accounting.master-account-tipe />
    </x-mikro.card-custom>
    @push('livewires')
        <script>
            $('#addData').on('click', ()=>{
                Livewire.emit('addData');
            })
        </script>
    @endpush

</x-metronics-layout>
