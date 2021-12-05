<div>
    <x-mikro.card-custom :title="'Sub Account'">
        <x-slot name="toolbar">
            <button class="btn btn-primary" wire:click="addAccountSub">New Record</button>
        </x-slot>

        <div class="col-6 row mb-8">
            <label for="search" class="col-2 col-form-label">Search :</label>
            <div class="col-6">
                <input type="text" class="form-control">
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <x-atom.table-th></x-atom.table-th>
                    <x-atom.table-th>ID</x-atom.table-th>
                    <x-atom.table-th>Account</x-atom.table-th>
                    <x-atom.table-th>Nama Sub Akun</x-atom.table-th>
                    <x-atom.table-th>Keterangan</x-atom.table-th>
                    <x-atom.table-th></x-atom.table-th>
                </tr>
            </thead>
            <tbody>
            @forelse($dataAccountSub as $row)
                <tr>
                    <x-atom.table-td></x-atom.table-td>
                    <x-atom.table-td>{{$row->kode_account_sub}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->account->account_name}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->sub_name}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->keterangan}}</x-atom.table-td>
                    <x-atom.table-td>
                        <x-atom.button-for-table wire:click="edit('{{$row->id}}')"><i class="la la-edit"></i></x-atom.button-for-table>
                        <x-atom.button-for-table wire:click="delete('{{$row->id}}')"><i class="la la-trash"></i></x-atom.button-for-table>
                    </x-atom.table-td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak Ada Data</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $dataAccountSub->links() }}
    </x-mikro.card-custom>

    <x-nano.modal-standart :title="'Form Account Sub'" id="modalSubAccount" wire:ignore.self>
        <form class="form">
            <input type="text" hidden wire:model.defer="idSubAccount">
            <div class="form-group row">
                <label class="col-3 col-form-label">Kategori</label>
                <div class="col-8">
                    <select name="kategori" id="kategori" class="form-control" wire:model="idKategori">
                        <option selected>Dipilih</option>
                        @forelse($selectKategori as $row)
                            <option value="{{$row->id}}">{{$row->deskripsi}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            @if(!is_null($idKategori))
            <div class="form-group row">
                <label class="col-3 col-form-label">Sub Kategori</label>
                <div class="col-8">
                    <select name="kategoriSub" id="kategoriSub" class="form-control" wire:model="idSubKategori">
                        <option selected>Dipilih</option>
                        @forelse($selectSubKategori as $row)
                            <option value="{{$row->id}}">{{$row->deskripsi}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            @endif
            @if(!is_null($idSubKategori))
            <div class="form-group row">
                <label class="col-3 col-form-label">Account</label>
                <div class="col-8">
                    <select name="account" id="account" class="form-control" wire:model="idAccount">
                        <option selected>Dipilih</option>
                        @forelse($selectAccount as $row)
                            <option value="{{$row->id}}">{{$row->account_name}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label class="col-3 col-form-label">Kode Sub-Account</label>
                <div class="col-8">
                    <input type="text" class="form-control @error('kodeAccountSub') is-invalid @endError" wire:model.defer="kodeAccountSub">
                    @error('kodeAccountSub')
                    <span class="invalid-feedback">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Nama Sub Account</label>
                <div class="col-8">
                    <input type="text" class="form-control @error('namaAccountSub') is-invalid @endError" wire:model.defer="namaAccountSub">
                    @error('namaAccountSub')
                    <span class="invalid-feedback">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Keterangan</label>
                <div class="col-8">
                    <input type="text" class="form-control @error('namaAccountSub') is-invalid @endError" wire:model.defer="keterangan">
                    @error('keterangan')
                    <span class="invalid-feedback">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </form>
        <x-slot name="footer">
            @if($update)
                <button class="btn btn-primary" wire:click="update">Update</button>
            @else
                <button class="btn btn-primary" wire:click="store">Simpan</button>
            @endif
        </x-slot>
    </x-nano.modal-standart>

    @push('livewires')
        <script>
            window.livewire.on('showModalSubAccount', ()=>{
                $('#modalSubAccount').modal('show');
            })
            window.livewire.on('hideModalSubAccount', ()=>{
                $('#modalSubAccount').modal('hide');
            })
        </script>
    @endpush
</div>
