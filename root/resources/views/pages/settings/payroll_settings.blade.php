@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-wrench"></i> Payroll Settings
			<span class="float-right PayrollPeriodMode">
				<button class="btn btn-warning" id="btn_edit"><i class="fa fa-pencil" aria-hidden="true" title="Edit"></i></button>
				<button class="btn btn-success" id="btn_done" hidden><i class="fa fa-check" aria-hidden="true" title="Done"></i></button>
			</span>
		</div>
		<div class="card-body">
			<ul class="nav nav-tabs mb-3">
				<li class="nav-item active">
			     	<a class="nav-link active" href="#home1" data-toggle="tab">Payroll Period</a>
			  	</li>
			  	{{-- <li>
			    	<a class="nav-link" href="#menu1" data-toggle="tab">Menu 2</a>
			  	</li>
			  	<li>
			    	<a class="nav-link" href="#menu2" data-toggle="tab">Menu 3</a>
			  	</li> --}}
			</ul>

			<div class="tab-content">
				<div id="home1" class="tab-pane fade in active show">
					<div class="row">
						<div class="col-3">
							<div class="card">
								<div class="card-header">
									Day From
									<span class="float-right text-danger">
										<i class="fa fa-lock exclusive_lock" aria-hidden="true"></i>
									</span>
								</div>
								<div class="card-body">
									<input type="text" name="date_from" id="date_from" class="form-control" value="{{$data[0]->pp_day_from}}" disabled readonly required>
								</div>
							</div>		
						</div>

						<div class="col-3">
							<div class="card">
								<div class="card-header">
									Day To
									<span class="float-right text-danger">
										<i class="fa fa-lock exclusive_lock" aria-hidden="true"></i>
									</span>
								</div>
								<div class="card-body">
									<input type="text" name="date_to" id="date_to" class="form-control" value="{{$data[0]->pp_day_to}}" disabled readonly required>
								</div>
							</div>		
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
		$('#date_from').datepicker(date_option5);
		$('#date_to').datepicker(date_option5);

		$('#btn_edit').on('click', function() {
			$('input[name="date_from"]')[0].removeAttribute('disabled');
			$('input[name="date_to"]')[0].removeAttribute('disabled');

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
			$('input[name="date_from"]')[0].setAttribute('disabled', '');
			$('input[name="date_to"]')[0].setAttribute('disabled', '');

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

		$('input[name="date_from"]').on('change', function() {
			$.ajax({
				type : 'post',
				url : '{{url('settings/timekeepingsettings/update/pp_day_from')}}',
				data : {'val':$(this).val()},
				success: function(data) {
					
				},
			});
		});

		$('input[name="date_to"]').on('change', function() {
			$.ajax({
				type : 'post',
				url : '{{url('settings/timekeepingsettings/update/pp_day_to')}}',
				data : {'val':$(this).val()},
				success: function(data) {
					
				},
			});
		});
	</script>
@endsection