<x-makro.list-data>

    <x-mikro.card-custom>
        <x-slot name="title">Daftar Kas Masuk</x-slot>
        <x-slot name="toolbar">
            <form id="formToolbar" class="pt-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-3" for="account">Akun</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="akun">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-3" for="account">Sub Akun</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="subAkun">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-mikro.card-custom>

</x-makro.list-data>>
