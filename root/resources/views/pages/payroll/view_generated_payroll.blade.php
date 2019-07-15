@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-eye"></i> View Generate Payroll
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="dataTable">
									<thead>
										<th>Payroll Code</th>
										<th>Employee Name</th>
										<th>Employee ID</th>
										<th>Payroll Period</th>
									</thead>
									<tbody>
										@if(count($data[0])>0)
										@foreach($data[0] as $d)
										<tr data-id="{{$d->emp_pay_code}}">
											<td>{{$d->emp_pay_code}}</td>
											<td>{{$d->name}}</td>
											<td>{{$d->empid}}</td>
											<td>{{$d->pp}}</td>
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
							{{-- <button type="button" class="btn btn-success btn-block" id="opt-add"><i class="fa fa-plus"></i> Add</button> --}}
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
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Generated Payroll</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ClearFld();">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-pp" data="#">
						<div class="row">
							<div class="col-12">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label>Payroll Code:</label><br>
											<input type="text" class="form-control" name="md-payrollcode" id="md-payrollcode" readonly>
										</div>
										<div class="form-group">
											<label>Payroll Period:</label><br>
											<input type="text" class="form-control" name="md-payrollperiod" id="md-payrollperiod" readonly>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label>Employee Name:</label><br>
											<input type="text" class="form-control" name="md-employeename" id="md-employeename" readonly>
										</div>
										<div class="form-group">
											<label>Rate Type:</label><br>
											<input type="text" class="form-control" name="md-ratetype" id="md-ratetype" readonly>
										</div>
										<div class="form-group">
											<label>Rate:</label><br>
											<div class="input-group">
												<div class="input-group-prepend">
													<div class="input-group-text">{!!Core::currSign()!!}</div>
												</div>
												<input type="text" class="form-control" step=".01" name="md-rate" id="md-rate" readonly value="0.00">
											</div>
										</div>
									</div>
									<div class="col">
										<div class="form-group">
											<label>Daily Rate:</label><br>
											<div class="input-group">
												<div class="input-group-prepend">
													<div class="input-group-text">{!!Core::currSign()!!}</div>
												</div>
												<input type="text" class="form-control" step=".01" name="md-dailyrate" id="md-dailyrate" readonly value="0.00">
											</div>
										</div>
										<div class="form-group">
											<label>Hourly Rate:</label><br>
											<div class="input-group">
												<div class="input-group-prepend">
													<div class="input-group-text">{!!Core::currSign()!!}</div>
												</div>
												<input type="text" class="form-control" step=".01" name="md-hourlyrate" id="md-hourlyrate" readonly value="0.00">
											</div>
										</div>
										<div class="form-group">
											<label>Minutes Rate:</label><br>
											<div class="input-group">
												<div class="input-group-prepend">
													<div class="input-group-text">{!!Core::currSign()!!}</div>
												</div>
												<input type="text" class="form-control" step=".01" name="md-minutesrate" id="md-minutesrate" readonly value="0.00">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col"> <!-- Column 1 -->
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item">
								  		<a class="nav-link active" id="pd-tab" data-toggle="tab" href="#pd" role="tab" aria-controls="pd" aria-selected="true">Payroll Details</a>
									</li>
									<li class="nav-item">
								    	<a class="nav-link" id="gp-tab" data-toggle="tab" href="#gp" role="tab" aria-controls="gp" aria-selected="false">Gross Pay</a>
									</li>
									<li class="nav-item">
								    	<a class="nav-link" id="d-tab" data-toggle="tab" href="#d" role="tab" aria-controls="d" aria-selected="false">Deductions</a>
									</li>
									<li class="nav-item">
								    	<a class="nav-link" id="np-tab" data-toggle="tab" href="#np" role="tab" aria-controls="np" aria-selected="false">Net Pay</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade show active" id="pd" role="tabpanel" aria-labelledby="pd-tab">
										<table class="table table-sm table-borderless">
											<col width="30%">
											<col width="35%">
											<col width="35%">
											<tbody>
												<tr>
													<td class="text-right"><label class="mt-1">Days Worked:</label></td>
													<td>
														<div class="form-group">
															<input type="number" class="form-control" step=".01" name="md-daysworked" id="md-daysworked">
														</div>
													</td>
													<td rowspan="4">
														<div class="form-group">
															<div class="text-right"><label><b>Basic Pay:</b></label></div>
															<div class="input-group">
																<div class="input-group-prepend">
																	<div class="input-group-text">{!!Core::currSign()!!}</div>
																</div>
																<input type="text" class="form-control" step=".01" name="md-basicpay" id="md-basicpay" readonly value="0.00">
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Regular Pay:</label></td>
													<td>
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-prepend">
																	<div class="input-group-text">{!!Core::currSign()!!}</div>
																</div>
																<input type="number" class="form-control" step=".01" name="md-regularpay" id="md-regularpay">
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td></td>
													<td>
														<div class="form-row text-center">
															<div class="col">
																<span>Hour(s)</span>
															</div>
															<div class="col">
																<span>Amount</span>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Absences:</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-absences-a" id="md-absences-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-absences-b" id="md-absences-b">
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Late/Undertime(hours(s)):</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-lu-a" id="md-lu-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-lu-b" id="md-lu-b">
																</div>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="tab-pane fade" id="gp" role="tabpanel" aria-labelledby="gp-tab">
										<table class="table table-sm table-borderless">
											<col width="30%">
											<col width="35%">
											<col width="35%">
											<tbody>
												<tr>
													<td></td>
													<td>
														<div class="form-row text-center">
															<div class="col">
																<span>Hour(s)</span>
															</div>
															<div class="col">
																<span>Amount</span>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Regular OT:</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-regularot-a" id="md-regularot-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-regularot-b" id="md-regularot-b">
																</div>
															</div>
														</div>
													</td>
													<td rowspan="4">
														<div class="form-group">
															<div class="text-right"><label><b>Gross Pay:</b></label></div>
															<div class="input-group">
																<div class="input-group-prepend">
																	<div class="input-group-text">{!!Core::currSign()!!}</div>
																</div>
																<input type="text" class="form-control" step=".01" name="md-grosspay" id="md-grosspay" readonly value="0.00">
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Day Off OT:</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-dayoffot-a" id="md-dayoffot-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-dayoffot-b" id="md-dayoffot-b">
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Legal Holiday OT:</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-legalholidayot-a" id="md-legalholidayot-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-legalholidayot-b" id="md-legalholidayot-b">
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Special Holiday OT:</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-specialholidayot-a" id="md-specialholidayot-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-specialholidayot-b" id="md-specialholidayot-b">
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Legal Holiday Pay:</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-legalholidaypay-a" id="md-legalholidaypay-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-legalholidaypay-b" id="md-legalholidaypay-b">
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Special Holiday Pay:</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-specialholidaypay-a" id="md-specialholidaypay-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-specialholidaypay-b" id="md-specialholidaypay-b">
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td class="text-right"><label class="mt-1">Other Earnings:</label></td>
													<td>
														<div class="form-group form-row">
															<div class="col">
																<input type="number" class="form-control" step=".01" name="md-otherearnings-a" id="md-otherearnings-a">
															</div>
															<div class="col">
																<div class="input-group">
																	<div class="input-group-prepend">
																		<div class="input-group-text">{!!Core::currSign()!!}</div>
																	</div>
																	<input type="number" class="form-control" step=".01" name="md-otherearnings-b" id="md-otherearnings-b">
																</div>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="tab-pane fade" id="d" role="tabpanel" aria-labelledby="d-tab">
										<div class="row mt-3">
											<div class="col-4">
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="SSS Contribution:" style="text-align: right;">
												</div>
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="PhilHealth Cont:" style="text-align: right;">
												</div>
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Pag-ibig Fund:" style="text-align: right;">
												</div>
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Withholding Tax:" style="text-align: right;">
												</div>
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Other Deductions:" style="text-align: right;">
												</div>
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Advances/Loans:" style="text-align: right;">
												</div>
												{{-- <div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Others:" style="text-align: right;">
												</div> --}}
											</div>
											<div class="col-4">
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" name="md-sss" id="md-ssscont" value="0.00">
													</div>
												</div>
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" name="md-philhealth" id="md-philhealthcont" value="0.00">
													</div>
												</div>
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" name="md-pagibig" id="md-pagibigfund" value="0.00">
													</div>
												</div>
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" name="md-withholdingtax" id="md-withholdingtax" value="0.00">
													</div>
												</div>
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" name="md-otherdeductions" id="md-otherdeductions" value="0.00">
														<div class="input-group-prepend">
															<button type="button" class="input-group-text"><i class="fa fa-question-circle"></i></button>
														</div>
													</div>
												</div>
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" name="md-al" id="md-al" value="0.00">
														<div class="input-group-prepend">
															<button type="button" class="input-group-text"><i class="fa fa-question-circle"></i></button>
														</div>
													</div>
												</div>
												{{-- <div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" name="md-others" id="md-others" value="0.00">
														<div class="input-group-prepend">
															<button type="button" class="input-group-text"><i class="fa fa-question-circle"></i></button>
														</div>
													</div>
												</div> --}}
											</div>
											<div class="col-4">
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Total Deductions:" style="text-align: right; font-weight: bold;">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" readonly name="md-sss" id="md-totaldeductions" value="0.00">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="np" role="tabpanel" aria-labelledby="np-tab">
										<div class="row mt-3">
											<div class="col-4">
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Gross Pay:" style="text-align: right;">
												</div>
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Total Deductions:" style="text-align: right;">
												</div>
												<div class="form-group">
													<input type="text" class="form-control-plaintext" readonly value="Net Pay:" style="text-align: right; font-weight: bold;">
												</div>
											</div>
											<div class="col-4">
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" readonly name="md-sss" id="md-grosspay2" value="0.00">
													</div>
												</div>
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" readonly name="md-sss" id="md-totaldeductions2" value="0.00">
													</div>
												</div>
												<div class="form-group form-inline">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text">{!!Core::currSign()!!}</div>
														</div>
														<input type="number" class="form-control" step=".01" readonly name="md-sss" id="md-netpay" value="0.00">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="ClearFld()">Close</button>
					<button type="submit" form="frm-pp" class="btn btn-primary">Save</button>
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

		$('#opt-update').on('click', function() {
			var selected_data = $(selected_row).attr('data-id');
			if (selected_row!="") {
				$('#modal-view').modal('toggle');
				$.ajax({
					type : 'post',
					url : '{{url('payroll/view-generated-payroll/info')}}',
					data : {id : selected_data},
					dataTy : 'json',
					success : function(data) {
						// console.log(data);
						if (data!="error") {
							LoadData(JSON.parse(data));
						} else {
							alert("Failed to load data.");
						}
					}
				});
			} else {
				NoDataAlert();
			}
		});

		function LoadData(data)
		{
			$('#md-payrollcode').val(data.emp_pay_code);
			$('#md-payrollperiod').val(data.ppid);
			$('#md-employeename').val(data.empid);
			$('#md-ratetype').val(data.rateType);
			$('#md-rate').val(data.rate);
			$('#md-dailyrate').val(data.dailyrate);
			$('#md-hourlyrate').val(data.hourlyrate);
			$('#md-minutesrate').val(data.minuterate);

			$('#md-daysworked').val((data.days_worked!=null) ? data.days_worked : 0);
			$('#md-absences-a').val((data.absences_a!=null) ? data.absences_a : 0);
			$('#md-absences-b').val((data.absences_b!=null) ? data.absences_b : 0);
			$('#md-lu-a').val(data.lu_a);
			$('#md-lu-b').val(data.lu_b);
			$('#md-regularpay').val((data.regular_pay!=null) ? data.regular_pay : 0);
			$('#md-basicpay').val((data.basic_pay!=null) ? data.basic_pay : 0);

			$('#md-regularot-a').val((data.reqular_ot_a!=null) ? data.reqular_ot_a : 0);
			$('#md-regularot-b').val((data.reqular_ot_b!=null) ? data.reqular_ot_b : 0);
			$('#md-dayoffot-a').val((data.dayoff_ot_a!=null) ? data.dayoff_ot_a : 0);
			$('#md-dayoffot-b').val((data.dayoff_ot_b!=null) ? data.dayoff_ot_b : 0);
			$('#md-legalholidayot-a').val((data.legal_hol_ot_a!=null) ? data.legal_hol_ot_a : 0);
			$('#md-legalholidayot-b').val((data.legal_hol_ot_b!=null) ? data.legal_hol_ot_b : 0);
			$('#md-specialholidayot-a').val((data.special_hol_ot_a!=null) ? data.special_hol_ot_a : 0);
			$('#md-specialholidayot-b').val((data.special_hol_ot_b!=null) ? data.special_hol_ot_b : 0);
			$('#md-legalholidaypay-a').val((data.legal_hol_ot_a!=null) ? data.legal_hol_ot_a : 0);
			$('#md-legalholidaypay-b').val((data.legal_hol_ot_b!=null) ? data.legal_hol_ot_b : 0);
			$('#md-specialholidaypay-a').val((data.special_hol_ot_a!=null) ? data.special_hol_ot_a : 0);
			$('#md-specialholidaypay-b').val((data.special_hol_ot_b!=null) ? data.special_hol_ot_b : 0);
			$('#md-otherearnings-a').val((data.other_earnings!=null) ? data.other_earnings : 0);
			$('#md-otherearnings-b').val(0);
			$('#md-grosspay').val(data.grosspay);

			$('#md-ssscont').val((data.sss_cont_b!=null) ? data.sss_cont_b : 0);
			$('#md-philhealthcont').val((data.philhealth_cont_b!=null) ? data.philhealth_cont_b : 0);
			$('#md-pagibigfund').val((data.pag_ibig_b!=null) ? data.pag_ibig_b : 0);
			$('#md-withholdingtax').val((data.w_tax!=null) ? data.w_tax : 0);
			$('#md-otherdeductions').val((data.other_deduction!=null) ? data.other_deduction : 0.0);
			$('#md-al').val((data.advances_loans!=null) ? data.advances_loans : 0);
			// $('#md-others').val((data.others!=null) ? data.others : 0);
			$('#md-totaldeductions').val(data.deductions);

			$('#md-grosspay2').val(data.grosspay);
			$('#md-totaldeductions2').val(data.deductions);
			$('#md-netpay').val(data.netpay);
		}

		function ClearFld()
		{
			
		}

		function NoDataAlert()
		{
			alert("Please select a record.");
		}
	</script>
@stop