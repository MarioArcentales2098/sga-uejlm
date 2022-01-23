<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        {{-- <link rel="shortcut icon" href="assets/images/favicon.ico"> --}}
        <title>SISTEMA -  @yield('title-auth')</title>

        <link href="{{ asset('herramienta/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('herramienta/assets/css/style.css')}}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('herramienta/assets/js/modernizr.min.js')}}"></script>
        <style>
            input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button{-webkit-appearance: none; margin: 0;}input[type="number"] {-moz-appearance: textfield;}
        </style>
    </head>
    <body>
        @yield('section-auth')    


        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="{{ asset('herramienta/assets/js/jquery.min.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/detect.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/fastclick.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/jquery.blockUI.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/waves.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/jquery.nicescroll.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/jquery.scrollTo.min.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/jquery.slimscroll.js')}}"></script>
        
        <script src="{{ asset('herramienta/lib/switchery/switchery.min.js')}}"></script>
        
        <script src="{{ asset('herramienta/assets/js/jquery.core.js')}}"></script>
        <script src="{{ asset('herramienta/assets/js/jquery.app.js')}}"></script>

    </body>
</html>