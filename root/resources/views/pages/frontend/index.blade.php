@extends('pages.frontend.main_view')

@section('to-body')

	{{-- <button class="btn btn-primary" style="position: absolute; left: 20px; top: 20px" onclick="window.location = '{{url('/')}}'">Back</button> --}}

	<div class="container">

		<center class="text-white" style="padding-top: 5%"><h2><b>HUMAN RESOURCE <br> INFORMATION SYSTEM</b></h2></center>

		<div class="row mb-4" style="padding-top: 10%">

			@php
				$color = [
						'primary',
						'warning',
						'success', 
						'danger',
						];
				$icon = [
						'clock-o',
						'lock',
						'calendar', 
						'plane',
						];
				$name = [
						'Personnel Locator', // Timekeeping
						'Account Login',
						'Calendar', 
						'Leave Application', // 
						];
				$func = [
						'toTimekeeping()',
						'toLogin()',
						'toCalendar()',
						'toLeaves()',
						];
			@endphp

			<div class="container table-responsive">
				<div class="row">
					@for($i=0; $i<4; $i++)
						<div class="col-sm-6 mb-3" style="<?php if($i%2!=0) echo 'padding-right:360px'; elseif ($i%2==0) echo 'padding-left:360px'; ?>" >
			        		<div class="card mx-auto text-white bg-{{$color[$i]}} o-hidden w-100 h-100 dashboard-leave-menu" onclick="{{$func[$i]}}">
			        			<div class="card-body" style="height: 120px">
			        				<div class="card-body-icon mt-4" style="opacity: 0.4;">
			        					<i class="fa fa-fw fa-{{$icon[$i]}}"></i>
			        				</div>
			        				<div>&nbsp;</div>
			        				<div class="text-uppercase small"><center><h5><b>{{$name[$i]}}</b></h5></center></div>
			        			</div>
			        		</div>
						</div>
					@endfor
				</div>
			</div>
    	</div>
    </div>
@endsection

@section('to-bottom')
	<script>
		function toCalendar() {
			window.location = '{{url('/user/calendar')}}';
		}

		function toLogin() {
			window.location = '{{url('/login')}}';
		}

		function toTimekeeping() {
			window.location = '{{url('/timekeeping')}}';
		}

		function toLeaves() {

		}
	</script>

	<style>
		.card-body {
			cursor: pointer;
		}

		.card:hover {
			opacity: 0.8;
		}

		.card {
			transition: 0.3s;
			color: white;
		}
	</style>
@endsection