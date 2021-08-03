<x-MetronicsLayout>

    <x-mikro.card-custom>
        <x-slot name="title">Print Preview</x-slot>

        <pre></pre>
    </x-mikro.card-custom>

</x-MetronicsLayout>

@push('scripts')
    <script>
        let master = {{ $master }}; // get from controller
        let detil = {{ $detil }}; // get from controller
    </script>
@endpush
