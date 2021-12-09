<div>
    <div class="row py-4">
        <label class="col-1 col-form-label">Search</label>
        <div class="col-2">
            <input type="text" class="form-control" wire:model="search">
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <x-atom.table-th :width="'10%'">Nomor</x-atom.table-th>
                <x-atom.table-th :width="'20%'">Kategori Tipe</x-atom.table-th>
                <x-atom.table-th :width="'30%'">Tipe Akun</x-atom.table-th>
                <x-atom.table-th>Keterangan</x-atom.table-th>
                <x-atom.table-th :width="'10%'"></x-atom.table-th>
            </tr>
        </thead>
        <tbody>
            @forelse($tipeAccount as $row)
                <tr>
                    <x-atom.table-td :type="'center'">{{$loop->iteration}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->tipeKategori->kategori ?? ''}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->tipe}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->keterangan}}</x-atom.table-td>
                    <x-atom.table-td :type="'center'">
                        <x-atom.button-for-table wire:click="edit('{{$row->id}}')"><i class="la la-edit"></i></x-atom.button-for-table>
                        <x-atom.button-for-table wire:click="destroy('{{$row->id}}')"><i class="la la-trash"></i></x-atom.button-for-table>
                    </x-atom.table-td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
    @if($paginate != 'all')
        {{$tipeAccount->links()}}
    @endif
    <x-nano.modal-standart :title="'Input Tipe Account'" id="modalTipeForm" wire:ignore.self>
        <form class="form">
            <input type="text" hidden wire:model.defer="tipeId">
            <div class="form-group row">
                <label class="col-md-4 col-form-label">Kategori Tipe</label>
                <div class="col-md-8">
                    <select name="prefix_kategori" id="prefix_kategori" wire:model="prefix_kategori">
                        <option selected>Pilih Kategori Tipe</option>
                        @forelse($selectKategoriTipe as $row)
                            <option value="{{$row->id}}">{{$row->kategori}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Tipe</label>
                <div class="col-8">
                    <input type="text" class="form-control" wire:model.defer="tipeAkun">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4">Keterangan</label>
                <div class="col-8">
                    <input type="text" class="form-control" wire:model.defer="keterangan">
                </div>
            </div>
        </form>
        <x-slot name="footer">
            <button class="btn btn-primary" wire:click="store">Submit</button>
            <button class="btn btn-danger" wire:click="closeModal">Cancel</button>
        </x-slot>
    </x-nano.modal-standart>
    <x-nano.modal-standart id="modalConfirmation">
        <p>Anda yakin menghapus item ini?</p>
        <x-slot name="toolbar">
            <button class="btn btn-primary">Yakin</button>
            <button class="btn btn-danger">Tidak</button>
        </x-slot>
    </x-nano.modal-standart>
    @push('livewires')
        <script>
            Livewire.on('showModal', ()=>{
                $('#modalTipeForm').modal('show');
            })

            Livewire.on('hideModal', ()=>{
                $('#modalTipeForm').modal('hide');
            })

            Livewire.on('showConfirmDelete', ()=>{
                $('#modalConfirmation').modal('show');
            })

            Livewire.on('hideConfirmDelete', ()=>{
                $('#modalConfirmation').modal('hide');
            })
        </script>
    @endpush
</div>
