<x-MetronicsLayout>
    @push('styles')
        <style>
            thead {
                border-top-style: dashed;
                border-bottom-style: dashed;
                border-width: 2pt;
            }
            tbody tr {
                border-bottom-style: dashed;
                border-width: 1pt;
            }

            #ttd {
                margin-top: -150px;
                margin-bottom: 100px;
            }

            @media print {
                @page  {
                    size: letter;
                }
            }
        </style>
    @endpush
    {{ $data }}
    <x-mikro.card-custom>
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Produk</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Diskon</th>
                    <th class="text-center">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                $subTotal= 0;
                @endphp
                @foreach($data->detilPenjualan as $row)
                    <tr>
                        <td class="text-center">{{ $row->kode_lokal }}</td>
                        <td>{{ $row->produk->nama_produk }}</td>
                        <td class="text-center">{{ $row->jumlah }}</td>
                        <td class="text-right">{{ $row->harga }}</td>
                        <td class="text-center">{{ $row->diskon }}</td>
                        <td class="text-right">{{ $row->sub_total }}</td>
                    </tr>
                    @php
                        $subTotal += $row->sub_total;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="4">Keterangan :</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" ></td>
                    <td style="border-top: dashed; border-width: 2px">Total</td>
                    <td class="text-right" style="border-top: dashed; border-width: 2px">{{  $subTotal }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none"></td>
                    <td style="border-top: dashed; border-width: 2px">PPN</td>
                    <td class="text-right" style="border-top: dashed; border-width: 2px">{{ $data->ppn }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none"></td>
                    <td style="border-top: dashed; border-width: 2px">Biaya Lain</td>
                    <td class="text-right" style="border-top: dashed; border-width: 2px">{{ $data->biaya_lain }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none"></td>
                    <td style="border-top: dashed; border-bottom: dashed; border-width: 2px; font-weight: bold">Total Bayar</td>
                    <td class="text-right" style="border-top: dashed; border-bottom: dashed; border-width: 2px; font-weight: bold">{{ $data->total_bayar }}</td>
                </tr>
            </tfoot>
        </table>
        <div class="row" id="ttd">
            <div class="col-md-3">
                <p class="text-center">Disiapkan Oleh</p>
                <p class="text-center mt-25" style="margin-bottom: -80px">(&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;)</p>
            </div>
            <div class="col-md-3">
                <p class="text-center">Disetujui Oleh</p>
                <p class="text-center mt-25" style="margin-bottom: -80px">(&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;)</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                NB : Barang tidak dapat dikembalikan kecuali Rusak / Perjanjian sebelumnya.
            </div>
        </div>
    </x-mikro.card-custom>

</x-MetronicsLayout>
