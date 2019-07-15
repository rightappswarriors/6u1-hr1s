<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">

  <!-- CSRF Token-->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name') }}</title>

  <!-- Bootstrap core CSS-->
  <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

  <!-- Jquery UI CSS-->
  <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
  <?php define("token", Session::token());?>

  <!-- Custom styles-->
  <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{asset('css/custom-login.css')}}">
  <script type="text/JavaScript" src='{{asset('js/jquery-3.3.1.min.js')}}'></script>

  <!-- Full Calendar -->
  {{-- <link href='{{asset('css/fullcalendar.css')}}' rel='stylesheet' />
  <link href='{{asset('css/fullcalendar.print.css')}}' rel='stylesheet' media='print' /> --}}

  @yield('to-head')

</head>
<body class="bg-dark">

  @yield('to-body')

  <!-- Notification Handler -->
  @include('include.notification_handler')

  <!-- Bootstrap core JavaScript-->
  <script type="text/javascript" src="{{asset('/js/custom-login.js')}}">
  </script>
  <script type="text/javascript" src="{{asset('/js/custom-btn.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/pds.js')}}"></script>
  {{-- <script src="vendor/jquery/jquery.min.js"></script> --}}
  <script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

  <!-- Core plugin JavaScript-->
  <script type="text/javascript" src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/sb-admin.js')}}"></script>

  <!-- Jquery UI JS-->
  <script type="text/javascript" src="{{asset('js/jquery-ui.js')}}"></script>

  <!-- Full Calendar -->
  {{-- <script type="text/JavaScript" src='{{asset('js/fullcalendar.js')}}'></script> --}}

  <!-- Custom JS-->
  @include('include.js_dateoption_config')
  @yield('to-bottom')
</body>
</html>
