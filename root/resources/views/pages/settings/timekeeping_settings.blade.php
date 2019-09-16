@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-wrench"></i> Timekeeping Settings
			<span class="float-right">
				<button class="btn btn-warning" id="btn_edit"><i class="fa fa-pencil" aria-hidden="true" title="Edit"></i></button>
				<button class="btn btn-success" id="btn_done" hidden><i class="fa fa-check" aria-hidden="true" title="Done"></i></button>
			</span>
		</div>
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-3">
					<div class="card">
						<div class="card-header">
							Required Time In
							<span class="float-right text-danger">
								<i class="fa fa-lock exclusive_lock" aria-hidden="true"></i>
							</span>
						</div>
						<div class="card-body">
							{{-- <textarea readonly placeholder="HH:MM:SS"name="req_in_1" class="form-control">{{$data[0]->req_time_in_1}}</textarea> --}}
							<input type="time" class="form-control" readonly name="req_in_1" step="1" value="{{$data[0]->req_time_in_1}}">
						</div>
					</div>
				</div>

				<div class="col-3">
					<div class="card">
						<div class="card-header">
							Required Time In (Afternoon)
							<span class="float-right text-danger">
								<i class="fa fa-lock exclusive_lock" aria-hidden="true"></i>
							</span>
						</div>
						<div class="card-body">
							{{-- <textarea readonly placeholder="HH:MM:SS"name="req_in_2" class="form-control">{{$data[0]->req_time_in_2}}</textarea> --}}
							<input type="time" class="form-control" readonly name="req_in_2" step="1" value="{{$data[0]->req_time_in_2}}">
						</div>
					</div>
				</div>

				<div class="col-3">
					<div class="card">
						<div class="card-header">
							Lunch Break
							<span class="float-right text-danger">
								<i class="fa fa-lock exclusive_lock" aria-hidden="true"></i>
							</span>
						</div>
						<div class="card-body">
							{{-- <textarea readonly placeholder="HH:MM:SS"name="lunch" class="form-control">{{$data[0]->lunch_break}}</textarea> --}}
							<input type="time" class="form-control" readonly name="lunch" step="1" value="{{$data[0]->lunch_break}}">
						</div>
					</div>
				</div>

				<div class="col-3">
					<div class="card">
						<div class="card-header">
							Required Time Out (Noon)
							<span class="float-right text-danger">
								<i class="fa fa-lock exclusive_lock" aria-hidden="true"></i>
							</span>
						</div>
						<div class="card-body">
							{{-- <textarea readonly placeholder="HH:MM:SS"name="req_out_2" class="form-control">{{$data[0]->req_time_out_2}}</textarea> --}}
							<input type="time" class="form-control" readonly name="req_out_2" step="1" value="{{$data[0]->req_time_out_2}}">
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					<div class="card">
						<div class="card-header">
							Required Time Out
							<span class="float-right text-danger">
								<i class="fa fa-lock exclusive_lock" aria-hidden="true"></i>
							</span>
						</div>
						<div class="card-body">
							{{-- <textarea readonly placeholder="HH:MM:SS"name="req_out_1" class="form-control">{{$data[0]->req_time_out_1}}</textarea> --}}
							<input type="time" class="form-control" readonly name="req_out_1" step="1" value="{{$data[0]->req_time_out_1}}">
						</div>
					</div>
				</div>

				<div class="col-3">
					<div class="card">
						<div class="card-header">
							Required Hours
						</div>
						<div class="card-body">
							{{-- <textarea class="form-control" readonly id="req_hours"></textarea> --}}
							<input type="text" class="form-control" readonly id="req_hours">
						</div>
					</div>
				</div>

				<div class="col-3">
					<div class="card">
						<div class="card-header">
							Required Hours (Afternoon)
						</div>
						<div class="card-body">
							{{-- <textarea class="form-control" readonly id="req_hours_aft"></textarea> --}}
							<input type="text" class="form-control" readonly id="req_hours_aft">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	
@endsection

