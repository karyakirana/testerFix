<div>
    <x-mikro.card-custom>

        <x-slot name="title">Akun</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew">New Record</button>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <x-atom.table-th>ID</x-atom.table-th>
                <x-atom.table-th>Kategori</x-atom.table-th>
                <x-atom.table-th>Sub Kategori</x-atom.table-th>
                <x-atom.table-th>Keterangan</x-atom.table-th>
                <x-atom.table-th></x-atom.table-th>
            </tr>
            </thead>
            <tbody>
                @forelse($daftarAkun as $row)
                @empty
                    <tr>
                        <td colspan="5">Tidak ada Data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot></tfoot>
        </x-nano.table-standart>

        <x-nano.modal-standart id="modalForm">
            <x-slot name="title">Akun Form</x-slot>

            <form action="#" id="formModal">
                <input type="text" name="id" hidden>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Kode</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="kode_account">
                    </div>
                </div>
                @livewire('accounting.selected-account-kategori-sub')
                <div class="form-group row">
                    <label class="col-3 col-form-label">Akun</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="namaAkun">
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
</div>
