@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-calendar"></i> <i class="fa fa-clock-o"></i> Payroll Period
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
											<th>Code</th>
											<th>Date From</th>
											<th>Date To</th>
										</tr>
									</thead>
									<tbody>
										@if(count($payroll)>0)
										@foreach($payroll as $pp)
										<tr data="{{$pp->pay_code}}">
											<td>{{$pp->pay_code}}</td>
											<td data-sort="{{strtotime($pp->date_from)}}">{{date("M d, Y", strtotime($pp->date_from))}}</td>
											<td data-sort="{{strtotime($pp->date_to)}}">{{date("M d, Y", strtotime($pp->date_to))}}</td>
										</tr>
										@endforeach
										@endif
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
		<div class="modal-dialog modal-lg" id="Modal_dia" role="document">
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
						{{-- <div class="row">
							<div class="col">
								<div class="form-group">
									<label>Period Code:</label>
									<input type="text" name="period_code" id="period_code" class="form-control" maxlength="8" placeholder="XXX" required>
								</div>
								<div class="form-group">
									<label>Date From:</label>
									<input type="text" name="date_from" id="date_from" class="form-control" placeholder="mm/dd/yyyy" required>
								</div>
								<div class="form-group">
									<label>Date To:</label>
									<input type="text" name="date_to" id="date_to" class="form-control" placeholder="mm/dd/yyyy" required>
								</div>
								<div class="form-group">
									<label>Payroll Type:</label>
									<select class="form-control" name="payroll_type" id="payroll_type" required>
										<option value="S">Special</option>
										<option value="R" selected>Regular</option>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Financial Year: </label>
									<select class="form-control YearSelector" name="fin_year" id="fin_year" required></select>
								</div>
								<div class="form-group">
									<label>Financial Month: </label>
									<select class="form-control MonthSelector" name="fin_month" id="fin_month" required></select>
								</div>
								<div class="form-group">
									<label>Number of work days:</label>
									<input type="number" class="form-control" name="num_workdays" id="num_workdays" min="1" value="1" required>
								</div>
								<div class="form-group">
									<label>Generate 13 month:</label>
									<select class="form-control" name="gen_13m" id="gen_13m" required>
										<option value="N">No</option>
										<option value="Y">Yes</option>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Deduct Witholding Tax:</label>
									<select name="ded_wht" id="ded_wht" class="form-control" required>
										<option value="N">No</option>
										<option value="Y">Yes</option>
									</select>
								</div>
								<div class="form-group">
									<label>Deduct SSS Contribution:</label>
									<select name="ded_sss" id="ded_sss" class="form-control" required>
										<option value="N">No</option>
										<option value="Y">Yes</option>
									</select>
								</div>
								<div class="form-group">
									<label>Deduct Philhealth:</label>
									<select name="ded_philhealth" id="ded_philhealth" class="form-control" required>
										<option value="N">No</option>
										<option value="Y">Yes</option>
									</select>
								</div>
								<div class="form-group">
									<label>Deduct Pag-ibig:</label>
									<select name="ded_pagibig" id="ded_pagibig" class="form-control" required>
										<option value="N">No</option>
										<option value="Y">Yes</option>
									</select>
								</div>
							</div>
						</div> --}}
						<span class="AddMode">
							<div class="row">
								<div class="col"> <!-- Column 1 -->
									<div class="form-group">
										<label>Period Code: <strong style="color:red">*</strong></label>
										<input type="text" name="txt_code" id="period_code" class="form-control R0" maxlength="8" placeholder="XXX" required>
									</div>
									<div class="form-group">
										<label>Financial Year: <strong style="color:red">*</strong></label>
										<select class="form-control R0" name="txt_yr" id="fin_year" required>
											<option value="">Select Year..</option>
											<option value="2019">2019</option><option value="2018">2018</option><option value="2017">2017</option><option value="2016">2016</option><option value="2015">2015</option><option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option>
										</select>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label>Date From: <strong style="color:red">*</strong></label>
										<input type="text" name="txt_dt_fr" onchange="getWorkDays()" id="date_from" class="form-control R0" placeholder="mm/dd/yyyy" required>
									</div>
									<div class="form-group">
										<label>Financial Month: <strong style="color:red">*</strong></label>
										<select class="form-control R0" name="txt_mo" id="fin_month" required>
											<option value="">Select Month..</option>
											<option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option>
										</select>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label>Date To: <strong style="color:red">*</strong></label>
										<input type="text" name="txt_dt_to" onchange="getWorkDays()" id="date_to" class="form-control R0" placeholder="mm/dd/yyyy" required>
									</div>
									<div class="form-group">
										<label>Payroll Type: <strong style="color:red">*</strong></label>
										<select class="form-control R0" name="txt_typ" id="payroll_type" required>
											<option value="R">Regular</option>
											<option value="S">Special</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label>Number of work days:</label>
										<input type="number" style="width: 50%" class="form-control R0" name="txt_work_d" id="num_workdays" min="1" value="1" required>
									</div>
								</div>
								<div class="col">
									<div class="custom-control custom-checkbox mr-sm-2">
								        <input type="checkbox" name="txt_with_tax" class="custom-control-input" id="chkbx001">
								        <label class="custom-control-label" for="chkbx001" >Deduct Witholding Tax</label>
								    </div>
									<div class="custom-control custom-checkbox mr-sm-2">
								        <input type="checkbox" name="txt_philhealth" class="custom-control-input" id="chkbx003">
								        <label class="custom-control-label" for="chkbx003">Deduct Philhealth</label>
								    </div>
								</div>
								<div class="col">
									<div class="custom-control custom-checkbox mr-sm-2">
								        <input type="checkbox" name="txt_sss" class="custom-control-input" id="chkbx002">
								        <label class="custom-control-label" for="chkbx002">GSIS</label>
								    </div>
									<div class="custom-control custom-checkbox mr-sm-2">
								        <input type="checkbox" name="txt_pag_ibig" class="custom-control-input" id="chkbx004">
								        <label class="custom-control-label" for="chkbx004">Deduct Pag-ibig</label>
								    </div>
								    <div class="custom-control custom-checkbox mr-sm-2">
								        <input type="checkbox" name="txt_gen_13_mo" class="custom-control-input" id="chkbx005" onchange="Gen13Chk()">
								        <label class="custom-control-label" for="chkbx005">Generate 13 month</label>
								    </div>
								</div>
							</div>
							<div class="row">
								<div class="col">
								    &nbsp;
								</div>
								<div class="col">
									<div class="form-group Gen13" style="display: none">
										<label>Generate 13th Month Date From:</label>
										<input type="text" name="txt_gen_13_dt_fr" class="form-control R2" placeholder="mm/dd/yyyy">
									</div>
								</div>
								<div class="col">
									<div class="form-group Gen13" style="display: none">
										<label>Generate 13th Month Date To:</label>
										<input type="text" name="txt_gen_13_dt_to" class="form-control R2" placeholder="mm/dd/yyyy">
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode" style="display: none">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Payroll Period list?</p>
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
		$('#date_to').datepicker(date_option5);
		$('input[name="txt_gen_13_dt_fr"]').datepicker(date_option5);
		$('input[name="txt_gen_13_dt_to"]').datepicker(date_option5);
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
		function LoadDatable()
		{
			data.row.add([
				data.pay_code,
				data.date_from,
				data.date_to
			]).draw();
		}
		function Gen13Chk()
		{
			if($('#chkbx005').prop('checked')){
				$('.Gen13').show();
				$('.R2').attr('required', '');

			} else {
				$('.Gen13').hide();
				$('.R2').removeAttr('required');
			}
		}
		function getWorkDays()
		{
			if(($('#date_from').val() != '') && ($('#date_to').val() != '')){
				var x = moment($('#date_from').val());
				var y = moment($('#date_to').val());
				var z = y.diff(x, 'days');
				$('input[name="txt_work_d"]').val(z);
			} else {
				$('input[name="txt_work_d"]').val(0);
			}
		}
		$('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/payroll-period')}}');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('input[name="txt_dt_fr"]').val('');
			$('input[name="txt_dt_to"]').val('');
			$('input[name="txt_work_d"]').val('1');
			$('input[name="txt_with_tax"]').prop('checked', false);
			$('input[name="txt_sss"]').prop('checked', false);
			$('input[name="txt_philhealth"]').prop('checked', false);
			$('input[name="txt_pag_ibig"]').prop('checked', false);
			$('input[name="txt_gen_13_mo"]').prop('checked', false);
			Gen13Chk();
			$('input[name="txt_gen_13_dt_fr"]').val('');
			$('input[name="txt_gen_13_dt_to"]').val('');

			$('.R0').attr('required', '');
			$('#Modal_dia').addClass('modal-lg');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-update').on('click', function() {
			// $('input[name="txt_code"]').val(selected_row.attr('data'));
			if(selected_row.attr('data') != '')
			{
				$('#Modal_dia').addClass('modal-lg');
				$('#frm-pp').attr('action', '{{url('master-file/payroll-period')}}/update');
				$('input[name="txt_code"]').attr('readonly', '');

				$.ajax({
					url : '{{ url('master-file/payroll-period/getOne') }}',
					data : {_token:$('meta[name="csrf-token"]').attr('content'), id : selected_row.attr('data')},
					success : function(data){
						if(data.status == 'OK')
						{
							var d = data.data;
							$('input[name="txt_code"]').val(d.pay_code);
							$('input[name="txt_dt_fr"]').val(d.date_from);
							$('input[name="txt_dt_to"]').val(d.date_to);
							$('input[name="txt_work_d"]').val(d.num_days);
							$('input[name="txt_with_tax"]').prop('checked', (d.d_w_tax == 'Y') ? true : false);
							$('input[name="txt_sss"]').prop('checked', (d.d_sss_c == 'Y') ? true : false);
							$('input[name="txt_philhealth"]').prop('checked', (d.d_philhealth == 'Y') ? true : false);
							$('input[name="txt_pag_ibig"]').prop('checked', (d.d_pagibig == 'Y') ? true : false);
							$('input[name="txt_gen_13_mo"]').prop('checked', (d.gen_13_month == 'Y') ? true : false);
							Gen13Chk();
							$('input[name="txt_gen_13_dt_fr"]').val((d.gen_13_month == 'Y') ? d.gen_13month_from : '');
							$('input[name="txt_gen_13_dt_to"]').val((d.gen_13_month == 'Y') ? d.gen_13month_to : '');
							$('select[name="txt_yr"]').val(d.financial_year);
							$('select[name="txt_mo"]').val(d.month);
						}
					},
					error : function(a, b, c){

					}
				});
				getWorkDays();
				$('.R0').attr('required', '');
				$('.AddMode').show();
				$('.DeleteMode').hide();
				$('#modal-pp').modal('show');
			} 
		});
		$('#opt-delete').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/payroll-period')}}/delete');
			$('#Modal_dia').removeClass('modal-lg');

			$('#TOBEDELETED').text(selected_row.attr('data'));
			$('input[name="txt_code"]').val(selected_row.attr('data'));
			$('.R0').removeAttr('required');
			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		});
	</script>
@endsection