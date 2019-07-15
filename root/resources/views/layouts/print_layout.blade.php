<!DOCTYPE html>
<html>
<head>
	<title>Print View</title>
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
</body>
</html>