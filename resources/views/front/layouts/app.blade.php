<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}">
    <title>{{ config('app.name').' - '.date('Y') }}</title>

    @section('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/libs/datepicker/bootstrap-datepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontAssets/css/styles.css') }}">

        <style>
            .swal2-popup.swal2-toast {
                font-size: 13px;
            }
            .swal2-container {
                z-index: 9999 !important;
            }

            body {
                color: #212529;
            }

            input {
                outline: 0;
            }

            .selected-language {
                color: #f5821f;
            }

            .nav-pills .nav-link.active,
            .nav-pills .show>.nav-link {
                color: #f5821f;
                background-color: #ffffff;
                border-bottom: 1px solid #f5821f;
            }

            .selected-language,
            .nav-link:focus,
            .nav-link:hover {
                color: #f5821f;
            }

            .nav-pills .nav-link {
                border-radius: 0px;
            }
        </style>
    @show
  </head>

  <body>
    <div id="app" style="background-image: linear-gradient(#fff0dd, #eef5ed) !important; ">
        @yield('content')
    </div>

    @section('scripts')
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datepicker/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.js') }}"></script>
        <script src="{{ asset('frontAssets/js/common.js') }}"></script>

        @if (Session::has('alert-message'))
            <script>
                Toast.fire({
                    icon: "{{ Session::get('alert-class', 'info') }}",
                    title: "{{ Session::get('alert-message') }}"
                })
            </script>
        @endif
    @show
  </body>
</html>
