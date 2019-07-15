<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>
		@if(View::hasSection('title'))
        @yield('title')
	    @else
	        {{config('app.name')}}
	    @endif
	</title>
	<!-- Bootstrap core CSS-->
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!-- Jquery Core JS-->
    <script type="text/JavaScript" src='{{asset('js/jquery-3.3.1.min.js')}}'></script>

    <style type="text/css">
    	.frame-big > img {
			width: 260px;
			height: 260px;
		}
		.error-header {
			font-size: 3rem;
		}
		.error-subheader {
			font-size: 2rem;
		}
    </style>
</head>
<body>
	<div class="container-fluid">
		<div class="text-center">
			<div class="mt-5 frame-big">
				<img src="{{asset('images')}}/@yield('image_name')">
			</div>
			<p class="font-weight-bold error-header mb-3">
				@if(View::hasSection('header'))
				@yield('header')
				@else
				No Header.
				@endif
			</p>
			<p class="error-subheader">
				@if(View::hasSection('message'))
				@yield('message')
				@else
				No message.
				@endif
			</p>
			<a href="{{url('/')}}">Back</a>
		</div>
	</div>
	<!-- Scripts-->
	<script type="text/JavaScript" src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>