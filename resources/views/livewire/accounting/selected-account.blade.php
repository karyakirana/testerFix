<div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Kategori</label>
        <div class="col-9">
            <select wire:model="selectedKategori" class="form-control" name="kategori" id="kategori">
                <option value="" selected>Pilih Kategori</option>
                @foreach($kategori as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->kode_kategori.' | '.$kategori->deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Sub Kategori</label>
        <div class="col-9">
            <select wire:model="selectedKategoriSub" class="form-control" name="subKategori" id="subKategori">
                <option value="" selected>Pilih Sub Kategori</option>
                @foreach($kategoriSub as $subKategori)
                    <option value="{{ $subKategori->id }}">{{ $subKategori->kode_kategori_sub.' | '.$subKategori->deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Akun</label>
        <div class="col-9">
            <select wire:model="selectedAccount" class="form-control" name="akun" id="akun">
                <option value="" selected>Pilih Akun</option>
                @foreach($account as $account)
                    <option value="{{ $account->id }}">{{ $account->kode_account.' | '.$account->account_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
