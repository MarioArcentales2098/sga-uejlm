<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="descripction">
    <meta name="author" content="author">
    @php $ran = rand(); @endphp
    <link rel="shortcut icon" href="{{ asset('recursos/icologoRM.png') }}">
    <title>{{ System::nameApp() }} - @yield('title-herramienta')</title>

    <link href="{{asset('herramienta/lib/morris/morris.css')}}" rel="stylesheet">
    <link href="{{asset('herramienta/lib/switchery/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{asset('herramienta/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('herramienta/assets/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("herramienta/custom/custom.css?v=$ran")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('herramienta/lib/toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('herramienta/lib/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('herramienta/assets/js/modernizr.min.js')}}"></script>

    <!-- REMPLAZO DE DATATABLE -->
    {{-- <link rel="stylesheet" href="{{ asset('herramienta/lib/simpledatatable/style.css') }}"> --}}
    {{-- <script src="{{ asset('herramienta/lib/simpledatatable/script.js') }}"></script> --}}

    @yield('style-herramienta')
    @yield('style-page-herramienta')
</head>


<body class="fixed-left">
    <div id="wrapper">
        <!-- ========== ToBar ========== -->
        @include('layouts.herramienta.includes.topbar')

        <!-- ========== Sidebar ========== -->
        @include('layouts.herramienta.includes.sidebar')

        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    @yield('content-herramienta')
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        {{-- <footer class="footer">{{date('Y')}} © SISTEMA. </footer> --}}
    </div>

    <script>
        var resizefunc = [];

    </script>
    <script src="{{asset('herramienta/assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('herramienta/assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('herramienta/assets/js/detect.js')}}"></script>
    <script src="{{asset('herramienta/assets/js/fastclick.js')}}"></script>
    {{-- <script src="{{asset('herramienta/assets/js/jquery.blockUI.js')}}"></script> --}}
    {{-- <script src="{{asset('herramienta/assets/js/waves.js')}}"></script> --}}
    {{-- <script src="{{asset('herramienta/assets/js/jquery.nicescroll.js')}}"></script> --}}
    {{-- <script src="{{asset('herramienta/assets/js/jquery.scrollTo.min.js')}}"></script> --}}
    <script src="{{asset('herramienta/assets/js/jquery.slimscroll.js')}}"></script>
    {{-- <script src="../plugins/switchery/switchery.min.js"></script> --}}

    {{-- <script src="{{asset('herramienta/lib/morris/morris.min.js')}}"></script> --}}
    {{-- <script src="{{asset('herramienta/lib/raphael/raphael.min.js')}}"></script> --}}

    {{-- <script src="{{asset('herramienta/lib/waypoints/lib/jquery.waypoints.min.js')}}"></script> --}}
    {{-- <script src="{{asset('herramienta/lib/counterup/jquery.counterup.js')}}"></script> --}}


    @yield('script-page-herramienta')

    <script src="{{asset('herramienta/assets/js/jquery.core.js')}}"></script>
    <script src="{{asset('herramienta/assets/js/jquery.app.js')}}"></script>
    <script src="{{asset('herramienta/lib/select2/js/select2.min.js')}}"></script>
    <script src="{{asset("herramienta/custom/custom.js?v=$ran")}}"></script>
    <script src="{{asset('herramienta/lib/toastr/toastr.min.js')}}"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>
    <script>var spinner = `<i class="fa fa-circle-o-notch fa-spin"></i>`;</script>
    
    @yield('script-herramienta')

</body>
</html>
