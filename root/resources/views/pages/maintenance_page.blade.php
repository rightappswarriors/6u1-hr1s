@extends('layouts.errors2')

@section('to-body')

@php
	switch ($page) {
		case 1:
			$img_path = 'images/ug-service-setup-computer.png';
			$msg_header = 'This page is currently under maintenance';
			$msg_subheader = 'Sorry for the inconvenince.';
			break;

		case 2:
			$img_path = 'images/restricted.png';
			$msg_header = 'This page is restricted';
			$msg_subheader = 'Please contact your administrator for assistance.';
			break;

		case 3:
			$img_path = 'images/search.png';
			$msg_header = 'Page Not Found.';
			$msg_subheader = 'Sorry, the page you are looking for could not be found.';
			break;

		case 4:
			$img_path = 'images/ug-service-setup-computer.png';
			$msg_header = 'Maintenance Mode is Active';
			$msg_subheader = 'All pages are currently dissabled.';
			break;

		case 5:
			$img_path = 'images/on-icon.jpg';
			$msg_header = 'Maintenance Mode is Disabled';
			$msg_subheader = 'All pages can now be accessed.';
			break;

		case 6:
			$img_path = 'images/blank_page.png';
			$msg_header = 'Page is blank';
			$msg_subheader = 'This page has no content yet.';
			break;

		case 7:
			$img_path = 'images/ug-service-setup-computer.png';
			$msg_header = 'Website is currently under maintenance';
			$msg_subheader = 'We will be back shortly. Sorry for the inconvenince.';
			break;
		
		default:
			$img_path = 'images/error.png';
			$msg_header = 'Error!';
			$msg_subheader = 'An error occured while processing your request.';
			break;
	}
@endphp

<div class="myimg-frame-big" align="center">
	<img src="{{url($img_path)}}">
</div>
<div class="mt-2" align="center">
	<h2>{{$msg_header}}</h2>
	<br>
	<h3>{{$msg_subheader}}</h3>
	<a href="{{url('/')}}">return</a>
</div>

@endsection