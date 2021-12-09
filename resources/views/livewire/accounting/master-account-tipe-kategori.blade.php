<div>
    <x-mikro.card-custom>
        <x-slot name="title">Kategori Tipe</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary" id="btn" wire:click="addData">New Category</button>
        </x-slot>
        <div class="row mb-6">
            <div class="col-3">
                <input type="text" class="form-control" wire:model="search" placeholder="search">
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td class="text-center" width="10%">ID</td>
                    <td class="text-center">Prefix Kategori</td>
                    <td class="text-center">Kategori</td>
                    <td class="text-center">Keterangan</td>
                    <td class="text-center" width="10%">Action</td>
                </tr>
            </thead>
            <tbody>
                @forelse($accountKategoriTipe as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $row->prefix_kategori }}</td>
                        <td class="text-center">{{ $row->kategori }}</td>
                        <td class="text-center">{{ $row->keterangan }}</td>
                        <td class="text-center">
                            <x-atom.button-for-table :name="'edit'"
                                                     wire:click="editData({{$row->id}})">
                                <i class="la la-edit"></i>
                            </x-atom.button-for-table>
                            <x-atom.button-for-table :name="'delete'"
                                                     wire:click="deleteData({{$row->id}})">
                                <i class="la la-trash hover-danger"></i>
                            </x-atom.button-for-table>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $accountKategoriTipe->links() }}
    </x-mikro.card-custom>

    <x-nano.modal-standart id="formKategoriTipe" wire:ignore.self>
        <x-slot name="title">
            Form Kategori Tipe
        </x-slot>

        <form class="form">
            <div class="form-group row">
                <input type="text" wire:model.defer="formKategoriTipe.id" hidden>
                <label class="col-4 col-form-label">Prefix Kategori</label>
                <div class="col-8">
                    <input type="text" class="form-control @error('formKategoriTipe.prefix') is-invalid @enderror"
                           wire:model.defer="formKategoriTipe.prefix">
                    @error('formKategoriTipe.prefix')<span class="is-invalid">{{$message}}</span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Kategori</label>
                <div class="col-8">
                    <input type="text" class="form-control @error('formKategoriTipe.kategori') is-invalid @enderror"
                           wire:model.defer="formKategoriTipe.kategori">
                    @error('formKategoriTipe.kategori')<span class="is-invalid">{{$message}}</span>@enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Keterangan</label>
                <div class="col-8">
                    <input type="text" class="form-control" wire:model.defer="formKategoriTipe.keterangan">
                </div>
            </div>
        </form>

        <x-slot name="footer">
            @if(isset($formKategoriTipe['id']))
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
                $('#formKategoriTipe').modal('show');
            });

            window.livewire.on('modalHide', ()=>{
                // modal show
                $('#formKategoriTipe').modal('hide');
            });
        </script>
    @endpush
</div>
