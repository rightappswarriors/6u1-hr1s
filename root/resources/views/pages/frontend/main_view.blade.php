<html>
	<head>
		<link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    	<link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
		<link href='{{asset('css/fullcalendar.css')}}' rel='stylesheet' />
    	<link href='{{asset('css/fullcalendar.print.css')}}' rel='stylesheet' media='print' />
		<link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
		<link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">

		<link rel="icon" type="image/png" href="{{asset('img/guihulngan.png')}}" sizes="32x32" />
    	<style>
    		body {
    			background: rgb(58, 52, 64);
    		}
    	</style>

    	<script type="text/JavaScript" src='{{asset('js/moment.min.js')}}'></script>
		<script type="text/JavaScript" src='{{asset('js/jquery-3.3.1.min.js')}}'></script>

		<title>HRIS</title>
	</head>
	<body>
		@yield('to-body')
		
		@yield('to-bottom')
	</body>
</html>