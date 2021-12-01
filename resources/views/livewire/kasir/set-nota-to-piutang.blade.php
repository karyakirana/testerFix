<div>
    <x-mikro.card-custom :title="'Set Nota Ke Piutang Per Customer'">
        <x-slot name="toolbar">
            <button class="btn btn-success" type="button" wire:click="showNota">Tambah Nota</button>
        </x-slot>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <x-atom.table-th :width="'5%'">
                                <label class="checkbox checkbox-outline flex-center">
                                    <input type="checkbox">
                                    <span></span>
                                </label>
                            </x-atom.table-th>
                            <x-atom.table-th :width="'10%'">ID</x-atom.table-th>
                            <x-atom.table-th :width="'30%'">Customer</x-atom.table-th>
                            <x-atom.table-th>Tgl Nota</x-atom.table-th>
                            <x-atom.table-th>Tgl Tempo</x-atom.table-th>
                            <x-atom.table-th :width="'15%'">Total Bayar</x-atom.table-th>
                            <x-atom.table-th :width="'10%'">Action</x-atom.table-th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($listNota as $index => $row)
                        <tr>
                            <x-atom.table-td>
                                <label class="checkbox checkbox-outline flex-center">
                                    <input type="checkbox">
                                    <span></span>
                                </label>
                            </x-atom.table-td>
                            <x-atom.table-td>{{$row['idJual']}}</x-atom.table-td>
                            <x-atom.table-td>{{$row['customer']}}</x-atom.table-td>
                            <x-atom.table-td :type="'center'">{{tanggalan_format($row['tglNota'])}}</x-atom.table-td>
                            <x-atom.table-td :type="'center'">{{$row['tglTempo'] ? tanggalan_format($row['tglTempo']) : ''}}</x-atom.table-td>
                            <x-atom.table-td :type="'right'" class="pr-5">{{rupiah_format($row['totalBayar'])}}</x-atom.table-td>
                            <x-atom.table-td :type="'center'" >
                                <div class="btn-group">
                                    <x-atom.button-for-table :name="'Edit'"><i class="la la-edit"></i></x-atom.button-for-table>
                                    <x-atom.button-for-table :name="'Edit'" wire:click="showDetail('{{$row['id']}}')"><i class="la la-list"></i></x-atom.button-for-table>
                                    <x-atom.button-for-table :name="'Delete'"><i class="la la-trash"></i></x-atom.button-for-table>
                                </div>
                            </x-atom.table-td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="7">Tidak Ada data</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-slot name="footer">
            <div class="text-center">
                <div class="btn-group">
                    <button class="btn btn-primary">Submit</button>
                    <button class="btn btn-warning">Cancel</button>
                </div>
            </div>
        </x-slot>
    </x-mikro.card-custom>
</div>
