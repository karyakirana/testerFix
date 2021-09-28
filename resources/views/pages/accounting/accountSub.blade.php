<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Akun</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" onclick="addData()">New Record</button>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="10%" class="text-center">Kategori</td>
                <td width="10%" class="text-center">Akun Induk</td>
                <td class="text-center">Deskripsi</td>
                <td class="none">Keterangan</td>
                <td width="10%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </x-nano.table-standart>

        <x-nano.modal-standart id="modalForm">
            <x-slot name="title">Sub Akun Form</x-slot>

            <form action="#" id="formModal">
                <input type="text" name="id" hidden>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kode</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="kode">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kategori</label>
                    <div class="col-9">
                        <select name="kategori" id="kategori"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Sub Kategori</label>
                    <div class="col-9">
                        <select name="subKategori" id="subKategori"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Akun</label>
                    <div class="col-9">
                        <select name="akun" id="akun"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Sub Akun</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="subAkun">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Keterangan</label>
                    <div class="col-9">
                        <textarea name="keterangan" id="keterangan" class="form-control" cols="2" rows="2"></textarea>
                    </div>
                </div>
            </form>

            <x-slot name="footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold" id="btnSave">Save changes</button>
            </x-slot>
        </x-nano.modal-standart>

    </x-mikro.card-custom>

</x-makro.list-data>
