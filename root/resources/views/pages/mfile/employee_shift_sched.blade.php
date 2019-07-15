@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-user"></i><i class="fa fa-clock-o"></i> Employee Shift Schedule
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover" id="dataTable">
									<thead>
										<tr>
											<th>Shift Code</th>
											<th>Name</th>
											<th>Time In</th>
											<th>Time Out</th>
											<th>Date From</th>
											<th>Date To</th>
										</tr>
									</thead>
									<tbody>
										@isset($empsched)
											@if(count($empsched)>0)
												@foreach($empsched as $pp)
												<tr data_id="{{$pp->esid}}" data_name="{{$pp->name}}" data_t_i="{{$pp->date_from}}" data_t_o="{{$pp->date_to}}" data_sf_c="{{$pp->code}}" data_emp="{{$pp->empid}}">
													<td>{{$pp->esid}}</td>
													<td>{{$pp->name}}</td>
													<td>{{date("h:i A", strtotime($pp->time_in))}}</td>
													<td>{{date("h:i A", strtotime($pp->time_out))}}</td>
													<td>{{date("M d, Y", strtotime($pp->date_from))}}</td>
													<td>{{date("M d, Y", strtotime($pp->date_to))}}</td>
												</tr>
												@endforeach
											@endif
										@endisset
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="card">
						<div class="card-body">
							<button type="button" class="btn btn-success btn-block" id="opt-add"><i class="fa fa-plus"></i> Add</button>
							<button type="button" class="btn btn-primary btn-block" id="opt-update"><i class="fa fa-edit"></i> Edit</button>
							<button type="button" class="btn btn-danger btn-block" id="opt-delete"><i class="fa fa-trash"></i> Delete</button>
							<button type="button" class="btn btn-info btn-block" id="opt-print"><i class="fa fa-print"></i> Print List</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Modal -->
	<div class="modal fade" id="modal-pp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-pp" data="#">
						@csrf
						<span class="AddMode">
							<div class="row">
								<div class="col"> <!-- Column 1 -->
									<div class="form-group">
										<label>Shift Code: <strong style="color:red;display: none">*</strong></label>
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="" readonly="">
									</div>
									<div class="form-group">
										<label>Employee: <strong style="color:red">*</strong></label>
										<select name="txt_emp" class="form-control" id="" required="">
											@isset($employee)
												@if(count($employee) > 0)
													<option value="">Select Employee..</option>
													@foreach ($employee as $e)
														<option value="{{$e->empid}}">{{$e->name}}</option>
													@endforeach
												@else
													<option value="">No Employee registered..</option>
												@endif
											@else
												<option value="">No Employee registered..</option>
											@endisset
										</select>
									</div>
									<div class="form-group">
										<label>Shift: <strong style="color:red">*</strong></label>
										<select name="txt_sft" class="form-control" onchange="ShwSchd()" required="">
											@isset($shiftsched)
												@if(count($shiftsched) > 0)
													<option value="">Select Shift..</option>
													@foreach ($shiftsched as $e)
														<option value="{{$e->code}}" id="SS_{{$e->code}}" tI="{{$e->time_in}}" tO="{{$e->time_out}}">{{$e->name}}</option>
													@endforeach
												@else
													<option value="">No Shift registered..</option>
												@endif
											@else
												<option value="">No Shift registered..</option>
											@endisset
										</select>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<label for="">Time In</label>
												<input class="form-control" type="time" name="sf_tI" readonly="">
											</div>
											<div class="col-sm-6">
												<label for="">Time Out</label>
												<input class="form-control" type="time" name="sf_tO" readonly="">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<label for="">Effectivity Date From</label>
												<input class="form-control" name="txt_dt_fr" type="date">
											</div>
											<div class="col-sm-6">
												<label for="">Effectivity Date To</label>
												<input class="form-control" name="txt_dt_to" type="date">
											</div>
										</div>
									</div>
									{{-- <div class="form-group">
										<label>Name:</label>
										<input type="text" name="txt_name" style="text-transform: uppercase;" class="form-control" placeholder="DESCRIPTION">
									</div>
									<div class="form-group">
										<label>Time In:</label>
										<input type="time" name="txt_time_in" class="form-control">
									</div>
									<div class="form-group">
										<label>Time Out:</label>
										<input type="time" name="txt_time_out" class="form-control">
									</div> --}}
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to cancel <strong><span id="TOBEDELETED" style="color:red"></span></strong> schedule?</p>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="frm-pp" class="btn btn-success">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="ClearFld()">Close</button>
					</span>
					<span class="DeleteMode">
						<button type="submit" form="frm-pp" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" onclick="ClearFld()">Cancel</button>
					</span>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{asset('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript">
		$('#date_from').datepicker(date_option5);
		$('#date_to').datepicker(date_option5);
		var table = $('#dataTable').DataTable(date_option_min);
	</script>
	<script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script>
	<script type="text/javascript">
		function ShwSchd()
		{
			if($('select[name="txt_sft"]').val() != '')
			{
				$('input[name="sf_tI"]').val($('#SS_'+$('select[name="txt_sft"]').val()).attr('tI'));
				$('input[name="sf_tO"]').val($('#SS_'+$('select[name="txt_sft"]').val()).attr('tO'));
			}
			else
			{
				$('input[name="sf_tI"]').val('');
				$('input[name="sf_tO"]').val('');
			}
		}
		$('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/employee-shift-schedule')}}');

			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val('');

			$('select[name="txt_emp"]').val('').trigger('change');
			$('select[name="txt_sft"]').val('').trigger('change');
			$('input[name="txt_dt_fr"]').val('');
			$('input[name="txt_dt_to"]').val('');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-update').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/employee-shift-schedule')}}/update');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));

			$('select[name="txt_emp"]').val(selected_row.attr('data_emp')).trigger('change');
			$('select[name="txt_sft"]').val(selected_row.attr('data_sf_c')).trigger('change');
			$('input[name="txt_dt_fr"]').val(selected_row.attr('data_t_i'));
			$('input[name="txt_dt_to"]').val(selected_row.attr('data_t_o'));

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-delete').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/employee-shift-schedule')}}/delete');

			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));

			$('select[name="txt_emp"]').val(selected_row.attr('data_emp')).trigger('change');
			$('select[name="txt_sft"]').val(selected_row.attr('data_sf_c')).trigger('change');
			$('input[name="txt_dt_fr"]').val(selected_row.attr('data_t_i'));
			$('input[name="txt_dt_to"]').val(selected_row.attr('data_t_o'));
			
			$('#TOBEDELETED').text(selected_row.attr('data_name'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		});
	</script>
@endsection