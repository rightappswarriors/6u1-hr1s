@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<div class="form-inline">
				<i class="fa fa-building"></i> Other Deductions <br>
					
				{{-- <div class="float-right">
					<button class="btn btn-success" onclick="GenerateRata($('#date_from').val())">Generate</button>
				</div> --}}
			</div>
		</div>

		<div class="card-body">
			<ul class="nav nav-tabs mb-3">
				<li class="nav-item active">
			     	<a class="nav-link active" href="#home1" data-toggle="tab">Other Deductions Entry</a>
			  	</li>
			  	<li>
			    	<a class="nav-link" hidden="" href="#menu1" data-toggle="tab">RATA - Representation Allowance (RA) and Transportation Allowance (TA)</a>
			  	</li>
			</ul>
			<div class="form-group row">
				{{-- <input type="text" name="date_from" id="date_from" class="form-control" value="SELECT DATE" readonly> --}}
				<div class="col-sm-4">
					<select class="form-control" name="ofc" id="ofc">
						{{-- <option value="" disabled selected hidden>Office</option> --}}
						@if(count($data[2]) > 0)
							@foreach($data[2] as $office)
							<option value="{{$office->cc_id}}">{{ucwords($office->cc_desc)}}</option>
							@endforeach
						@endif
					</select>
				</div>
				<div class="col-sm-2">
					<select class="form-control MonthSelector ml-3" name="date_month" id="date_month" onchange="">	
					</select>
				</div>
				<div class="col-sm-2">
					<select class="form-control YearSelector ml-3" name="date_year" id="date_year" onchange="" >
					</select>
				</div>
				<div class="col-sm-2">
					<select class="form-control ml-3" name="search_period" id="search_period" required>
						<option value="15">15th Day</option>
						<option value="30">30th Day</option>
					</select>
				</div>
				<div class="col-sm-2">
					{{-- <button class="ml-3 btn btn-primary mr-1" id="f_find">Find</button> --}}
					{{-- <button class="btn btn-primary ml-3" onclick="toPrint()"><i class="fa fa-print"></i></button> --}}

					<button type="button" class="btn btn-success mr-1" id="opt-add">
						<i class="fa fa-plus"></i> Add
					</button>
					{{-- <button type="button" class="btn btn-info" id="opt-print">
						<i class="fa fa-print"></i> Print List
					</button> --}}
				</div>
			</div>	
				
			
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="dataTable">
									<col>
									<col>
									<col>
									<col>
									<col>
									<col>
									<col width="10%">
									<thead>
										<tr>
											<th class="center">Code</th>
											<th class="center">Employee <br> ID</th>
											<th class="center">Employee <br> Name</th>
											<th class="center">Amount</th>
											<th class="center" width="25%">Period</th>
											<th class="center">Deduction</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										{{-- @foreach ($data[1] as $k => $v)
										<tr>
											<td>{{$v->id}}</td>
											<td>{{$v->emp_no}}</td>
											<td>{{$v->emp_name}}</td>
											<td>{{$v->amount}}</td>
											<td>{{$v->payroll_period}}th Day</td>
											<td>{{OtherDeductions::Get_Name($v->deduction_code)}}</td>
										</tr>
										@endforeach --}}
									</tbody>
								</table>
							</div>	
						</div>
					</div>
				</div>
				{{-- <div class="col-3">
					<div class="card">
						<div class="card-body">
							<i class="fa fa-spinner fa-spin fa-5x" id="frm-spinner"></i>
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
					<h5 class="modal-title" id="exampleModalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-pp" data="#">
						@csrf
						<span class="AddMode">
							<div class="row mb-2">
								<div class="col-6">
									<!-- Column 1 Row 1 -->
									<b>Basic Information</b>
									<div class="dropdown-divider"></div>
									{{-- <div class="form-group">
										<label>Office:</label>
										<select class="form-control">
											<option disabled hidden selected value="">---</option>
											@if(count($data[2]) > 0)
												@foreach($data[2] as $office)
												<option value="{{$office->cc_id}}">{{ucwords($office->cc_desc)}}</option>
												@endforeach
											@endif
										</select>
									</div> --}}
									<div class="form-group" id="form-group-office">
										<label for="">Office:</label>
										<select class="form-control" name="modalOffice" id="modalOffice">
											{{-- <option value="" disabled selected hidden>Office</option> --}}
											@if(count($data[2]) > 0)
												@foreach($data[2] as $office)
												<option value="{{$office->cc_id}}">{{ucwords($office->cc_desc)}}</option>
												@endforeach
											@endif
										</select>
									</div>
									<div class="form-group">
										<label>Employee:</label>
										<input type="text" class="form-control" name="cbo_employee_txt" id="cbo_employee_txt" readonly required hidden disabled>
										<select name="cbo_employee" id="" style="text-transform: uppercase;" class="form-control">
											<option disabled hidden selected value="">---</option>
											{{-- @foreach(Employee::Load_Employees() as $key => $value)
												<option value="{{$value->empid}}">{{$value->lastname}}, {{$value->firstname}} {{$value->mi}}</option>
											@endforeach --}}
										</select>
										<input type="text" class="form-control" hidden id="cbo_employee_view" disabled>
										<input type="text" class="form-control" hidden name="cbo_employee" id="cbo_employee">
									</div>

									<div class="form-group">
										<label>Type of Deduction:</label>
										<select name="cbo_type" id="" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@foreach ($data[0] as $k => $v)
												<option value="{{$v->code}}">{{$v->description}}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-6">
									<!-- Column 2 Row 1 -->
									<b>Deduction Information</b>
									<div class="dropdown-divider"></div>
									<div class="form-group">
										<label>Payment Period:</label>
										<select class="form-control" name="cbo_period" id="payroll_period" onchange="" required>
											<option value="15">15th Day</option>
											<option value="30">30th Day</option>
										</select>
									</div>

									<div class="form-group">
										<label>Payment Date:</label>
										<div class="row">
											<div class="col">
												<select class="form-control MonthSelector" name="cbo_month">
													<option value="" disabled selected hidden>MONTH</option>
												</select>
											</div>
											<div class="col">
												<select class="form-control YearSelector" name="cbo_year">
													{{-- <option value="" disabled selected hidden>YEAR</option> --}}
												</select>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label>Amount:</label>
										<input type="number" name="txt_amount" style="" class="form-control" required step="any">
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<input type="hidden" hidden name="txt_hidden_id" id="txt_hidden_id">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Other Deductions list?</p>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="frm-pp" class="btn btn-success">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="ClearFields()">Close</button>
					</span>
					<span class="DeleteMode">
						<button type="submit" form="frm-pp" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" onclick="ClearFields()">Cancel</button>
					</span>
				</div>
			</div>
		</div>
	</div>
@endsection


@section('to-bottom')
	<script type="text/javascript" src="{{asset('js/for-fixed-tag.js')}}"></script>
	<script>

		var table = $('#dataTable').DataTable(dataTable_short);
		var selected_row = null;
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
			$('#frm-spinner').show();
		});

		$('input[name="txt_amount"]').on('change', function() {
			$(this).val( ($(this).val() < 1)?1: $(this).val());
		});
	</script>

	<script>
		$('#opt-add').on('click', function() {
			// if($('#ofc').val() == "" || $('#ofc').val() == null) {
			// 	$('#ofc').focus().select();
			// 	alert('Please select an office.');
			// } else {
			
			$('#cbo_employee_view')[0].setAttribute('hidden', '');
			$('select[name="cbo_employee"]')[0].removeAttribute('hidden');
			$('#modalOffice').on('change', function(){
				$.ajax({
					type: 'post',
					url: '{{url('timekeeping/timelog-entry/find-emp-office')}}',
					data: {ofc_id: $('select[name="modalOffice"]').val()},
					success: function(data) {
						let select = $('select[name="cbo_employee"]')[0];
							while(select.firstChild) {
								select.removeChild(select.firstChild);
							}

						for(i=0; i<data.length; i++) {
							var opt = document.createElement('option');
								opt.setAttribute('value', data[i].empid);
								opt.innerText = data[i].name;
							select.appendChild(opt);
						}	
					},
				});
			});
				
				
				$('#txt_hidden_id').val("");
				$('select[name="cbo_employee"]')[0].disabled = false;
				$('#exampleModalLabel').text("New Other Deductions");
				$('#frm-pp').attr('action', '{{url('payroll/other-deductions')}}');
				ClearFields();
				OpenModal('.AddMode');
			// }
		});

		// $('#opt-update').on('click', function() {
		function row_update(obj) {
			selected_row = $($(obj).parents()[1]);
			if(selected_row != null) {
				$('#modalOffice')[0].removeAttribute('required');
				$('#form-group-office').attr('hidden', true);
				$('#cbo_employee_view')[0].removeAttribute('hidden');
				$('select[name="cbo_employee"]')[0].setAttribute('hidden', '');
				$('#cbo_employee_view').val(selected_row.children()[2].innerText);
				$('#txt_hidden_id').val(selected_row.children()[0].innerText);
				$('select[name="cbo_employee"]')[0].disabled = true;
				$('#exampleModalLabel').text("Update Other Deductions");
				$('#frm-pp').attr('action', '{{url('payroll/other-deductions/')}}/update');

				$.ajax({
					type: 'post',
					url: '{{url('payroll/other-deductions/')}}/find2',
					data : {id: selected_row.children()[0].innerText},
					success: function(data) {
						FillFields(data);
					},
				});

				OpenModal('.AddMode');
			} else {
				alert('No data selected');
			}
		// });
		}

		// $('#opt-delete').on('click', function() {
		function row_delete(obj) {
			selected_row = $($(obj).parents()[1]);
			if(selected_row != null) {
				$('#txt_hidden_id').val(selected_row.children()[0].innerText);
				$('#exampleModalLabel').text("Delete Other Deductions");
				$('#frm-pp').attr('action', '{{url('payroll/other-deductions/')}}/delete');

				$.ajax({
					type: 'post',
					url: '{{url('payroll/other-deductions/')}}/find2',
					data : {id: selected_row.children()[0].innerText},
					success: function(data) {
						FillFields(data);
					},
				});

				$('#TOBEDELETED').text(""+selected_row.children()[0].innerText+" "+selected_row.children()[2].innerText);

				OpenModal('.DeleteMode');
			} else {
				alert('No data selected');
			}
		// });
		}


		// $('#f_find').on('click', function() {
		// // $('#date_month, #date_year, #search_period, #ofc').on('change', function() {
		// 	Find();
		// });
		
		//ON CHANGE EVENT FIND DATA ALL SELECT2 FIELDS
		
		$('#ofc').on('change', function(){
			Find();
		});

		$('#date_month').on('change', function(){
			Find();
		});

		$('#date_year').on('change', function(){
			Find();
		});

		$('#search_period').on('change', function(){
			Find();
		});

		//END ON CHANGE EVENT


		function Find() {
			let find_ofc = $('#ofc').val();
			let find_month = $('#date_month').val();
			let find_year = $('#date_year').val();
			let find_period = $('#search_period').val();

			$.ajax({
				type : 'post',
				url : '{{url('payroll/other-deductions/')}}/find',
				data : {month: find_month, year: find_year, period: find_period, ofc: find_ofc},
				// dataTy : 'json',
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
				},
			});
		}

		function FillFields(data) {

			$('input[name="cbo_employee"]').val(data.emp_no).trigger('change');
			$('select[name="cbo_type"]').val(data.deduction_code).trigger('change');
			$('select[name="cbo_period"]').val(data.payroll_period).trigger('change');
			$('select[name="cbo_month"]').val(data.month).trigger('change');
			$('select[name="cbo_year"]').val(data.year).trigger('change');
			$('input[name="txt_amount"]').val(data.amount);
		}

		function ClearFields() {
			$('select[name="cbo_employee"]').val("").trigger('change');
			$('select[name="cbo_type"]').val("").trigger('change');
			$('select[name="cbo_period"]').val("15").trigger('change');
			$('select[name="cbo_month"]').val(('{{date('m')}}'.substring(0, 1) == 0 )?'{{date('m')}}'.substring(1, 2):'{{date('m')}}').trigger('change');
			$('select[name="cbo_year"]').val('{{date('Y')}}').trigger('change');
			$('input[name="txt_amount"]').val(1);
		}

		function LoadTable(data) {
			console.log(data);
			table.row.add([
				data.id,
				data.emp_no,
				data.emp_name,
				data.amount,
				data.date_from_readable + " to " + data.date_to_readable,
				data.deduction_readable,
				'<button type="button" class="btn btn-primary" id="opt-update" onclick="row_update(this)">'+
				'	<i class="fa fa-edit"></i>'+
				'</button>'+
				'<button type="button" class="btn btn-danger" id="opt-delete" onclick="row_delete(this)">'+
				'	<i class="fa fa-trash"></i>'+
				'</button',

			]).draw();
		}

		function OpenModal(id) {
			$('.AddMode').hide();
			$('.DeleteMode').hide();

			$(id).show();
			$('#modal-pp').modal('show');
		}

		// $(document).ready(function() {
		// 	Find();
		// });
	</script>

	<style>
		.center {
			text-align: center !important;
		}

		th {
			vertical-align: middle !important;
			text-align: center;
		}
	</style>
@endsection