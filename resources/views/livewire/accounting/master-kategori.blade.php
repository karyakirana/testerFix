<div>
    <x-mikro.card-custom>
        <x-slot name="title">Kategori Akun</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" wire:click="addData">New Record</button>
        </x-slot>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td width="10%" class="text-center">ID</td>
                    <td class="text-center">Kategori</td>
                    <td class="text-center">keterangan</td>
                    <td width="10%" class="text-center">Action</td>
                </tr>
            </thead>
            <tbody>
                @forelse($accountKategori as $row)
                    <tr>
                        <td class="text-center">{{ $row->kode_kategori }}</td>
                        <td class="text-center">{{ $row->deskripsi }}</td>
                        <td class="text-center">{{ $row->keterangan }}</td>
                        <td class="text-center">
                            <x-atom.button-for-table :name="'edit'"
                                                     wire:click="editData({{$row->id}})">
                                <i class="la la-edit"></i>
                            </x-atom.button-for-table>
                            <x-atom.button-for-table :name="'delete'"
                                                     wire:click="deleteData({{$row->id}})">
                                <i class="la la-trash"></i>
                            </x-atom.button-for-table>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $accountKategori->links() }}
    </x-mikro.card-custom>

    <x-nano.modal-standart id="formKategori" wire:ignore.self>
        <x-slot name="title">
            Form Kategori
        </x-slot>

        <form class="form">
            <div class="form-group row">
                <input type="text" wire:model.defer="formKategori.id" hidden>
                <label class="col-4 col-form-label">Nomor Kategori</label>
                <div class="col-8">
                    <input type="text" class="form-control @error('formKategori.nomorKategori') is-invalid @enderror" wire:model.defer="formKategori.nomorKategori">
                    @error('formKategori.nomorKategori')<span class="is-invalid">{{$message}}</span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Kategori</label>
                <div class="col-8">
                    <input type="text" class="form-control @error('formKategori.kategori') is-invalid @enderror" wire:model.defer="formKategori.kategori">
                    @error('formKategori.kategori')<span class="is-invalid">{{$message}}</span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Keterangan</label>
                <div class="col-8">
                    <input type="text" class="form-control" wire:model.defer="formKategori.keterangan">
                </div>
            </div>
        </form>

        <x-slot name="footer">
            @if(isset($formKategori['id']))
                <button type="button" wire:click.prevent="updateData" class="btn btn-primary">Save</button>
            @else
                <button type="button" wire:click.prevent="storeData" class="btn btn-primary">Save</button>
            @endif
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
        </x-slot>
    </x-nano.modal-standart>

    @push('livewires')
        <script>
            window.livewire.on('modalShow', ()=>{
                // modal show
                $('#formKategori').modal('show');
            });

            window.livewire.on('modalHide', ()=>{
                // modal show
                $('#formKategori').modal('hide');
            });
        </script>
    @endpush
</div>


