<div>
    <x-mikro.card-custom>
        <x-slot name="title">Tambahan Biaya Penjualan</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" data-toggle="modal" data-target="#formModal">{{ $idPenjualan }}</button>
        </x-slot>

        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Biaya</th>
                            <th>Nominal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($itemBiaya as $index => $item)
                        @empty
                            <tr>
                                <td colspan="4" class="text-center"> Tidak ada Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div>
                    <form>
                        <h3 class="font-size-lg mb-8 text-center"> Input Biaya</h3>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Account</label>
                            <div class="col-8">
                                <input type="text" class="form-control">
                                <span></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Tagihan</label>
                            <div class="col-8">
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Nominal</label>
                            <div class="col-8">
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Keterangan</label>
                            <div class="col-8">
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-warning">Reset</button>
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-mikro.card-custom>
</div>
