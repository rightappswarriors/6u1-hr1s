@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-money" aria-hidden="true"></i> Loan Entry
			<button type="button" class="btn btn-success" id="opt-add">
				<i class="fa fa-plus"></i> Add
			</button>
			<button type="button" class="btn btn-info" id="opt-print">
				<i class="fa fa-print"></i> Print List
			</button>
		</div>
		<div class="card-body">

			<div class="card mb-3">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<form method="post" action="{{url('payroll/loan-entry/find')}}" id="frm-loaddtr">

								<div class="row">
									<div class="col-4">
										<div class="row mb-2">
											<div class="col-3">
												Office: 
											</div>
											<div class="col">
												<select class="form-control w-100" name="office" id="office" required>
													<option disabled selected value="">Please select an office</option>
													@if(!empty($data['offices']))
														@foreach($data['offices'] as $office)
															<option value="{{$office->cc_id}}">{{$office->cc_desc}}</option>
														@endforeach		
													@endif
												</select>
											</div>
										</div>


										<div class="row">
											<div class="col-3">
												<label>Employee:</label>
											</div>
											<div class="col">
												<select class="form-control" name="tito_emp" id="tito_emp" required>
													<option disabled selected value="">---</option>
													<!-- {{-- @if(!empty($data[1]))
													@foreach($data[1] as $emp)
													<option value="{{$emp->empid}}">{{$emp->firstname." ".$emp->lastname}}</option>
													@endforeach
													@endif --}} -->
												</select>
											</div>
										</div>
									</div>

									<div class="col-4">
										<div class="row mb-2">
											<div class="col-3">
												From:
											</div>
											<div class="col">
												<input type="text" name="date_from" id="date_from" class="form-control" value="{{date('Y-m-01')}}" required>
											</div>
										</div>

										<div class="row">
											<div class="col-3">
												To:
											</div>
											<div class="col">
												<input type="text" name="date_to" id="date_to" class="form-control" value="{{date('Y-m-d')}}" required>
											</div>
										</div>
									</div>

									<div class="col">
										<div class="row mb-2">
											<div class="col-4">
												<label>Search by ID:</label>
											</div>
											<div class="col">
												<input type="text" name="tito_id" id="tito_id" class="form-control float-right ml-2" placeholder="Search by ID">
											</div>
											<!-- {{-- <i class="fa fa-spinner fa-spin fa-2x hidden" id="frm-spinner"></i> --}} -->
										</div>
										<div class="row">
											<div class="col-2">
												<button type="button" class="btn btn-primary" id="opt-submit">Go</button>
											</div>
											<!-- <div class="col"> -->
												<!-- {{-- <button type="button" class="btn btn-success" id="opt-add"><i class="fa fa-plus"></i></button>
												<button type="button" class="btn btn-primary" id="opt-update"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-danger" id="opt-delete"><i class="fa fa-trash"></i></button>
												<button type="button" class="btn btn-warning" id="opt-money"><i class="fa fa-money"></i></button>
												<button type="button" class="btn btn-info" id="opt-print"><i class="fa fa-print"></i></button> --}} -->
											<!-- </div> -->
											<div class="col-1" id="loader-conainter">
												<div class="loader-circle"></div>
											</div>
										</div>
									</div>
								</div>

								<!-- {{-- <div class="form-inline">
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
										<input type="text" name="date_from" id="date_from" class="form-control" value="{{date('Y-m-1')}}" required>
									</div>
									<div class="form-group mr-2">
										<label>To:</label>
										<input type="text" name="date_to" id="date_to" class="form-control" value="{{date('Y-m-d')}}" required>
									</div> --}} -->
									<!-- {{-- <i class="fa fa-spinner fa-spin fa-2x hidden" id="frm-spinner"></i> --}} -->
									<!-- {{-- <button type="submit" class="btn btn-primary mr-2">Go</button> --}} -->
									<!-- {{-- <button type="button" class="btn btn-primary" id="btn-print" data="#" onclick="PrintPage(this.getAttribute('data'))"><i class="fa fa-print"></i></button> --}} -->
								</div>
							</form>
						</div>
					</div>		
				</div>
			</div>

			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover table-bordered" id="dataTable">
									<col>
									<col>
									<col>
									<col>
									<col>
									<col>
									<col>
									<col>
									<col>
									<col width="10%">
									<thead>
										<tr>
											<th>Code</th>
											<th>Employee <br>Name</th>
											<th>Transaction <br>Date</th>
											{{-- <th>Location</th>
											<th>Warehouse Location</th> --}}
											<th>Loan <br>Type</th>
											{{-- <th>Cost Center</th> --}}
											<th>Period <br>to Pay</th>
											<th>Amount</th>
											<th>Deduction</th>
											<th>Months to <br> be Paid</th>
											<th>Reason</th>
											{{-- <th>Deduction Date</th> --}}
											<th></th>
										</tr>
									</thead>
									<tbody>
										<!-- {{-- @isset($data)
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
										@endisset --}} -->
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
	<div class="modal fade" id="modal-pp" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
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
						<span class="AddMode EditMode">
							<div class="row mb-2">
								<div class="col-12"> 
									<!-- Column 1 Row 1 -->
									<b>Basic Information</b>
									<div class="dropdown-divider"></div>
									<div class="form-group">
										<label>Employee:</label>
										<input type="text" class="form-control" name="cbo_employee_txt" id="cbo_employee_txt" readonly required>
										<span id="cbo_select">
											<select name="cbo_employee" id="cbo_employee_select" style="text-transform: uppercase;" class="form-control" >
												<option disabled hidden selected value="">---</option>
												<!-- @foreach(Employee::Load_Employees() as $key => $value)
													<option value="{{$value->empid}}">{{$value->lastname}}, {{$value->firstname}} {{$value->mi}}</option>
												@endforeach -->
											</select>
										</span>
									</div>
									<div class="form-group">
										<label>Reason:</label>
										<textarea type="text" name="txt_desc" style="" class="form-control" maxlength="100"></textarea>
									</div>
								</div>

								<div class="col-12">
									<!-- Column 2 Row 1 -->
									<b>Loan Details</b>
									<div class="dropdown-divider"></div>
									<div hidden class="form-group">
										<label>Stock Location:</label>
										<select name="cbo_stocklocation" style="text-transform: uppercase;" class="form-control">
											<option disabled hidden selected value="">---</option>
											@for($i=1;$i<=6;$i++)
												<option value="Stock Location {{$i}}">Stock Location {{$i}}</option>
											@endfor
										</select>
									</div>
									<div class="form-group">
										<label>Loan Type:</label>
										<select name="cbo_contraacct" id="cbo_contraacct" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@foreach(LoanType::Load_LoanTypes() as $key => $value)
												<option value="{{$value->code}}">{{$value->description}}</option>
											@endforeach
											<option value="pagibig">Pag-Ibig</option>
											{{-- <option value="sss">SSS</option> --}}
											<option value="gsis">GSIS</option>
										</select>
									</div>
									<div class="form-group exclusive_sub_hidden" id="pagibig_sub" hidden>
										<label>Pag-Ibig Type:</label>
										<select name="cbo_pagibig_sub" id="cbo_pagibig_sub" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@foreach(Pagibig::Get_All_Sub() as $key => $value)
												<option value="{{$value->id}}">{{$value->description}}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group exclusive_sub_hidden" id="sss_sub" hidden>
										<label>GSIS Type:</label>
										<select name="cbo_sss_sub" id="cbo_sss_sub" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@foreach(SSS::Get_All_Sub() as $key => $value)
												<option value="{{$value->id}}">{{$value->description}}</option>
											@endforeach
										</select>
									</div>
								</div> 
							</div>


							<div class="row">
								<div class="col-6">
									{{-- <div class="form-group">
										<label>Cost Center:</label>
										<select name="cbo_costcenter" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@for($i=1;$i<=6;$i++)
												<option value="Cost Center {{$i}}">Cost Center {{$i}}</option>
											@endfor
										</select>
									</div> --}}
									{{-- <div class="form-group">
										<label>Sub Cost Center:</label>
										<select name="cbo_scc" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@for($i=1;$i<=6;$i++)
												<option value="Sub Cost Center {{$i}}">Sub Cost Center {{$i}}</option>
											@endfor
										</select>
									</div> --}}
									<!-- Column 1 Row 2 -->
									<b>Transaction Details</b>
									<div class="dropdown-divider"></div>
									<div class="form-group">
										<label>Transaction Date:</label>
										<input type="text" name="dtp_trnxdt" class="form-control" id="trans_date" value="{{date('m/d/Y')}}" required readonly>
									</div>
									<div class="form-group" hidden>
										<label>Period to pay:</label>
										<select name="cbo_per_tp" style="text-transform: uppercase;" class="form-control">
											<option value="" selected hidden disabled>---</option>
											<option value="15">15th Day</option>
											<option value="30">30th Day</option>
										</select>
									</div>
									<div class="form-group">
										<label>Amount of loan:</label>
										<input type="number" name="txt_amnt_loan" class="form-control" step="any" required>
									</div>
									
								</div>
								<div class="col-6">
									<!-- Column 2 Row 2 -->
									<b>Summary</b>
									<div class="dropdown-divider"></div>
									<div class="form-group" hidden="">
										<label>Issuance No.:</label>
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX">
									</div>
									<div class="form-group">
										<label>Deduction per month:</label>
										<input type="number" name="txt_deduction" class="form-control" readonly required>
									</div>
									<div class="form-group">
										<label>Months to be paid:</label>
										<input type="number" name="txt_mo_tbp" class="form-control" required>
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
						{{-- <button type="submit" form="frm-pp" class="btn btn-success">Save</button> --}}
						<button type="button" class="btn btn-success" id="add_btn">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="ClearFld()">Close</button>
					</span>
					<span class="EditMode">
						{{-- <button type="submit" form="frm-pp" class="btn btn-success">Save</button> --}}
						<button type="button" class="btn btn-warning" id="edit_btn">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="ClearFld()">Close</button>
					</span>
					<span class="DeleteMode">
						<input type="text" id="obj_holder" hidden>
						<button type="button" id="delete_btn" onclick="DeleteLoanEntry()" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" onclick="ClearFld()">Cancel</button>
					</span>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="/root/resources/assets/js/utils.js"></script>
	<script type="text/javascript">
		// variable declaration
		var $officeSelect = $('#office');
		var $employeeSelect = $('#tito_emp');
		var $loader = $("#loader-conainter");

		$loader.hide();

		$('select[name="cbo_contraacct"]').on('change', function() {
			// let div = $('#pagibig_sub');
			// if($(this).val() == "pagibig") {
			// 	div[0].removeAttribute('hidden');
			// 	div[0].children[1].removeAttribute('disabled');

			// 	div[0].children[1].setAttribute('required', '');
			// } else {
			// 	div[0].setAttribute('hidden', '');
			// 	div[0].children[1].setAttribute('disabled', '');
			// 	div[0].children[1].removeAttribute('required');
			// }

			switch($(this).val()) {
				case "pagibig":
					var divs = $('.exclusive_sub_hidden');
					for(i=0; i<divs.length; i++) {
						divs[i].setAttribute('hidden', '');
						divs[i].children[1].setAttribute('disabled', '');
						divs[i].children[1].removeAttribute('required');
					}

					var div = $('#pagibig_sub');
					div[0].removeAttribute('hidden');
					div[0].children[1].removeAttribute('disabled');

					div[0].children[1].setAttribute('required', '');
					break;

				case "sss":
					var divs = $('.exclusive_sub_hidden');
					for(i=0; i<divs.length; i++) {
						divs[i].setAttribute('hidden', '');
						divs[i].children[1].setAttribute('disabled', '');
						divs[i].children[1].removeAttribute('required');
					}

					var div = $('#sss_sub');
					div[0].removeAttribute('hidden');
					div[0].children[1].removeAttribute('disabled');

					div[0].children[1].setAttribute('required', '');
					break;

				case "gsis":
					var divs = $('.exclusive_sub_hidden');
					for(i=0; i<divs.length; i++) {
						divs[i].setAttribute('hidden', '');
						divs[i].children[1].setAttribute('disabled', '');
						divs[i].children[1].removeAttribute('required');
					}

					var div = $('#sss_sub');
					div[0].removeAttribute('hidden');
					div[0].children[1].removeAttribute('disabled');

					div[0].children[1].setAttribute('required', '');
					break;
				default:
					var divs = $('.exclusive_sub_hidden');
					for(i=0; i<divs.length; i++) {
						divs[i].setAttribute('hidden', '');
						divs[i].children[1].setAttribute('disabled', '');
						divs[i].children[1].removeAttribute('required');
					}
					break;
			}
		});

		$employeeSelect.on('input', function() {
			$('#tito_id').val('');
		});

		$('#tito_id').on('input', function() {
			var id = $(this).val();

			// min length to do a query 3
			if (id.length > 2) {
				$loader.show();

				$.ajax({
					type : 'post',
					url : '{{url('payroll/loan-entry/find-id')}}',
					data : {id: id, date_start:$('#date_from').val(), date_to:$('#date_to').val()},
					success: function(data) {
						table.clear().draw();
						if (data!="error") {
							if (data!="empty") {
								if(data.length > 0) {
									// $('select[name=office]').val(data[0].deptid).trigger('change');
									for(var i = 0 ; i < data.length; i++) {
										LoadTable(data[i]);
									}
								} 	
							} else {
								
							}
						} else {
							
						}

						$loader.hide();
					},
				});
			} else {
				table.clear().draw();
			}
		});

		$('#opt-submit').on('click', function(e) {
			SubmitSearchFrm(e);
		});
	</script>

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

		$employeeSelect.on('change', function() {
			$('input[name="empid"]').val($(this).val());
		});

	</script>

	<script type="text/javascript">
		$('#trans_date').datepicker(date_option3);
		$('#deduc_date').datepicker(date_option2);
		$('#date_from').datepicker(date_option5);
		$('#date_to').datepicker(date_option5);
	</script>

	<script>
		function fillEmployeeSelect(data) {
			if (data.length > 0) {
				for (var i = 0; i < data.length; i++) {
					var firstname = data[i].firstname;
					var lastname = data[i].lastname;
					var mi = data[i].mi;
					var name = firstname + " " + mi + " " + lastname;
					var option = {
						text: name,
						value: data[i].empid,
					};

					Util.appendOption($employeeSelect, option);
				}
			}
		}

		$officeSelect.on('change', function() {
			var officeId = $(this).val();
			var employees = LocalStorage.getEmployees(officeId);
			var option = {
				text: '---',
				value: '',
				disabled: '',
				selected: ''
			};

			// clear employee select
			Util.initSelect($employeeSelect, option);

			// has employees saved on local storage
			if (employees.length > 0) {
				fillEmployeeSelect(employees);
			}

			$.ajax({
				type: 'post',
				url: '{{url('timekeeping/timelog-entry/find-emp-office')}}',
				data: {ofc_id: officeId},
				success: function(data) {
					var prevSelectedEmployee = $employeeSelect.val();

					// clear employee select
					Util.initSelect($employeeSelect, option);

					// update/store employees
					LocalStorage.setEmployees(officeId, data);

					// repopulate employee select
					fillEmployeeSelect(data);

					// reselect previous selected employee
					$employeeSelect.val(prevSelectedEmployee);
				},
			});
		});

		var table = $('#dataTable').DataTable(dataTable_short);

		function ValidateSearchFrm()
		{
			if ($('#date_from').val() != "" && $('#date_from') != null && $('#date_to').val() != "" && $('#date_to') != null) {
				if ($employeeSelect.val() != null) {
					if ($employeeSelect.val() != "") {
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
			$loader.show();

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
					$loader.hide();
				}
			});
		}

		// $('#date_from, #date_to, #tito_emp').on('change', function(e) {
		// 	if (ValidateSearchFrm() == true) {SubmitSearchFrm(e);}
		// });


		function FillFld(data){;
			$('input[name="txt_code"]').val(data.loan_code);
			$('select[name="cbo_employee"]').val(data.employee_no).trigger('change');
			$('textarea[name="txt_desc"]').val(data.loan_desc);
			$('select[name="cbo_contraacct"]').val(data.loan_type).trigger('change');
			// $('select[name="cbo_stocklocation"]').val(data.loan_location).trigger('change');
			$('input[name="dtp_trnxdt"]').val(data.loan_transdate);
			$('select[name="cbo_costcenter"]').val(data.loan_cost_center_code).trigger('change');
			$('select[name="cbo_scc"]').val(data.loan_sub_cost_center).trigger('change');
			$('input[name="txt_mo_tbp"]').val(data.months_to_be_paid);
			$('select[name="cbo_per_tp"]').val(data.period_to_pay).trigger('change');
			$('input[name="txt_amnt_loan"]').val(data.loan_amount);
			$('input[name="txt_deduction"]').val(data.loan_deduction);
			$('input[name="dtp_deduction"]').val(data.deduction_date);
			switch(data.loan_type) {
				case "pagibig": $('select[name="cbo_pagibig_sub"]').val(data.loan_sub_type).trigger('change'); break;
				case "sss": $('select[name="cbo_sss_sub"]').val(data.loan_sub_type).trigger('change'); break;	
				case "gsis": $('select[name="cbo_sss_sub"]').val(data.loan_sub_type).trigger('change'); break;
			}
			

		}

		function ClearFld() {
			$('#btn-print').attr('data', '#');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('select[name="cbo_employee"]').val('').trigger('change');
			$('textarea[name="txt_desc"]').val('');
			$('select[name="cbo_contraacct"]').val('').trigger('change');
			// $('select[name="cbo_stocklocation"]').val('').trigger('change');
			$('input[name="dtp_trnxdt"]').val('{{date('m-d-Y')}}');
			$('select[name="cbo_costcenter"]').val('').trigger('change');
			$('select[name="cbo_scc"]').val('').trigger('change');
			$('select[name="cbo_per_tp"]').val('').trigger('change');
			$('input[name="txt_amnt_loan"]').val(''); 
			$('input[name="txt_mo_tbp"]').val(1);
			$('input[name="txt_deduction"]').val('');
			$('input[name="dtp_deduction"]').val('');
			$('select[name="cbo_pagibig_sub"]').val('');
			$('select[name="cbo_sss_sub"]').val('');
		}

		function removeRequired() {
			$('input[name="txt_code"]').removeAttr('readonly');
			$('[name=txt_desc]').removeAttr('required');
			$('[name=cbo_contraacct]').removeAttr('required');
			$('[name=cbo_per_tp]').removeAttr('required');
			$('[name=txt_amnt_loan]').removeAttr('required');
		}

		function LoadTable(data)
		{
			table.row.add([
				data.loan_code,
				data.emp_name,
				data.loan_transdate,
				data.type_readable,
				data.period_readable,
				data.loan_amount,
				data.loan_deduction,
				data.months_to_be_paid,
				data.loan_desc,
				// data.deduction_date,
				'<button type="button" class="btn btn-primary" id="opt-update" onclick="row_update(this)">'+
				'	<i class="fa fa-edit"></i>'+
				'</button>'+
				'<button type="button" class="btn btn-danger" id="opt-delete" onclick="row_delete(this)">'+
				'	<i class="fa fa-trash"></i>'+
				'</button',
			]).draw();
		}

		function OpenModal(id)
		{
			$('.AddMode').hide();
			$('.DeleteMode').hide();
			$('.EditMode').hide();

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
				var $modalEmployeeSelect = $('#cbo_employee_select');
				var $modalEmployeeTxt = $('#cbo_employee_txt');
				var selectedEmployeeName = $("#tito_emp option:selected").text();

				var option = {
					text: selectedEmployeeName,
					value: $employeeSelect.val(),
					selected: ''
				};

				ClearFld();
				$modalEmployeeSelect.html("");

				$('#exampleModalLabel').text("New Loan Entry");
				$('#frm-pp').attr('action', '{{url('payroll/loan-entry')}}');

				$modalEmployeeTxt.val(selectedEmployeeName);
				Util.appendOption($modalEmployeeSelect, option);
				// $('#cbo_employee').val($employeeSelect.val());
				// $('#dtp_filed, #dtp_lfrm, #dtp_lto').val('{{date('m/d/Y')}}');
				$modalEmployeeTxt.show();
				$modalEmployeeSelect.parent().hide();
				// $("#cbo_employee_select").val($("#tito_emp").val()).trigger('change');
				OpenModal('.AddMode');
			}
		});

		$('#add_btn').on('click', function(){
			add_loan();
		})

		$('#edit_btn').on('click', function(){
			edit_loan();
		})

		function add_loan()
		{
			var data = {
				empid : $('input[name="empid"').val(),
				txt_amnt_loan : $('input[name="txt_amnt_loan"]').val(),
				txt_mo_tbp : $('input[name="txt_mo_tbp"]').val(),
				cbo_contraacct : $('#cbo_contraacct option:selected').val(),
				cbo_pagibig_sub : $('#cbo_pagibig_sub option:selected').val(),
				cbo_sss_sub : $('#cbo_sss_sub option:selected').val(),
				txt_desc : $('textarea[name="txt_desc"]').val(),
				dtp_trnxdt : $('input[name="dtp_trnxdt"]').val(),
				cbo_costcenter : $('#cbo_costcenter option:selected').val(),
				cbo_per_tp : $('#cbo_per_tp option:selected').val(),
			}
			$.ajax({
						
						type: "post",
						url: "{{url('payroll/loan-entry/add')}}",
						data: data,
						success : function(data) {
							ClearFld();
							$('.AddMode').modal('hide');
							$('#modal-pp').modal('hide');
							alert('Successfully added new Loan Entry.');
							returnDataToday(data);
						}
					});
		}

		function edit_loan()
		{
			var data = {
				txt_code : $('input[name="txt_code"]').val(),
				empid : $('input[name="empid"').val(),
				txt_amnt_loan : $('input[name="txt_amnt_loan"]').val(),
				txt_mo_tbp : $('input[name="txt_mo_tbp"]').val(),
				cbo_contraacct : $('#cbo_contraacct option:selected').val(),
				cbo_pagibig_sub : $('#cbo_pagibig_sub option:selected').val(),
				cbo_sss_sub : $('#cbo_sss_sub option:selected').val(),
				txt_desc : $('textarea[name="txt_desc"]').val(),
				dtp_trnxdt : $('input[name="dtp_trnxdt"]').val(),
				cbo_costcenter : $('#cbo_costcenter option:selected').val(),
				cbo_per_tp : $('#cbo_per_tp option:selected').val(),
			}
			$.ajax({
						
						type: "post",
						url: "{{url('payroll/loan-entry/update')}}",
						data: data,
						success : function(data) {
							ClearFld();
							$('.EditMode').modal('hide');
							$('#modal-pp').modal('hide');
							alert('Successfully Modified Loan Entry.');
							returnDataToday(data);
						}
					});
		}
		function returnDataToday(date)
		{
			$('#date_from').val(date);
			$('#date_to').val(date);
			$('#opt-submit').click();
		}
		// $('#opt-update').on('click', function() {
		function row_update(obj) {
			$("#cbo_select").hide();
			$("#cbo_employee_txt").show();
			selected_row = $($(obj).parents()[1]);
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
					$('#cbo_employee').val($employeeSelect.val());
					OpenModal('.EditMode');
				} else {
					NoSelectedRow();
				}
			}
			$('.EditMode').show();
			$('.DeleteMode').hide();
		// });
		}

		// $('#opt-delete').on('click', function() {
		function row_delete(obj) {
			selected_row = $($(obj).parents()[1]);
			if (ValidateSearchFrm()) {
				if (selected_row!=null) {
					ClearFld();
					$('#exampleModalLabel').text("Delete Loan Entry");
					$('[name=txt_code]').val(selected_row.children()[0].innerText);
					$('.EditMode').hide();
					DeleteModal('.DeleteMode',selected_row.children()[0].innerText);				
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
		// });
		}

		function DeleteModal(id,obj){
			$('.AddMode').hide();
			$('.DeleteMode').hide();
			$('#obj_holder').val(obj);
			$(id).show();
			$('#modal-pp').modal('show');
		}

		function DeleteLoanEntry(){
			var obj = $('#obj_holder').val();
			$.ajax({
					type: 'post',
					url: '{{url('payroll/loan-entry/delete')}}',
					data: {code: obj},
					success: function(data) 
					{
						alert(data);
						ClearFld();
						$('.DeleteMode').modal('hide');
						$('#modal-pp').modal('hide');
						$('#opt-submit').click();
					},
				});	
		}
		
	</script>
@endsection