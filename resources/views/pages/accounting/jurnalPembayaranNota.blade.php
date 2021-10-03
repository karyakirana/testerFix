<x-makro.list-data>

    <x-mikro.card-custom>

        <x-slot name="title">Pembayaran Nota</x-slot>
        <x-slot name="toolbar">
            <button class="btn btn-primary font-weight-bolder" id="btnNew" onclick="addData()">New Record</button>
        </x-slot>

        <x-nano.table-standart id="listTable">
            <thead>
            <tr>
                <td width="10%" class="text-center">ID</td>
                <td width="10%" class="text-center">Customer</td>
                <td width="10%" class="text-center">Penerima</td>
                <td class="text-right">Nominal</td>
                <td class="none">Keterangan</td>
                <td width="10%">Action</td>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </x-nano.table-standart>

    </x-mikro.card-custom>

</x-makro.list-data>
