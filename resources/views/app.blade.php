<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css?v=1.0.0') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css?v=1.0.0') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap4-toggle.min.css?v=1.0.0') }}">
    {{-- Datatable --}}
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css?v=1.0.0') }}"
        type="text/css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css?v=1.0.0') }}" type="text/css">
    {{-- Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datepicker.min.css?v=1.0.0') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hello, world!</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row mt-2">
            <div class="col-md-12">
                @include('navs')
            </div>
        </div>

        @yield('content')
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    {{-- Bootstrap Select --}}
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/chosen/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap4-toggle.min.js') }}"></script>
    {{-- Datepicker --}}
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <script>
        $(function() {
            // setting dropdown di table responsive
            // hold onto the drop down menu                                             
            var dropdownMenu;

            // and when you show it, move it to the body                                     
            $(window).on('show.bs.dropdown', function(e) {

                // grab the menu        
                dropdownMenu = $(e.target).find('.cuk');

                // detach it and append it to the body
                $('body').append(dropdownMenu.detach());

                // grab the new offset position
                var eOffset = $(e.target).offset();

                // make sure to place it where it would normally go (this could be improved)
                dropdownMenu.css({
                    'display': 'block',
                    'top': eOffset.top + $(e.target).outerHeight(),
                    'center': eOffset.center
                });
            });

            // and when you hide it, reattach the drop down, and hide it normally                                                   
            $(window).on('hide.bs.dropdown', function(e) {
                $(e.target).append(dropdownMenu.detach());
                dropdownMenu.hide();
            });

            $("input[data-type='currency']").on({
                keyup: function() {
                    formatCurrency($(this));
                },
                blur: function() {
                    formatCurrency($(this), "blur");
                }
            });

            $('.date-picker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('.date-time-picker').datetimepicker({
                autoclose: true
            }).on('change', function(e) {
                $(this).datetimepicker('hide');
            });

            $('.time-picker').datetimepicker({
                datepicker: false,
                format: 'H:i',
                autoclose: true
            }).on('change', function(e) {
                $(this).datetimepicker('hide');
            });
        });


        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }

        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();



            // don't validate empty input
            if (input_val === "") {
                return;
            }

            // original length
            var original_len = input_val.length;

            // initial caret position 
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;
                // console.log(input_val.length);

                // final formatting
                if (input_val.length > 13) {
                    input_val += ".00";
                }

                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
    @yield('script')
</body>

</html>
