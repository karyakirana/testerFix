<div>

    @if(session()->has('message'))
    <div class="alert alert-custom alert-notice alert-light-primary fade show mb-5" role="alert">
        <div class="alert-text">{{ session('message') }}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    @endif


    <x-mikro.card-custom :title="'Sub Kategori'">
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew">New Record</button>
        </x-slot>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Kategori</th>
                <th class="text-center">Sub Kategori</th>
                <th class="text-center">Sub Keterangan</th>
                <th width="15%"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($subKategoriData as $row)
                <tr>
                    <td class="text-center">{{$row->kode_kategori_sub}}</td>
                    <td class="text-center">{{$row->kategori->deskripsi}}</td>
                    <td>{{$row->deskripsi}}</td>
                    <td class="text-center">{{$row->keterangan}}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary" wire:click="edit({{$row->id}})">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger" wire:click="delete({{$row->id}})">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada Data</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-mikro.card-custom>

    <x-nano.modal-standart :title="'Form Sub Kategori'" id="modalSubKategori" wire:ignore.self>
        <form>
            <input type="text" hidden wire:model.defer="inputForm.id">
            <div class="form-group row">
                <label for="kategori" class="col-md-4">Kategori</label>
                <div class="col-md-8">
                    <select name="kategori" id="kategori"
                            class="form-control @error('inputForm.kategori') is-invalid @enderror"
                            wire:model.defer="inputForm.kategori"
                    >
                        <option selected>Dipilih</option>
                        @forelse($selectkategori as $row)
                            <option value="{{$row->id}}">{{$row->deskripsi}}</option>
                        @empty
                            <option value="">Tidak Ada Data</option>
                        @endforelse
                    </select>
                    @error('inputForm.kategori') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="kodeKategori" class="col-md-4">Kode Kategori</label>
                <div class="col-md-8">
                    <input type="text" class="form-control @error('inputForm.kodeKategori') is-invalid @enderror"
                           name="subKategori"
                           wire:model.defer="inputForm.kodeKategori"
                    >
                    @error('inputForm.kodeKategori') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="subKategori" class="col-md-4">Nama Sub Kategori</label>
                <div class="col-md-8">
                    <input type="text" class="form-control @error('inputForm.subKategori') is-invalid @enderror"
                           name="subKategori"
                           wire:model.defer="inputForm.subKategori"
                    >
                    @error('inputForm.subKategori') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="keterangan" class="col-md-4">Keterangan</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="keterangan" wire:model.defer="inputForm.keterangan">
                </div>
            </div>
        </form>
        <x-slot name="footer">
            <button class="btn btn-danger" type="button" wire:click="close">Cancel</button>
            <button class="btn btn-primary" type="button" wire:click="store">Simpan</button>
        </x-slot>
    </x-nano.modal-standart>

    @push('livewires')
        <script>
            $('#btnNew').on("click", ()=>{
                $('#modalSubKategori').modal('show')
            });

            window.livewire.on('openModal', ()=>{
                $('#modalSubKategori').modal('show')
            })

            window.livewire.on('closeModal', ()=>{
                $('#modalSubKategori').modal('hide');
            });
        </script>
    @endpush
</div>
