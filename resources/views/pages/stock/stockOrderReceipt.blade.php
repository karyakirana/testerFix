<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Order {{ $master->kode }}</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style type="text/css">
            @page {
                size: A4;
                margin: 20mm;
            }
            /* print styles */
            @media print {
                body {
                    visibility: hidden;
                    margin: 0;
                    padding: 0;
                    color: #000;
                    background-color: #fff;
                }
                .page {
                    visibility: visible;
                    box-shadow: none;
                    margin: 0;
                    padding: 0;
                }
            }

            @media screen {
                .page {
                    width: 210mm;
                    min-height: 297mm;
                    padding: 20mm
                }
            }
        </style>
    </head>
    <body class="bg-gray-600">
        <div class="page bg-white mx-auto mt-12 shadow-2xl">

            <div class="text-center mb-6 pb-1 border-b-2 border-black">
                <h1 class="text-2xl font-bold">{{ $master->suppliers->namaSupplier }}</h1>
                <p class="pb-3 border-b-4 border-black"></p>
            </div>

            <div class="text-center mb-10 font-bold">
                <h1 class="text-center text-xl">Laporan Penjualan</h1>
                <p class="text-sm"></p>
            </div>

            <table class="table-auto w-full text-xs mb-8">

            </table>

        </div>
    </body>
</html>
