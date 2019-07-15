@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-money" aria-hidden="true"></i> Loan History
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card mb-3">
						<form action="{{url('payroll/loan-history/find')}}" method="post" id="frm-dtrhistory">
							<div class="card-body form-inline">
								<div class="form-group mr-2">
									<label>From:</label>
									<input type="text" name="date_from" id="date_from" class="form-control" value="{{date('m/d/Y')}}" required>
								</div>
								<div class="form-group mr-2">
									<label>To:</label>
									<input type="text" name="date_to" id="date_to" class="form-control" value="{{date('m/d/Y')}}" required>
								</div>
								<div class="form-group mr-2">
									<label>Employee:</label>
									<select class="form-control" name="tito_emp" id="tito_emp" required>
										<option disabled selected value="">---</option>
										@if(!empty($data[1]))
											@foreach($data[1] as $emp)
												<option value="{{$emp->empid}}">{{$emp->firstname." ".$emp->lastname}}</option>
											@endforeach
										@endif
									</select>
								</div>	
								<button type="submit" class="btn btn-primary mr-2">Go</button>
								<button type="button" class="btn btn-primary" id="btn-print" data="#" onclick="PrintPage(this.getAttribute('data'))"><i class="fa fa-print"></i></button>						
							</div>
						</form>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover" id="dataTable">
									<thead>
										<tr>
											<th>Code</th>
											<th>Transaction Date</th>
											<th>Employee No</th>
											<th>Employee Name</th>
											<th>Amount</th>
											<th>Deduction Amount</th>
											<th>Description</th>
											<th>Deduction Date</th>
											<th>Location</th>
											<th>Cost Center</th>
											<th>Sub Cost Center</th>
										</tr>
									</thead>
									<tbody>
										{{-- @isset($data[0])
											@if(count($data[0]) > 0)
												@foreach($data[0] as $key => $value)
													<tr loan_code="{{$value->loan_code}}" 
														employee_no="{{$value->employee_no}}"
														employee_name="{{$value->employee_name}}"
														loan_desc="{{$value->loan_desc}}"
														loan_type="{{$value->loan_type}}"
														loan_location="{{$value->loan_location}}"
														loan_transdate="{{$value->loan_transdate}}"
														loan_cost_center_code="{{$value->loan_cost_center_code}}"
														loan_sub_cost_center="{{$value->loan_sub_cost_center}}"
														loan_amount="{{$value->loan_amount}}"
														loan_deduction="{{$value->loan_deduction}}"
														deduction_date="{{$value->deduction_date}}"
														>
														<td>{{$value->employee_no}}</td>
														<td>{{$value->employee_name}}</td>
														<td>{{$value->loan_amount}}</td>
														<td>{{$value->loan_deduction}}</td>
														<td>{{$value->loan_desc}}</td>
														<td>{{\Carbon\Carbon::parse($value->deduction_date)->format('M d, Y')}}</td>
														<td>{{$value->loan_location}}</td>
														<td>{{$value->loan_cost_center_code}}</td>
														<td>{{$value->loan_sub_cost_center}}</td>
													</tr>
												@endforeach
											@endif
										@endisset --}}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-3 hidden">
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

@section('to-bottom')
	{{-- <script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script> --}}

	<script type="text/javascript">
		$('#date_from').datepicker(date_option5);
		$('#date_to').datepicker(date_option5);
	</script>

	<script type="text/javascript">
		var table = $('#dataTable').DataTable();
		function LoadTable(data)
		{
			table.row.add([
				data.loan_code,
				data.loan_transdate,
				data.employee_no,
				data.employee_name,
				data.loan_amount,
				data.loan_deduction,
				data.loan_desc,
				data.deduction_date,
				data.loan_location,
				data.loan_cost_center_code,
				data.loan_sub_cost_center,
			]).draw();
		}

		$('#frm-dtrhistory').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type : $(this).attr('method'),
				url : $(this).attr('action'),
				data : $(this).serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="No record found.") {
							table.clear().draw();
							for(var i = 0 ; i < data.length; i++) {
								LoadTable(data[i]);
							}
							// $('#btn-print').attr('data', '{{url('/timekeeping/employee-dtr/print-dtr')}}?tito_emp='+$('#tito_emp').val()+'&date_from='+$('#date_from').val()+'&date_to='+$('#date_to').val());
						} else {
							table.clear().draw();
							alert(data);
						}
					} else {
						alert('Error on loading data.');
					}
				}
			});
		})
	</script>

	<script type="text/javascript">
		function PrintPage(page_location) {
			$("<iframe>")
	        .hide()
	        .attr("src", page_location)
	        .appendTo("body");   
		}
	</script>
@endsection