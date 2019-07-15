@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-money" aria-hidden="true"></i> Loan Entry
		</div>
		<div class="card-body">

			<div class="card mb-3">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<form method="post" action="{{url('payroll/loan-entry/find')}}" id="frm-loaddtr">
								<div class="form-inline">
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
									<div class="form-group mr-2">
										<label>From:</label>
										<input type="text" name="date_from" id="date_from" class="form-control" value="{{date('m/1/Y')}}" required>
									</div>
									<div class="form-group mr-2">
										<label>To:</label>
										<input type="text" name="date_to" id="date_to" class="form-control" value="{{date('m/d/Y')}}" required>
									</div>
									<i class="fa fa-spinner fa-spin fa-2x hidden" id="frm-spinner"></i>
									{{-- <button type="submit" class="btn btn-primary mr-2">Go</button> --}}
									{{-- <button type="button" class="btn btn-primary" id="btn-print" data="#" onclick="PrintPage(this.getAttribute('data'))"><i class="fa fa-print"></i></button> --}}
								</div>
							</form>
						</div>

						<div class="col-3 text-right">
							<button type="button" class="btn btn-success" id="opt-add"><i class="fa fa-plus"></i></button>
							<button type="button" class="btn btn-primary" id="opt-update"><i class="fa fa-edit"></i></button>
							<button type="button" class="btn btn-danger" id="opt-delete"><i class="fa fa-trash"></i></button>
							{{-- <button type="button" class="btn btn-warning" id="opt-money"><i class="fa fa-money"></i></button> --}}
							<button type="button" class="btn btn-info" id="opt-print"><i class="fa fa-print"></i></button>
						</div>
					</div>		
				</div>
			</div>

			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover" id="dataTable">
									<thead>
										<tr>
											<th>Code</th>
											<th>Employee Name</th>
											<th>Transaction Date</th>
											{{-- <th>Location</th>
											<th>Warehouse Location</th> --}}
											<th>Loan Type</th>
											{{-- <th>Cost Center</th> --}}
											<th>Amount</th>
											<th>Deduction</th>
											<th>Months to <br> be Paid</th>
											<th>Description</th>
											{{-- <th>Deduction Date</th> --}}
										</tr>
									</thead>
									<tbody>
										{{-- @isset($data)
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
														<td>{{$value->loan_code}}</td>
														<td>{{$value->employee_name}}</td>
														<td>{{\Carbon\Carbon::parse($value->loan_transdate)->format('M d, Y')}}</td>
														<td>{{$value->loan_type}}</td>
														<td>{{$value->loan_amount}}</td>
														<td>{{$value->loan_deduction}}</td>
														<td>{{$value->loan_desc}}</td>
														<td>{{\Carbon\Carbon::parse($value->deduction_date)->format('M d, Y')}}</td>
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
				{{-- <div class="col-3">
					<div class="card">
						<div class="card-body">
							<button type="button" class="btn btn-success btn-block" id="opt-add"><i class="fa fa-plus"></i> Add</button>
							<button type="button" class="btn btn-primary btn-block" id="opt-update"><i class="fa fa-edit"></i> Edit</button>
							<button type="button" class="btn btn-danger btn-block" id="opt-delete"><i class="fa fa-trash"></i> Delete</button>
							<button type="button" class="btn btn-info btn-block" id="opt-print"><i class="fa fa-print"></i> Print List</button>
						</div>
					</div>
				</div> --}}
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
						<input type="hidden" class="form-control" name="empid" readonly hidden required>
						<span class="AddMode">
							<div class="row">
								<div class="col"> <!-- Column 1 -->
									<div class="form-group">
										<label>Issuance No.:</label>
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX" required>
									</div>
									<div class="form-group">
										<label>Employee:</label>
										<input type="text" class="form-control" name="cbo_employee_txt" id="cbo_employee_txt" readonly required>
										<select name="cbo_employee" id="" style="text-transform: uppercase;" class="form-control" hidden>
											<option disabled hidden selected value="">---</option>
											@foreach(Employee::Load_Employees() as $key => $value)
												<option value="{{$value->empid}}">{{$value->lastname}}, {{$value->firstname}} {{$value->mi}}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group">
										<label>Description:</label>
										<textarea type="text" name="txt_desc" style="" class="form-control" maxlength="100" required></textarea>
									</div>
									<div class="form-group">
										<label>Loan Type:</label>
										<select name="cbo_contraacct" id="" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@foreach(LoanType::Load_LoanTypes() as $key => $value)
												<option value="{{$value->code}}">{{$value->description}}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group">
										<label>Stock Location:</label>
										<select name="cbo_stocklocation" id="" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@for($i=1;$i<=6;$i++)
												<option value="Stock Location {{$i}}">Stock Location {{$i}}</option>
											@endfor
										</select>
									</div>
									<div class="form-group">
										<label>Transaction Date:</label>
										<input type="text" name="dtp_trnxdt" class="form-control" id="trans_date" value="{{date('m/d/Y')}}" required readonly>
									</div>
									{{-- <div class="form-group">
										<label>Cost Center:</label>
										<select name="cbo_costcenter" id="" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@for($i=1;$i<=6;$i++)
												<option value="Cost Center {{$i}}">Cost Center {{$i}}</option>
											@endfor
										</select>
									</div> --}}
									{{-- <div class="form-group">
										<label>Sub Cost Center:</label>
										<select name="cbo_scc" id="" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@for($i=1;$i<=6;$i++)
												<option value="Sub Cost Center {{$i}}">Sub Cost Center {{$i}}</option>
											@endfor
										</select>
									</div> --}}
									<div class="form-group">
										<label>Amount of loan:</label>
										<input type="number" name="txt_amnt_loan" class="form-control" required>
									</div>
									<div class="form-group">
										<label>Months to be paid:</label>
										<input type="number" name="txt_mo_tbp" class="form-control" required>
									</div>
									<div class="form-group">
										<label>Deduction per month:</label>
										<input type="number" name="txt_deduction" class="form-control" readonly required>
									</div>
									{{-- <div class="form-group">
										<label>Start deduction on:</label>
										<input type="text" name="dtp_deduction" value="{{date('m/d/Y')}}" class="form-control" id="deduc_date" required readonly>
									</div> --}}
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Loan Entry list?</p>
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
	<script type="text/javascript">
		var selected_row = null;
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});

		$('input[name="txt_amnt_loan"]').on('change', function() {
			$(this).val( ($(this).val() < 1)?1: $(this).val());
			$('input[name="txt_deduction"]').val( Math.round( $('input[name="txt_amnt_loan"]').val() / $('input[name="txt_mo_tbp"]').val() * 100 ) / 100);
		});
		$('input[name="txt_mo_tbp"]').on('change', function() {
			$(this).val( ($(this).val() < 1)?1: $(this).val());
			$('input[name="txt_deduction"]').val( Math.round( $('input[name="txt_amnt_loan"]').val() / $('input[name="txt_mo_tbp"]').val() * 100 ) / 100);
		});

		$('#tito_emp').on('change', function() {
			$('input[name="empid"]').val($(this).val());
		});

	</script>

	<script type="text/javascript">
		$('#trans_date').datepicker(date_option2);
		$('#deduc_date').datepicker(date_option2);
		$('#date_from').datepicker(date_option5);
		$('#date_to').datepicker(date_option5);
	</script>

	<script>
		var table = $('#dataTable').DataTable(dataTable_short);

		function ValidateSearchFrm()
		{
			if ($('#date_from').val() != "" && $('#date_from') != null && $('#date_to').val() != "" && $('#date_to') != null) {
				if ($('#tito_emp').val() != null) {
					if ($('#tito_emp').val() != "") {
						return true;
					} else {
						return false;
					}
				} else {
					alert("Please select an employee.");
					return false;
				}
			} else {
				alert("No date selected.");
			}
		}

		function SubmitSearchFrm(e)
		{
			e.preventDefault();
			var frm = $('#frm-loaddtr');
			$('#frm-spinner').show();
			$.ajax({
				type : frm.attr('method'),
				url : frm.attr('action'),
				data : frm.serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="No record found.") {
							table.clear().draw();
							var d = data;
							for(var i = 0 ; i < d.length; i++) {
								
								LoadTable(d[i]);
							}
						} else {
							table.clear().draw();
							alert(data);
						}
					} else {
						alert('Error on loading data.');
					}
					$('#frm-spinner').hide();
				}
			});
		}

		$('#date_from, #date_to, #tito_emp').on('change', function(e) {
			if (ValidateSearchFrm() == true) {SubmitSearchFrm(e);}
		});


		function FillFld(data){ console.log(data);
			$('input[name="txt_code"]').val(data.loan_code);
			$('select[name="cbo_employee"]').val(data.employee_no).trigger('change');
			$('textarea[name="txt_desc"]').val(data.loan_desc);
			$('select[name="cbo_contraacct"]').val(data.loan_type).trigger('change');
			$('select[name="cbo_stocklocation"]').val(data.loan_location).trigger('change');
			$('input[name="dtp_trnxdt"]').val(data.loan_transdate);
			$('select[name="cbo_costcenter"]').val(data.loan_cost_center_code).trigger('change');
			$('select[name="cbo_scc"]').val(data.loan_sub_cost_center).trigger('change');
			$('input[name="txt_mo_tbp"]').val(data.loan_amount);
			$('input[name="txt_amnt_loan"]').val(data.months_to_be_paid);
			$('input[name="txt_deduction"]').val(data.loan_deduction);
			$('input[name="dtp_deduction"]').val(data.deduction_date);
		}

		function ClearFld() {
			$('#btn-print').attr('data', '#');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('select[name="cbo_employee"]').val('').trigger('change');
			$('textarea[name="txt_desc"]').val('');
			$('select[name="cbo_contraacct"]').val('').trigger('change');
			$('select[name="cbo_stocklocation"]').val('').trigger('change');
			$('input[name="dtp_trnxdt"]').val('{{date('m-d-Y')}}');
			$('select[name="cbo_costcenter"]').val('').trigger('change');
			$('select[name="cbo_scc"]').val('').trigger('change');
			$('input[name="txt_amnt_loan"]').val(''); 
			$('input[name="txt_mo_tbp"]').val(1);
			$('input[name="txt_deduction"]').val('');
			$('input[name="dtp_deduction"]').val('');
		}

		function LoadTable(data)
		{
			table.row.add([
				data.loan_code,
				data.emp_name,
				data.loan_transdate,
				data.type_readable,
				data.loan_amount,
				data.loan_deduction,
				data.months_to_be_paid,
				data.loan_desc,
				// data.deduction_date,
			]).draw();
		}

		function OpenModal(id)
		{
			$('.AddMode').hide();
			$('.DeleteMode').hide();

			$(id).show();
			$('#modal-pp').modal('show');
		}
	</script>

	<script type="text/javascript">
		$('#opt-add').on('click', function() {
			// $('#frm-pp').attr('action', '{{url('payroll/loan-entry')}}');

			// $('input[name="txt_code"]').removeAttr('readonly');
			// $('input[name="txt_code"]').val('');
			// $('select[name="cbo_employee"]').val('').trigger('change');
			// $('textarea[name="txt_desc"]').val('');
			// $('select[name="cbo_contraacct"]').val('').trigger('change');
			// $('select[name="cbo_stocklocation"]').val('').trigger('change');
			// $('input[name="dtp_trnxdt"]').val('');
			// $('select[name="cbo_costcenter"]').val('').trigger('change');
			// $('select[name="cbo_scc"]').val('').trigger('change');
			// $('input[name="txt_amnt_loan"]').val('');
			// $('input[name="txt_deduction"]').val('');
			// $('input[name="dtp_deduction"]').val('');
			
			// $('.AddMode').show();
			// $('.DeleteMode').hide();
			// $('#modal-pp').modal('show');
			if (ValidateSearchFrm()) {
				ClearFld();
				$('#exampleModalLabel').text("New Loan Entry");
				$('#frm-pp').attr('action', '{{url('payroll/loan-entry')}}');
				$('#cbo_employee_txt').val($('#tito_emp option:selected').text());
				$('#cbo_employee').val($('#tito_emp').val());
				// $('#dtp_filed, #dtp_lfrm, #dtp_lto').val('{{date('m/d/Y')}}');
				OpenModal('.AddMode');
			}
		});

		$('#opt-update').on('click', function() {
			$('input[name="txt_code"]').attr('readonly', '');
			// $('input[name="txt_code"]').val(selected_row.attr('data_id'));
			// $('input[name="txt_name"]').val(selected_row.attr('data_name'));
			// $('input[name="txt_code"]').val(selected_row.attr('loan_code'));
			// $('select[name="cbo_employee"]').val(selected_row.attr('employee_no')).trigger('change');
			// $('textarea[name="txt_desc"]').val(selected_row.attr('loan_desc'));
			// $('select[name="cbo_contraacct"]').val(selected_row.attr('loan_type')).trigger('change');
			// $('select[name="cbo_stocklocation"]').val(selected_row.attr('loan_location')).trigger('change');
			// $('input[name="dtp_trnxdt"]').val(selected_row.attr('loan_transdate'));
			// $('select[name="cbo_costcenter"]').val(selected_row.attr('loan_cost_center_code')).trigger('change');
			// $('select[name="cbo_scc"]').val(selected_row.attr('loan_sub_cost_center')).trigger('change');
			// $('input[name="txt_amnt_loan"]').val(selected_row.attr('loan_amount'));
			// $('input[name="txt_deduction"]').val(selected_row.attr('loan_deduction'));
			// $('input[name="dtp_deduction"]').val(selected_row.attr('deduction_date'));
			

			if (ValidateSearchFrm()) {
				if (selected_row!=null) {
					$.ajax({
						type : 'get',
						url : '{{url('payroll/loan-entry/get-entry')}}',
						data : {code: selected_row.children()[0].innerText},
						dataTy : 'json',
						success : function(data) {
							if (data!="error") {
								if (data!="No record found.") {
									$('#frm-pp').attr('action', '{{url('payroll/loan-entry/update')}}');
									FillFld(JSON.parse(data));
								} else {
									table.clear().draw();
									alert(data);
								}
							} else {
								alert('Error on loading data.');
							}
							$('#frm-spinner').hide();
						}
					});
					$('#exampleModalLabel').text("Edit Loan Entry");
					$('#frm-pp').attr('action', '#');
					$('#cbo_employee_txt').val($('#tito_emp option:selected').text());
					$('#cbo_employee').val($('#tito_emp').val());
					OpenModal('.AddMode');
				} else {
					NoSelectedRow();
				}
			}

			$('.AddMode').show();
			$('.DeleteMode').hide();
		});

		$('#opt-delete').on('click', function() {

			if (ValidateSearchFrm()) {
				if (selected_row!=null) {
					ClearFld();
					$('#exampleModalLabel').text("Delete Loan Entry");
					$('#txt_code').val(selected_row.children()[0].innerText);
					$('#frm-pp').attr('action', '{{url('payroll/loan-entry/delete')}}');
					OpenModal('.DeleteMode');
				} else {
					NoSelectedRow();
				}
			}

			// $('#frm-pp').attr('action', '{{url('payroll/loan-entry')}}/delete');
		
			// $('input[name="txt_code"]').attr('readonly', '');
			// // $('input[name="txt_code"]').val(selected_row.attr('data_id'));
			// // $('input[name="txt_name"]').val(selected_row.attr('data_name'));

			// $('input[name="txt_code"]').removeAttr('required');
			// $('select[name="cbo_employee"]').removeAttr('required');
			// $('textarea[name="txt_desc"]').removeAttr('required');
			// $('select[name="cbo_contraacct"]').removeAttr('required');
			// $('select[name="cbo_stocklocation"]').removeAttr('required');
			// $('input[name="dtp_trnxdt"]').removeAttr('required');
			// $('select[name="cbo_costcenter"]').removeAttr('required');
			// $('select[name="cbo_scc"]').removeAttr('required');
			// $('input[name="txt_amnt_loan"]').removeAttr('required');
			// $('input[name="txt_deduction"]').removeAttr('required');
			// $('input[name="dtp_deduction"]').removeAttr('required');



			// $('#TOBEDELETED').text(selected_row.attr('employee_name'));

			// $('.AddMode').hide();
			// $('.DeleteMode').show();
			// $('#modal-pp').modal('show');
		});
	</script>
@endsection