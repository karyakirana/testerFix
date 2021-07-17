<x-MetronicsLayout>
    @prepend('styles')
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    @endprepend

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap py-3">
            <div class="card-title">
                <h3 class="card-label">Penjualan</h3>
            </div>
            <div class="card-toolbar">
                <button class="btn btn-primary font-weight-bolder">New Record</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered"></table>
        </div>
        <div class="card-footer"></div>
    </div>

    @push('scripts')
            <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    @endpush
</x-MetronicsLayout>
