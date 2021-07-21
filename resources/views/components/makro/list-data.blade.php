<x-MetronicsLayout>
    @prepend('styles')
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    @endprepend

    {{ $slot }}

    @prepend('scripts')
            <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    @endprepend
</x-MetronicsLayout>
