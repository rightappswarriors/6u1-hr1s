@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-eye"></i> Payroll Register
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="dataTable">
									<thead>
										<th width="10%">Payroll Reg Code</th>
										{{-- <th>Employee</th> --}}
										<th>Employee Name</th>
										{{-- <th>Payroll Code</th> --}}
										<th>Payroll Description</th>
										<th>Department From</th>
										<th>Department Until</th>
										<th>Report Type</th>
									</thead>
									<tbody>
										@isset($payroll_register)
											@if(count($payroll_register) > 0)
												@foreach ($payroll_register as $pr)
													<tr data_id="{{$pr->payrollreg_code}}">
														<th>{{$pr->payrollreg_code}}</th>
														<td>{{$pr->empname}}</td>
														<td>{{$pr->payroll_period_desc}}</td>
														<td>{{$pr->dept_frm_name}}</td>
														<td>{{$pr->dept_To_name}}</td>
														<td>{{$pr->report_type}}</td>
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
@stop

@section('to-modal')
	<!-- Modal -->
	<div class="modal fade" id="modal-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div id="TESTMODAL" class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Payroll Register Entry</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ClearFld();">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-pp" data="#">
						@csrf
						<span class="AddMode">
							<div class="row">
								<div class="col-12">
									<div class="row">
										<div class="col">
											<div class="form-group">
												<label>Code:</label><br>
												<input type="text" class="form-control" name="txt_code" id="" readonly>
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label>Payroll Period: <span style="color:red;font-weight: bolder">*</span></label><br>
												<select name="txt_pay_per" class="form-control RX" required="" onchange="getDesc()">
													@isset($payroll_period)
														@if(count($payroll_period) > 0)
															<option value="">Select Payroll Period..</option>
															@foreach ($payroll_period as $p)
																<option value="{{$p->pay_code}}">{{$p->period}}</option>
															@endforeach
														@else
															<option value="">No Payroll Period registered..</option>
														@endif
													@else
														<option value="">No Payroll Period registered..</option>
													@endisset
												</select>
												<input name="txt_pay_per_desc" type="text" hidden value="">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col">
											<div class="form-group">
												<label>Department Code From: <span style="color:red;font-weight: bolder">*</span></label><br>
												<select name="txt_dept_frm" class="form-control RX" required="">
													@isset($dept)
														@if(count($dept) > 0)
															<option value="">Select Department..</option>
															@foreach ($dept as $d)
																<option value="{{$d->deptid}}">{{$d->dept_name}}</option>
															@endforeach
														@else
															<option value="">No Department registered..</option>
														@endif
													@else
														<option value="">No Department registered..</option>
													@endisset
												</select>
											</div>
											<div class="form-group">
												<label>Until: <span style="color:red;font-weight: bolder">*</span></label><br>
												<select name="txt_dept_until" id="" class="form-control RX" required="">
													@isset($dept)
														@if(count($dept) > 0)
															<option value="">Select Department..</option>
															@foreach ($dept as $d)
																<option value="{{$d->deptid}}">{{$d->dept_name}}</option>
															@endforeach
														@else
															<option value="">No Department registered..</option>
														@endif
													@else
														<option value="">No Department registered..</option>
													@endisset
												</select>
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label>Specific Employee: <span style="color:red;font-weight: bolder">*</span></label><br>
												<select name="txt_spec_emp" id="" class="form-control RX" required="">
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
												<label>Report Type: <span style="color:red;font-weight: bolder">*</span></label><br>
												<select name="txt_rep_typ" class="form-control RX" required="">
													<option value="">Select Report Type..</option>
													<option value="By Department/Section">By Department/Section</option>
													<option value="By Payment Type">By Payment Type</option>
													<option value="By Department/Employee">By Department/Employee</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Payroll Register list?</p>
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
	<!-- Modal -->
@endsection

@section('to-bottom')
	<script type="text/javascript">
		$('#dataTable').DataTable(dataTable_config3);
	</script>
	<script type="text/javascript">
		var selected_row = "";
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
		function getDesc()
		{
			$('input[name="txt_pay_per_desc"]').val($('select[name="txt_pay_per"] option:selected').text());
		}
		$('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('payroll/payroll-register')}}');

			$('input[name="txt_code"]').val('');
			$('select[name="txt_pay_per"]').val('').trigger('change');
			$('select[name="txt_dept_frm"]').val('').trigger('change');
			$('select[name="txt_dept_until"]').val('').trigger('change');
			$('select[name="txt_spec_emp"]').val('').trigger('change');
			$('select[name="txt_rep_typ"]').val('').trigger('change');

			$('.RX').attr('required', '');
			$('#TESTMODAL').addClass('modal-lg');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-view').modal('show');
		});
		$('#opt-update').on('click', function() {
			$('#frm-pp').attr('action', '{{url('payroll/payroll-register')}}/update');
			var selected_data = $(selected_row).attr('data-id');
			if (selected_row!="") {
				$.ajax({
					url : '{{url('payroll/payroll-register')}}/getOne',
					data : {id : selected_data},
					success : function(data) {
						var d = data.data;
						if(data.status == 'OK')
						{
							if(d){
								$('input[name="txt_code"]').val(d.payrollreg_code);
								$('select[name="txt_pay_per"]').val(d.payroll_period_code).trigger('change');
								$('select[name="txt_dept_frm"]').val(d.dept_frm).trigger('change');
								$('select[name="txt_dept_until"]').val(d.dept_until).trigger('change');
								$('select[name="txt_spec_emp"]').val(d.employee).trigger('change');
								$('select[name="txt_rep_typ"]').val(d.report_type).trigger('change');
							}
						}
					},
					error : function(a, b, c){
					}
				});
				$('.RX').attr('required', '');
				$('#TESTMODAL').addClass('modal-lg');
				$('.AddMode').show();
				$('.DeleteMode').hide();
				$('#modal-view').modal('toggle');
			} else {
				NoDataAlert();
			}
		});
		$('#opt-delete').on('click', function(){
			$('#frm-pp').attr('action', '{{url('payroll/payroll-register')}}/delete');

			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			// $('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('#TOBEDELETED').text(selected_row.attr('data_id'));

			$('.RX').removeAttr('required');
			$('#TESTMODAL').removeClass('modal-lg');
			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-view').modal('toggle');
		});
		function NoDataAlert()
		{
			alert("Please select a record.");
		}
	</script>
@stop