<div>
    <x-mikro.card-custom :title="'Transaksi Penerimaan Cash '.$transaksiId">

        <form class="form">
            <div class="form-group row">
                <label class="col-2 col-form-label">Customer</label>
                <div class="col-4">
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Customer</button>
                        </div>
                    </div>
                </div>
                <label class="col-2 col-form-label">Account</label>
                <div class="col-4">
                    <select class="form-control">
                    </select>
                </div>
            </div>
        </form>

    </x-mikro.card-custom>
</div>
