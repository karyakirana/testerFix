jQuery(document).ready(function() {
    // KTDatatablesDataSourceAjaxServer.init();
    // $('.rupiah').text(formatter.format(this));
});
// formatter.format(this)

// local rupiah
const formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
})

// memberikan titik pada currency
const numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}



var esc_init = "\x1B" + "\x40"; // initialize printer
var esc_p = "\x1B" + "\x70" + "\x30"; // open drawer
var gs_cut = "\x1D" + "\x56" + "\x4E"; // cut paper
var esc_a_l = "\x1B" + "\x61" + "\x30"; // align left
var esc_a_c = "\x1B" + "\x61" + "\x31"; // align center
var esc_a_r = "\x1B" + "\x61" + "\x32"; // align right
var esc_double = "\x1B" + "\x21" + "\x31"; // heading
var font_reset = "\x1B" + "\x21" + "\x02"; // styles off
var esc_ul_on = "\x1B" + "\x2D" + "\x31"; // underline on
var esc_ul_off = "\x1B" + "\x2D" + "\x30"; // underline on
var esc_bold_on = "\x1B" + "\x45"; // emphasis on
var esc_bold_off = "\x1B" + "\x46"; // emphasis off

var esc_serif_font = "\x1B" + "\x6B" +"\x31";
var normal_font = "\x1B" + "\x50";
var title_font = "\x1B" + "\x67";

var esc_dwidth_on = "\x1B" + "\x0E"; // Double width line
var esc_dwidth_off = "\x14";
var esc_dheight_on = "\x1B" + "\x77" + "\x31";
var esc_dheight_off = "\x1B" + "\x77" + "\x30";
var esc_dstrike_on = "\x1B" + "\x47";
var esc_dstrike_off = "\x1B" + "\x48";
var esc_ff = "\x0C"; // make to front
var esc_c_110 = "\x1b" + "\x43" + "\x6E"; // page length 110

// i'm masterpiece
function getEscTable(data = '', pagelength, align){
    var spasi ="";
    var row = "";
    if (data.length < pagelength) {
        var num = pagelength - data.length;
        for(num; num>0; num--){
            spasi += " ";
        }
    } else {
        spasi = "";
    }

    if(align == 'left'){
        row = data + spasi;
    } else if (align == 'right') {
        row = spasi + data;
    } else if (align == 'center') {
        var spase='';
        if (data.length < pagelength) {
            var spa = Math.round((pagelength - data.length)/2);
            for(spa; spa > 0; spa--){
                spase += " ";
            }
        } else {
            spase = '';
        }
        row = spase + data + spase;
    }


    return row;
}
