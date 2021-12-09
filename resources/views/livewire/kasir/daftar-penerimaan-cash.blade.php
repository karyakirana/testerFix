<div>
    <div class="row mb-6">
        <div class="col-4">
            <input type="text" class="form-control" placeholder="Search">
        </div>
        <div class="col-4"></div>
        <div class="col-4 row">
            <div class="col-8"></div>
            <select id="" wire:model="paginate" class="form-control col-3">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <x-atom.table-th>ID</x-atom.table-th>
                <x-atom.table-th>Account</x-atom.table-th>
                <x-atom.table-th>Nominal</x-atom.table-th>
                <x-atom.table-th>Pembuat</x-atom.table-th>
                <x-atom.table-th>Customer</x-atom.table-th>
                <x-atom.table-th>Action</x-atom.table-th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataPenerimaanCash as $row)
                <tr>
                    <x-atom.table-td>{{$row->debetAccount->account_name}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->kreditAccount->account_name}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->nominal_penerimaan}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->users->name}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->customer->nama_cust}}</x-atom.table-td>
                    <x-atom.table-td>{{$row->id}}</x-atom.table-td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="6">Tidak Ada Data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $dataPenerimaanCash->links() }}
</div>
