<x-MetronicsLayout>

    <x-mikro.card-custom>
        <x-slot name="title">Print Preview</x-slot>
        <x-slot name="toolbar">
            <div>
                <select class="form-control input" id="printer"></select>
            </div>

            <a href="#" onclick="webprint.printRaw(printEsc(), $('#printer').val());" class="btn btn-primary ml-2">
                <span class="svg-icon svg-icon-md">
                    <i class="text-light-50 flaticon2-print"></i>
                </span>Print
            </a>
        </x-slot>
        <pre id="tampilan"></pre>
    </x-mikro.card-custom>

    @push('scripts')

        <script src="/js/invoicePenjualan.js"></script>
        <script src="/js/webprint.js"></script>
        <script>
            jQuery(document).ready(function() {
                $('#tampilan').html(tampilanEsc());
            });
            var barisUtama = '';
            var barisDetail = '';

            let utama = {!!$dataUtama!!};
            console.log(utama);



            var detail = {!!$dataDetail!!};
            console.log(detail);
            // console.log(barisUtama);

            detail.forEach(function(element){
                console.log(element.id_detil);
            })

            var set_font_1 = '\x1B' + '\x6B' + '\x31';
            var condensed_on = '\x0F';
            var condesed_off = '\x12';
            var esc_p = '\x1B' + '\x50';
            var alt_cond_on = '\x1B'+'\x21' + '\x04';
            var alt_cond_off = '\x1B'+'\x21' + '\x00';
            var bold_on = '\x1B'+'\x45';
            var bold_off = '\x1B'+'\x46';
            var italic_on = '\x1B'+'\x34';
            var italic_off = '\x1B'+'\x35';

            function garis(lop)
            {
                var garis= '+';
                for (index = 0; index < lop; index++) {
                    garis += '-';
                }
                garis += '+';
                return garis;
            }
            function tampilanEsc()
            {
                var barisUtama;
                var subTotal = 0;
                barisUtama = '';
                barisUtama += getEscTable('Nota Retur Baik', 40, 'left')+ getEscTable('Colly =', 50, 'left') + getEscTable("Surabaya : "+ utama.tgl_nota, 47, 'left')+'\n';
                barisUtama += getEscTable('', 40, 'left')+ getEscTable('', 50, 'left') + getEscTable('Kepada Yth, : ' +utama.namaCustomer, 47, 'left')+'\n';
                barisUtama += getEscTable('', 30, 'left')+ getEscTable('', 74, 'left') + getEscTable(utama.addr_cust, 23, 'left')+'\n';
                // barisUtama += getEscTable('Colly = ..........', 20, 'left')+ '\n';
                barisUtama += getEscTable('Nomor : ', 10, 'left')+getEscTable('' + utama.penjualanId, 40, 'left') + getEscTable("Jatuh Tempo : "+ utama.tgl_tempo ?? '', 56, 'right') +'\n';
                barisUtama += garis(135)+'\n';
                barisUtama += getEscTable('KODE', 10, 'center')+getEscTable('Produk', 60, 'center')+getEscTable('Qty', 5, 'center')+getEscTable('Harga', 25, 'center');
                barisUtama += getEscTable('Disc', 5, 'center')+getEscTable('Sub Total', 30, 'center')+'\n';
                barisUtama += garis(135)+'\n';


                detail.forEach(function(data){
                    barisUtama += ' '+getEscTable((data.kode_lokal) ? data.kode_lokal : '', 9, 'left')+getEscTable(data.nama_produk, 60, 'left')+getEscTable((data.jumlah).toString(), 5, 'right')+getEscTable(numberWithCommas(data.harga), 20, 'right')+"     "
                        +getEscTable((data.diskon).toString(), 5, 'center')+getEscTable(numberWithCommas(data.sub_total), 28, 'right')+'\n';
                    subTotal += data.sub_total;
                })
                console.log(subTotal);
                barisUtama += garis(135)+'\n';
                barisUtama += getEscTable('Keterangan : ' + ((utama.penket) ? utama.penket : '-'), 14, 'left')+'\n';
                barisUtama += getEscTable(garis(60), 137, 'right')+'\n';
                barisUtama += getEscTable('Disiapkan Oleh', 30, 'center')+getEscTable('Disetujui Oleh', 30, 'center')+getEscTable('', 16, 'center')+ getEscTable('Sub Total', 15, 'left') +':'+getEscTable( 'Rp. '+ numberWithCommas(numberWithCommas(subTotal)), 42, 'right')+'\n';
                barisUtama += getEscTable('', 76, 'center')+getEscTable('PPN', 15, 'left') +':'+getEscTable( 'Rp. '+ ((utama.ppn) ? numberWithCommas(utama.ppn) : '' ), 42, 'right')+'\n';
                barisUtama += getEscTable('', 76, 'center')+getEscTable('Biaya Lain', 15, 'left') +':'+getEscTable( 'Rp. '+ ((utama.biaya_lain) ? numberWithCommas(utama.biaya_lain) : ''), 42, 'right')+'\n';
                barisUtama += getEscTable(garis(60), 137, 'right')+'\n';
                barisUtama += '('+getEscTable('', 28, 'center')+')  ('+getEscTable('', 28, 'center')+')'+getEscTable('', 16, 'center')+ getEscTable('Total', 13, 'left') +':'+getEscTable( 'Rp. '+ numberWithCommas(utama.total_bayar), 42, 'right')+'\n'+'\n';
                barisUtama += 'Barang tidak dapat dikembalikan kecuali Rusak / Perjanjian sebelumnya.'+'\n';
                barisUtama += '';

                return barisUtama;
            }

            // panjang length 137 karakter
            function printEsc()
            {
                var barisUtama;
                var subTotal = 0;
                barisUtama = esc_init+alt_cond_on+set_font_1 +esc_p;
                barisUtama += alt_cond_off+bold_on+ esc_ul_on+getEscTable('Nota Retur Baik', 15, 'left')+ bold_off+esc_ul_off+alt_cond_on +bold_on+ getEscTable('Colly =', 56, 'left')+bold_off + getEscTable("Surabaya : "+ utama.tgl_nota, 27, 'left')+'\n';
                barisUtama += getEscTable('', 40, 'left')+ getEscTable('', 50, 'left') + getEscTable('Kepada Yth, : ' +utama.namaCustomer, 47, 'left')+'\n';
                barisUtama += getEscTable('', 20, 'left')+ getEscTable('', 60, 'left') + getEscTable(utama.addr_cust, 27, 'center')+'\n';
                // barisUtama += getEscTable('Colly = ..........', 20, 'left')+ '\n';
                barisUtama += getEscTable('Nomor : ', 10, 'left')+alt_cond_off+bold_on+getEscTable('' + utama.penjualanId, 15, 'left') + bold_off+alt_cond_on+ getEscTable("Jatuh Tempo : "+ utama.tgl_tempo ?? '', 56, 'right') +'\n';
                barisUtama += garis(135)+'\n';
                barisUtama += getEscTable('KODE', 10, 'center')+getEscTable('Produk', 60, 'center')+getEscTable('Qty', 5, 'center')+getEscTable('Harga', 25, 'center');
                barisUtama += getEscTable('Disc', 5, 'center')+getEscTable('Sub Total', 30, 'center')+'\n';
                barisUtama += garis(135)+'\n';


                detail.forEach(function(data){
                    barisUtama += ' '+getEscTable((data.kode_lokal) ? data.kode_lokal : '', 9, 'left')+getEscTable(data.nama_produk, 60, 'left')+getEscTable((data.jumlah).toString(), 5, 'center')+getEscTable(numberWithCommas(data.harga), 20, 'right')+"     "
                        +getEscTable((data.diskon).toString(), 5, 'center')+getEscTable(numberWithCommas(data.sub_total), 28, 'right')+'\n';
                    subTotal += data.sub_total;
                })
                console.log(subTotal);
                barisUtama += garis(135)+'\n';
                barisUtama += getEscTable('Keterangan : ' + ((utama.penket) ? utama.penket : '-'), 14, 'left')+'\n';
                barisUtama += getEscTable(garis(60), 137, 'right')+'\n';
                barisUtama += getEscTable('Disiapkan Oleh', 30, 'center')+getEscTable('Disetujui Oleh', 30, 'center')+getEscTable('', 16, 'center')+ getEscTable('Sub Total', 15, 'left') +':'+getEscTable( 'Rp. '+ numberWithCommas(numberWithCommas(subTotal)), 42, 'right')+'\n';
                barisUtama += getEscTable('', 76, 'center')+getEscTable('PPN', 15, 'left') +':'+getEscTable( 'Rp. '+ ((utama.ppn) ? numberWithCommas(utama.ppn) : '' ), 42, 'right')+'\n';
                barisUtama += getEscTable('', 76, 'center')+getEscTable('Biaya Lain', 15, 'left') +':'+getEscTable( 'Rp. '+ ((utama.biaya_lain) ? numberWithCommas(utama.biaya_lain) : ''), 42, 'right')+'\n';
                barisUtama += getEscTable(garis(60), 137, 'right')+'\n';
                barisUtama += '('+getEscTable('', 28, 'center')+')  ('+getEscTable('', 28, 'center')+')'+getEscTable('', 16, 'center')+ getEscTable('Total', 13, 'left') +':'+bold_on+getEscTable( 'Rp. '+ numberWithCommas(utama.total_bayar), 42, 'right')+bold_off+'\n'+'\n';
                barisUtama += italic_on+'Barang tidak dapat dikembalikan kecuali Rusak / Perjanjian sebelumnya.'+italic_off+'\n';
                barisUtama += alt_cond_off+esc_ff;

                return barisUtama;
            }
            console.log(printEsc());
            // printer
            // qz.printers.setPrinterCallbacks((evt) => { console.log(evt.severity, evt.eventType, evt.message); })

            var populatePrinters = function(printers){
                var printerlist = $("#printer");
                printerlist.html('');
                for (var i in printers){
                    printerlist.append('<option value="'+printers[i]+'">'+printers[i]+'</option>');
                }
            };

            var populatePorts = function(ports){
                var portlist = $("#portlist");
                portlist.html('');
                for (var i in ports){
                    portlist.append('<option value="'+ports[i]+'">'+ports[i]+'</option>');
                }
                if ($("#portlist option").length)
                    webprint.openPort($("#portlist option:first-child").val(), {baud:"9600", databits:"8", stopbits:"1", parity:"1", flow:"none"});
            };

            webprint = new WebPrint(true, {
                relayHost: "127.0.0.1",
                relayPort: "8080",
                listPrinterCallback: populatePrinters,
                listPortsCallback: populatePorts,
                readyCallback: function(){
                    webprint.requestPorts();
                    webprint.requestPrinters();
                }
            });
        </script>
    @endpush
</x-MetronicsLayout>

