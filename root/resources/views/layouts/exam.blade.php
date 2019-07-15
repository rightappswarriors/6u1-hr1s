<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">

    <!-- CSRF Token-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <?php define("token", Session::token());?>

	<title>{{ config('app.name') }}</title>

	<!-- Bootstrap core CSS-->
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!-- Jquery UI CSS-->
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">

    <!-- Custom CSS-->
    <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">

    <!-- Jquery Core JS-->
    <script type="text/JavaScript" src='{{asset('js/jquery-3.3.1.min.js')}}'></script>

    <!-- Template Core-->
    @yield('to-head')
</head>
<body>
	@yield('to-body')
	@yield('to-modal')

	<script type="text/JavaScript" src='{{asset('js/moment.min.js')}}'></script>
	<script type="text/JavaScript" src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/JavaScript" src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
	@yield('to-bottom')
</body>
</html>