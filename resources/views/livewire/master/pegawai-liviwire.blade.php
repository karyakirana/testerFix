<div>
    <x-mikro.card-custom>
        <x-slot name="title">Daftar Pegawai</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" data-toggle="modal" data-target="#formModal">New Record</button>
        </x-slot>

        {{-- Table --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Nama</td>
                    <td>Telepon</td>
                    <td>Email</td>
                    <td>Alamat</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                @if($dataPegawai->count() == 0)
                <tr>
                    <td colspan="6" class="text-center">Tidak Ada data</td>
                </tr>
                @endif
                @foreach($dataPegawai as $row)
                @endforeach
            </tbody>
        </table>
        {{ $dataPegawai->links() }}

    </x-mikro.card-custom>
    <x-nano.modal-standart id="formModal">
        <x-slot name="title">
            Form Pegawai
        </x-slot>
        <form class="form">
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">ID</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="kode" wire:model="kode">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Nama</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nama" wire:model="nama">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Alamat</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="alamat" wire:model="alamat">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Kota</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="kota" wire:model="kota">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Gender</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="gender" wire:model="gender">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Tempat Lahir</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="tempatlahir" wire:model="tempatLahir">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">Tanggal Lahir</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="tglLahir" wire:model="tgllahir">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">KTP</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="ktp" wire:model="ktp">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">NPWP</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="npwp" wire:model="npwp">
                </div>
            </div>
        </form>
        <x-slot name="footer">
            <button class="btn btn-danger">Cancel</button>
            <button class="btn btn-primary">Save</button>
        </x-slot>
    </x-nano.modal-standart>

    <x-nano.modal-standart id="detailPegawai">
    </x-nano.modal-standart>
</div>

