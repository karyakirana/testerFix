<x-MetronicsLayout>
    @prepend('styles')
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    @endprepend

    {{ $slot }}

    @prepend('scripts')
            <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
            <script>
                jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
                    return this.flatten().reduce( function ( a, b ) {
                        if ( typeof a === 'string' ) {
                            a = a.replace(/[^\d.-]/g, '') * 1;
                        }
                        if ( typeof b === 'string' ) {
                            b = b.replace(/[^\d.-]/g, '') * 1;
                        }

                        return a + b;
                    }, 0 );
                } );
            </script>
    @endprepend
</x-MetronicsLayout>
