<input type="text" {{ $attributes->merge(['class'=>'form-control tanggalan']) }}>

@push('scripts')
    <script>
        var customDatePicker = function (){

            let arrows;
            if (KTUtil.isRTL()) {
                arrows = {
                    leftArrow: '<i class="la la-angle-right"></i>',
                    rightArrow: '<i class="la la-angle-left"></i>'
                }
            } else {
                arrows = {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }

            let tanggalan = function (){
                $('.tanggalan').datepicker({
                    rtl: KTUtil.isRTL(),
                    format: 'dd-M-yyyy',
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: arrows
                });
            }

            return {
                init : function (){
                    tanggalan();
                }
            }
        }();

        jQuery(document).ready(function() {
            customDatePicker.init();
        });

    </script>
@endpush
