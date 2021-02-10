<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('images/favicoaehr.png') }}">
    <link href="{{asset('includes/bootstrap-4.5.3/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ mix('css/app.css')}}" rel="stylesheet">
    {{--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">--}}

    <script src="{{asset('js/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('js/jquery-3.5-slim.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}" ></script>
    {{--for ajax--}}
    <script src="{{asset('js/jquery-3.5.js')}}"></script>
    {{--<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>--}}
    {{--swal--}}

    @yield('styles')
    @yield('scripts')
</head>
<body>
@yield('content')
</body>
</html>
