<div>
    @if(session()->has('gagal'))
    <x-atom.notification-all :type="'danger'">
        {{session('gagal')}}
    </x-atom.notification-all>
    @endif

    @if(session()->has('simpan'))
        <x-atom.notification-all :type="'success'">
            {{session('simpan')}}
        </x-atom.notification-all>
    @endif


    <x-mikro.card-custom>

        <x-slot name="title">Akun</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" wire:click="addData">New Record</button>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <x-atom.table-th>ID</x-atom.table-th>
                <x-atom.table-th>Sub Kategori</x-atom.table-th>
                <x-atom.table-th>Nama Akun</x-atom.table-th>
                <x-atom.table-th>Keterangan</x-atom.table-th>
                <x-atom.table-th></x-atom.table-th>
            </tr>
            </thead>
            <tbody>
                @forelse($daftarAkun as $row)
                    <tr>
                        <x-atom.table-td :type="'center'">{{$row->kode_account}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->accountKategori->deskripsi ?? ''}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->account_name}}</x-atom.table-td>
                        <x-atom.table-td>{{$row->keterangan}}</x-atom.table-td>
                        <x-atom.table-td :type="'center'">
                            <button class="btn btn-clean" wire:click="edit('{{$row->id}}')">edit</button>
                            <button class="btn btn-clean" wire:click="destroy('{{$row->id}}')">delete</button>
                            @can('SuperAdmin')
                                <button class="btn btn-clean" wire:click="forceDestroy('{{$row->id}}')">force</button>
                            @endcan
                        </x-atom.table-td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada Data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot></tfoot>
        </x-nano.table-standart>
        {{ $daftarAkun->links() }}
    </x-mikro.card-custom>

    <x-nano.modal-standart id="modalAccountForm">
        <x-slot name="title">Akun Form</x-slot>

        <form action="#" id="formModal">
            <input type="text" name="id" hidden wire:model.defer="accountId">
            <div class="form-group row">
                <label class="col-3 col-form-label">Kode</label>
                <div class="col-9">
                    <input type="text" class="form-control" name="kode_account" wire:model.defer="kodeAccount">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Sub Kategori</label>
                <div class="col-9">
                    <select id="kategoriSubId" class="form-control" wire:model.defer="kategoriSubId">
                        <option selected>Silahkan Pilih</option>
                        @forelse($dataSubKategori as $row)
                            <option value="{{$row->id}}">{{$row->kode_kategori_sub}} | {{$row->deskripsi}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Akun</label>
                <div class="col-9">
                    <input type="text" class="form-control" name="namaAkun" wire:model.defer="accountName">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Keterangan</label>
                <div class="col-9">
                    <textarea name="keterangan" id="keterangan" class="form-control" cols="2" rows="2" wire:model.defer="keterangan"></textarea>
                </div>
            </div>
        </form>

        <x-slot name="footer">
            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary font-weight-bold" wire:click="store">Save changes</button>
        </x-slot>
    </x-nano.modal-standart>

    @push('livewires')
        <script>
            window.livewire.on('showModalAccount', ()=>{
                $('#modalAccountForm').modal('show');
            })

            window.livewire.on('hideModalAccount', ()=>{
                $('#modalAccountForm').modal('hide');
            })
        </script>
    @endpush
</div>