@section('to-bottom')
	<script>
		$(document).ready(function() {
			calculate_required_hours();
			calculate_required_hours_aft();
		});

		$('#btn_edit').on('click', function() {
			$('input[name="req_in_1"]')[0].removeAttribute('readonly');
			$('input[name="req_in_2"]')[0].removeAttribute('readonly');
			$('input[name="req_out_1"]')[0].removeAttribute('readonly');
			$('input[name="req_out_2"]')[0].removeAttribute('readonly');
			$('input[name="lunch"]')[0].removeAttribute('readonly');

			$('#btn_done')[0].removeAttribute('hidden');
			$('#btn_edit')[0].setAttribute('hidden', '');

			let locks = $('.exclusive_lock');
			for(let i=0; i<locks.length; i++) {
				$('.exclusive_lock')[i].classList.remove('fa-lock');
				$('.exclusive_lock')[i].classList.add('fa-unlock-alt');

				$('.exclusive_lock')[i].parentNode.classList.remove('text-danger');
				$('.exclusive_lock')[i].parentNode.classList.add('text-success');

				unlock_anim($('.exclusive_lock')[i]);
			}
		});

		$('#btn_done').on('click', function() {
			$('input[name="req_in_1"]')[0].setAttribute('readonly', '');
			$('input[name="req_in_2"]')[0].setAttribute('readonly', '');
			$('input[name="req_out_1"]')[0].setAttribute('readonly', '');
			$('input[name="req_out_2"]')[0].setAttribute('readonly', '');
			$('input[name="lunch"]')[0].setAttribute('readonly', '');

			$('#btn_edit')[0].removeAttribute('hidden');
			$('#btn_done')[0].setAttribute('hidden', '');

			let locks = $('.exclusive_lock');
			for(let i=0; i<locks.length; i++) {
				$('.exclusive_lock')[i].classList.remove('fa-unlock');
				$('.exclusive_lock')[i].classList.add('fa-unlock-alt');

				$('.exclusive_lock')[i].parentNode.classList.remove('text-success');
				$('.exclusive_lock')[i].parentNode.classList.add('text-danger');

				lock_anim($('.exclusive_lock')[i]);
			}
		});

		function unlock_anim(dom) {
			setTimeout(function() {
				dom.classList.remove('fa-unlock-alt');
				dom.classList.add('fa-unlock')
			}, 300);
		}

		function lock_anim(dom) {
			setTimeout(function() {
				dom.classList.remove('fa-unlock-alt');
				dom.classList.add('fa-lock')
			}, 300);
		}

		function updateValue(col, val)
		{
			$.ajax({
				type : 'post',
				url : '{{url('settings/timekeepingsettings/update')}}/'+col,
				data : {'val':val},
				success: function(data) {
					calculate_required_hours();
					calculate_required_hours_aft();
				},
			});
		}

		function convertTimeFormat(val)
		{
			val = val.split(":");
			return val[0]+":"+val[1]+":00";
		}

		$('input[name="req_in_1"]').on('input', function() {
			// $.ajax({
			// 	type : 'post',
			// 	url : '{{url('settings/timekeepingsettings/update/req_time_in_1')}}',
			// 	data : {'val':$(this).val()},
			// 	success: function(data) {
			// 		calculate_required_hours();
			// 		calculate_required_hours_aft();
			// 	},
			// });
			updateValue('req_time_in_1', convertTimeFormat($(this).val()));
		});

		$('input[name="req_in_2"]').on('input', function() {
			// $.ajax({
			// 	type : 'post',
			// 	url : '{{url('settings/timekeepingsettings/update/req_time_in_2')}}',
			// 	data : {'val':$(this).val()},
			// 	success: function(data) {
			// 		calculate_required_hours();
			// 		calculate_required_hours_aft();
			// 	},
			// });

			updateValue('req_time_in_2', convertTimeFormat($(this).val()));
		});

		$('input[name="req_out_1"]').on('input', function() {
			// $.ajax({
			// 	type : 'post',
			// 	url : '{{url('settings/timekeepingsettings/update/req_time_out_1')}}',
			// 	data : {'val':$(this).val()},
			// 	success: function(data) {
			// 		calculate_required_hours();
			// 		calculate_required_hours_aft();
			// 	},
			// });

			updateValue('req_time_out_1', convertTimeFormat($(this).val()));
		});

		$('input[name="req_out_2"]').on('input', function() {
			// $.ajax({
			// 	type : 'post',
			// 	url : '{{url('settings/timekeepingsettings/update/req_time_out_2')}}',
			// 	data : {'val':$(this).val()},
			// 	success: function(data) {
			// 		calculate_required_hours();
			// 		calculate_required_hours_aft();
			// 	},
			// });

			updateValue('req_time_out_2', convertTimeFormat($(this).val()));
		});

		$('input[name="lunch"]').on('input', function() {
			// $.ajax({
			// 	type : 'post',
			// 	url : '{{url('settings/timekeepingsettings/update/lunch_break')}}',
			// 	data : {'val':$(this).val()},
			// 	success: function(data) {
			// 		calculate_required_hours();
			// 		calculate_required_hours_aft();
			// 	},
			// });

			updateValue('lunch_break', convertTimeFormat($(this).val()));
		});

		function calculate_required_hours() {
			// let first = $('textarea[name="req_in_1"]').val();
			// let second = $('textarea[name="req_out_1"]').val();
			// let lunch = $('textarea[name="lunch"]').val();

			let first = $('input[name="req_in_1"]').val();
			let second = $('input[name="req_out_1"]').val();
			let lunch = $('input[name="lunch"]').val();

			let ms = moment(second, "HH:mm:ss").diff(moment(first, "HH:mm:ss"));
			let d = moment.duration(ms);

			lunch = moment(lunch, "HH:mm:ss");
			let hours = (lunch.isBetween(moment(first, "HH:mm:ss"), moment(second, "HH:mm:ss")))?d.hours()-1:d.hours();

			let string = hours + " hours "+d.minutes()+ " minutes "+d.seconds()+ " seconds.";
			string = (isNaN(hours) || isNaN(d.minutes()) || isNaN(d.seconds()))?"N/A":string;


			$('#req_hours').val(string);
		}

		function calculate_required_hours_aft() {
			// let first = $('textarea[name="req_in_1"]').val();
			// let second = $('textarea[name="req_out_2"]').val();
			// let lunch = $('textarea[name="lunch"]').val();

			let first = $('input[name="req_in_1"]').val();
			let second = $('input[name="req_out_1"]').val();
			let lunch = $('input[name="lunch"]').val();

			let ms = moment(second, "HH:mm:ss").diff(moment(first, "HH:mm:ss"));
			let d = moment.duration(ms);

			lunch = moment(lunch, "HH:mm:ss");
			let hours = (lunch.isBetween(moment(first, "HH:mm:ss"), moment(second, "HH:mm:ss")))?d.hours()-1:d.hours();

			let string =hours + " hours "+d.minutes()+ " minutes "+d.seconds()+ " seconds.";
			string = (isNaN(hours) || isNaN(d.minutes()) || isNaN(d.seconds()))?"N/A":string;

			$('#req_hours_aft').val(string);

		}
	</script>
@endsection