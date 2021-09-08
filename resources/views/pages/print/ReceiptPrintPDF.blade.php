<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data->id_jual }}</title>
    <link href="{{asset('/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{{asset('/assets/css/themes/layout/header/base/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/themes/layout/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/themes/layout/brand/dark.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/themes/layout/aside/dark.css')}}" rel="stylesheet" type="text/css" />
    <!--end::Layout Themes-->
    <style>

        body {
            background: none;
        }
    </style>
</head>
<body>
    <div class="isi">
        <table class="table table-borderless">
            <thead>
            <tr>
                <td>ID</td>
                <td>Judul</td>
                <td>Harga</td>
                <td>Jumlah</td>
                <td>Diskon</td>
                <td>Sub Total</td>
            </tr>
            </thead>
            <tbody>
            @php
                $subTotal= 0;
            @endphp
            @foreach($data->detilPenjualan as $row)
                <tr>
                    <td>{{ $row->produk->kode_lokal }}</td>
                    <td>{{ $row->produk->nama_produk }}</td>
                    <td class="text-right">{{ $row->harga }}</td>
                    <td class="text-center">{{ $row->jumlah }}</td>
                    <td class="text-center">{{ $row->diskon }}</td>
                    <td class="text-right">{{ $row->sub_total }}</td>
                </tr>
                @php
                    $subTotal += $row->sub_total;
                @endphp

            @endforeach
                <tr>
                    <td colspan="4" style="border-bottom: none">Keterangan :</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" ></td>
                    <td style="border-top: dashed; border-width: 1px">Total</td>
                    <td class="text-right" style="border-top: dashed; border-width: 1px">{{  $subTotal }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none"></td>
                    <td style="border-top: dashed; border-width: 1px">PPN</td>
                    <td class="text-right" style="border-top: dashed; border-width: 1px">{{ $data->ppn }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none"></td>
                    <td style="border-top: dashed; border-width: 1px">Biaya Lain</td>
                    <td class="text-right" style="border-top: dashed; border-width: 1px">{{ $data->biaya_lain }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border: none"></td>
                    <td style="border-top: dashed; border-bottom: dashed; border-width: 1px; font-weight: bold">Total Bayar</td>
                    <td class="text-right" style="border-top: dashed; border-bottom: dashed; border-width: 1px; font-weight: bold">{{ $data->total_bayar }}</td>
                </tr>
            </tfoot>
        </table>
        <div id="ttd" class="row">
            <div class="col-1">
                <p class="text-center">Disiapkan Oleh</p>
                <p class="text-center" style="margin-top: 40px">_________________</p>
            </div>
            <div class="col-2">
                <p class="text-center">Disetujui Oleh</p>
                <p class="text-center" style="margin-top: 40px">_________________</p>
            </div>
        </div>
        <div id="nb" style="font-size: 12px">
                NB : Barang tidak dapat dikembalikan kecuali Rusak / Perjanjian sebelumnya.
        </div>
    </div>

</body>
</html>
