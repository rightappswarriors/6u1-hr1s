<!DOCTYPE html>
<html>
<head>
	<title>Print View</title>
	<!-- Bootstrap core CSS-->
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">


	{{-- <style type="text/css">
		table {
			border-collapse: collapse;
		}

		table, th, td {
			border: 1px solid black;
		}

		th {
			text-align: center;
		}
	</style> --}}
	
	<!-- Jquery Core JS-->
    <script type="text/JavaScript" src='{{asset('js/jquery-3.3.1.min.js')}}'></script>

	@yield('head')
	<script type="text/javascript">
        function PrintPage() {
            window.print();
        }
    </script>
	@yield('script-head')
</head>
<body>
	@yield('body')
	@yield('script-body')

	<!-- Scripts -->
	<script type="text/JavaScript" src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>