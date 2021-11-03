<div>
    <x-mikro.card-custom>
        <x-slot name="title">Daftar Pegawai</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" data-toggle="modal" data-target="#formModal">New Record</button>
        </x-slot>

        {{-- Notifications --}}
        @if($successMessage)
        <x-atom.notification-all :type="'success'">
            {{ $successMessage }}
        </x-atom.notification-all>
        @endif
        {{-- Table --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td width="10%" class="text-center">ID</td>
                    <td width="25%" class="text-center">Nama</td>
                    <td width="10%" class="text-center">Gender</td>
                    <td class="text-center">Alamat</td>
                    <td width="10%" class="text-center">Action</td>
                </tr>
            </thead>
            <tbody>
                @forelse($dataPegawai as $row)
                    <tr>
                        <td class="text-center">{{ $row->kode }}</td>
                        <td>{{ $row->nama }}</td>
                        <td class="text-center">{{ $row->gender }}</td>
                        <td>{{ $row->alamat }}</td>
                        <td class="text-center">
                            <x-atom.button-for-table :name="'edit'"
                                                     data-toggle="modal"
                                                     data-target="#formModal"
                                                     wire:click="edit({{$row->id}})">
                                <i class="la la-edit"></i>
                            </x-atom.button-for-table>
                            <x-atom.button-for-table :name="'delete'"
                                                     wire:click="destroyPegawai({{$row->id}})">
                                <i class="la la-trash"></i>
                            </x-atom.button-for-table>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak Ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $dataPegawai->links() }}

    </x-mikro.card-custom>
    <x-nano.modal-standart id="formModal" wire:ignore.self>
        <x-slot name="title">
            Form Pegawai
        </x-slot>
        <form class="form">
            <input type="text" wire:model.defer="idPegawai" hidden>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">ID</label>
                <div class="col-md-6">
                    <x-atom.input :name="'kode'" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Nama</label>
                <div class="col-md-6">
                    <x-atom.input :name="'nama'" />
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Alamat</label>
                <div class="col-md-6">
                    <x-atom.input :name="'alamat'" />
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Kota</label>
                <div class="col-md-6">
                    <x-atom.input :name="'kota'" />
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Gender</label>
                <div class="col-md-6">
                    <div class="radio-inline">
                        <label class="radio radio-primary">
                            <input type="radio" name="gender" wire:model.defer="gender" value="pria">
                            <span></span>Pria</label>
                        <label class="radio radio-primary">
                            <input type="radio" name="gender" wire:model.defer="gender" value="wanita">
                            <span></span>Wanita</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Tempat Lahir</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="tempatlahir" wire:model.defer="kotaLahir">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Tanggal Lahir</label>
                <div class="col-md-6">
                    <x-nano.input-datepicker name="tglLahir" id="tglLahir" wire:model.defer="tglLahir" autocomplete="off"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">KTP</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="ktp" wire:model.defer="ktp">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">NPWP</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="npwp" wire:model.defer="npwp">
                </div>
            </div>
        </form>
        <x-slot name="footer">
            <button type="button" wire:click.prevent="cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" wire:click.prevent="storePegawai" class="btn btn-primary">Save</button>
        </x-slot>
    </x-nano.modal-standart>

    <x-nano.modal-standart id="detailPegawai">
    </x-nano.modal-standart>

    @push('livewires')
        <script>
            window.livewire.on('pegawaiStore', ()=>{
                $('#formModal').modal('hide');
            });
        </script>
    @endpush
</div>

