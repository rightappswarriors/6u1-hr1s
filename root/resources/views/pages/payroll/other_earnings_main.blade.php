@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<div class="form-inline">
				<i class="fa fa-building"></i> Other Earnings<br>
					{{-- <div class="form-group mr-2">
						<select class="form-control w-50" name="office" id="office" required>
							<option disabled selected value="">Please select an office</option>
							@if(!empty($data[2]))
							@foreach($data[2] as $off)
							<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
							@endforeach
							@endif
						</select>
						<input type="text" name="date_from" id="date_from" class="form-control" value="SELECT DATE" readonly>
						<select class="form-control MonthSelector ml-3" name="date_month" id="date_month" onchange="" disabled>
							<option value="" disabled selected hidden>MONTH</option>
						</select>
						<select class="form-control YearSelector ml-3" name="date_year" id="date_year" onchange="" disabled>
							<option value="" disabled selected hidden>YEAR</option>
						</select>
						<button class="btn btn-primary ml-3" onclick="toPrint()"><i class="fa fa-print"></i></button>
					</div> --}}
				{{-- <div class="float-right">
					<button class="btn btn-success" onclick="GenerateRata($('#date_from').val())">Generate</button>
				</div> --}}
			</div>
		</div>
		<div class="card-body">

			<ul class="nav nav-tabs mb-3">
				<li class="nav-item active">
			     	<a class="nav-link active" href="#home1" data-toggle="tab">Other Earnings Entry</a>
			  	</li>
			  	<li>
			    	<a class="nav-link" hidden="" href="#menu1" data-toggle="tab">RATA - Representation Allowance (RA) and Transportation Allowance (TA)</a>
			  	</li>
			</ul>
			<div class="tab-content">
				<div id="home1" class="tab-pane fade in active show">
					<div class="table-responsive">
						<div class="row mb-3 mt-1 ml-1">
							<div class="col-3">
								<select class="form-control" name="ofc" id="ofc">
									{{-- <option value="" disabled selected hidden>Please select an office</option> --}}
									@if(count($data[2]) > 0)
										@foreach($data[2] as $office)
										<option value="{{$office->cc_id}}">{{ucwords($office->cc_desc)}}</option>
										@endforeach
									@endif
								</select>
							</div>
							<div class="col-2">
								<select class="form-control MonthSelector ml-3" name="date_month_1" id="date_month_1" onchange="">
									<option value="" disabled selected hidden>MONTH</option>
								</select>
							</div>
							<div class="col-2">
								<select class="form-control YearSelector ml-3" name="date_year_1" id="date_year_1" onchange="">
									{{-- <option value="" disabled selected hidden>YEAR</option> --}}
								</select>
							</div>
							<div class="col-2">
								<select class="form-control ml-3" name="search_period_1" id="search_period_1" required>
									<option value="15">15th Day</option>
									<option value="30">30th Day</option>
								</select>
							</div>
							<div class="col-1">
								<button class="ml-3 btn btn-primary" id="f_find">Find</button>
							</div>
						</div>
						<div class="row mb-3 mt-1 ml-1">
							<div class="col-3">
								<span class="OtherEarnings">
									<button type="button" class="btn btn-success" id="opt-add">
										<i class="fa fa-plus"></i> Add
									</button>
									<button type="button" class="btn btn-info" id="opt-print">
										<i class="fa fa-print"></i> Print List
									</button>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<table class="table table-bordered table-hover" id="dataTable1">
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
											<th>Employee<br>ID</th>
											<th>Employee<br>Name</th>
											<th>Amount</th>
											<th>Period</th>
											<th>Earnings</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
							{{-- <div class="col-3">
								<div class="card">
									<div class="card-body">
										<button type="button" class="btn btn-success btn-block" id="opt-add"><i class="fa fa-plus"></i> Add</button>
										<button type="button" class="btn btn-primary btn-block" id="opt-update"><i class="fa fa-edit"></i> Edit</button>
										<button type="button" class="btn btn-danger btn-block" id="opt-delete"><i class="fa fa-trash"></i> Delete</button>
									</div>
								</div>
							</div> --}}
						</div>	
					</div>
				</div>

				<div id="menu1" class="tab-pane fade in">
					<div class="row">
						<div class="col-3">
							<select class="form-control w-100" name="office" id="office" required>
								<option disabled selected value="">Please select an office</option>
								@if(!empty($data[2]))
								@foreach($data[2] as $off)
								<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
								@endforeach
								@endif
							</select>
						</div>
						<div class="col-2">
							<select class="form-control MonthSelector ml-3" name="date_month" id="date_month" onchange="" disabled>
								<option value="" disabled selected hidden>MONTH</option>
							</select>
						</div>
						<div class="col-2">
							<select class="form-control YearSelector ml-3" name="date_year" id="date_year" onchange="" disabled>
								{{-- <option value="" disabled selected hidden>YEAR</option> --}}
							</select>
						</div>
						<div class="col">
							<button class="btn btn-primary ml-3" onclick="toPrint()"><i class="fa fa-print"></i></button>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dataTable">
							@php
								$count = 0;
							@endphp
							<thead>
								<tr>
									<th rowspan="2">ID</th>
									<th rowspan="2">NAME</th>
									<th rowspan="2">No.</th>
									<th rowspan="2">POSITION</th>
									<th rowspan="2" style="min-width: 150px !important" class="center">MONTHLY <br> SALARY</th>
									<th rowspan="2" style="min-width: 150px !important" class="center">MONTHLY <br> RA</th>
									<th rowspan="2" style="min-width: 150px !important" class="center">MONTHLY <br> TA</th>
									<th rowspan="2">ABSENCE <br> W/O PAY</th>
									<th colspan="3" class="center">DEDUCTION</th>
									<th rowspan="2" style="min-width: 150px !important" class="center">NET <br> AMOUNT <br> REVEICED</th>
									<th rowspan="2" style="min-width: 150px !important" class="center">AMOUNT <br> PAID</th>
								</tr>
								<tr>
									<th rowspan="1" style="min-width: 150px !important">1</th>
									<th rowspan="1" style="min-width: 150px !important">2</th>
									<th rowspan="1" style="min-width: 150px !important">TOTAL DEDUCTION</th>
								</tr>
							</thead>
							<tbody>
								{{-- @isset($data[1])
									@foreach($data[1] as $k => $v)
										<tr>
											<td>{{$v->empid}}</td>
											<td>{{Employee::Name($v->empid)}}</td>
											<td>{{++$count}}</td>
											<td>{{Position::Get_Position($v->positions)}}</td>
											<td>
												@switch($v->rate_type)
													@case('M')
													{{number_format($v->pay_rate, 2)}}
													@break
													@case('D')
													<i>(Employee has daily rate)</i>
												@endswitch
											</td>
											<td>
												<input type="number" class="form-control" name="monthly_ra">
											</td>
											<td>
												<input type="number" class="form-control" name="monthly_ta">
											</td>
											<td>{{$v->empid}}</td>
											<td>{{$v->empid}}</td>
											<td>{{$v->empid}}</td>
											<td>{{$v->empid}}</td>
											<td>{{$v->empid}}</td>
											<td>{{$v->empid}}</td>
										</tr>
									@endforeach
								@endisset --}}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Modal -->
	<div class="modal fade" id="modal-pp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
									<div class="form-group">
										<select class="form-control w-100" name="modalOffice" id="modalOffice" required>
											<option selected value="">Please select an office</option>
											@if(!empty($data[2]))
											@foreach($data[2] as $off)
												<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
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
										<input type="text" class="form-control" hidden name="cbo_employee_id">
									</div>

									<div class="form-group">
										<label>Type of Earning:</label>
										<select name="cbo_type" id="" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@foreach ($data[3] as $k => $v)
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
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Other Earnings list?</p>
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
		var selected_row = null;
		$('#dataTable1').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
		var table = $('#dataTable').DataTable(dataTable_short);
		var table1 = $('#dataTable1').DataTable(dataTable_short);
		
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
				$('#exampleModalLabel').text("New Other Earnings");
				$('#frm-pp').attr('action', '{{url('payroll/other-earnings/add')}}');
				ClearFields();
				OpenModal('.AddMode');
			// }
		});

		$('.nav-link').on('click', function() {
			if($(this).attr('href') == '#home1') {
				$('.OtherEarnings').show();
			} else {
				$('.OtherEarnings').hide();
			}
		});

		// $('#opt-update').on('click', function() {
		function row_update(obj) {
			selected_row = $($(obj).parents()[1]);
			if(selected_row != null) {
				$('#cbo_employee_view')[0].removeAttribute('hidden');
				$('select[name="cbo_employee"]')[0].setAttribute('hidden', '');
				$('#cbo_employee_view').val(selected_row.children()[2].innerText);
				$('#txt_hidden_id').val(selected_row.children()[0].innerText);
				$('select[name="cbo_employee"]')[0].disabled = true;
				$('#exampleModalLabel').text("Update Other Deductions");
				$('#frm-pp').attr('action', '{{url('payroll/other-earnings/')}}/update');

				$.ajax({

					type: 'post',
					url: '{{url('payroll/other-earnings/')}}/find2_e',
					data : {id: selected_row.children()[0].innerText},
					success: function(data) {
						FillFields(data,true);
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
				$('#frm-pp').attr('action', '{{url('payroll/other-earnings/')}}/delete');

				$.ajax({
					type: 'post',
					url: '{{url('payroll/other-earnings/')}}/find2_e',
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

		$('#f_find').on('click', function() {
		// $('#date_month, #date_year, #search_period, #ofc').on('change', function() {
			Find();
		});

		function Find() {
			let find_ofc = $('#ofc').val();
			let find_month = $('#date_month_1').val();
			let find_year = $('#date_year_1').val();
			let find_period = $('#search_period_1').val();

			$.ajax({
				type : 'post',
				url : '{{url('payroll/other-earnings/')}}/find_e',
				data : {month: find_month, year: find_year, period: find_period, ofc: find_ofc},
				// dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="No record found.") {
							table1.clear().draw();
							var d = data;
							for(var i = 0 ; i < d.length; i++) {
								LoadTable(d[0]);
							}
						} else {
							table1.clear().draw();
							alert(data);
						}
					} else {
						alert('Error on loading data.');
					}
				},
			});
		}

		function LoadTable(data) {
			table1.row.add([
				data.id,
				data.emp_no,
				data.emp_name,
				data.amount,
				data.date_from_readable + " to " + data.date_to_readable,
				data.earning_readable,
				'<button type="button" class="btn btn-primary" id="opt-update" onclick="row_update(this)">'+
				'	<i class="fa fa-edit"></i>'+
				'</button>'+
				'<button type="button" class="btn btn-danger" id="opt-delete" onclick="row_delete(this)">'+
				'	<i class="fa fa-trash"></i>'+
				'</button',

			]).draw();
		}

		function FillFields(data,forUpdate = false) {
			forUpdate = (forUpdate ? data.emp_no : '');
			$('[name=cbo_employee_id]').val(forUpdate).trigger('change');
			$('select[name="cbo_employee"]').val(data.emp_no).trigger('change');
			$('select[name="cbo_type"]').val(data.earning_code).trigger('change');
			$('select[name="cbo_period"]').val(data.payroll_period).trigger('change');
			$('select[name="cbo_month"]').val(data.month).trigger('change');
			$('select[name="cbo_year"]').val(data.year).trigger('change');
			$('input[name="txt_amount"]').val(data.amount);
		}

		function ClearFields() {
			$('[name=cbo_employee_id]').val("").trigger('change');
			$('select[name="cbo_employee"]').val("").trigger('change');
			$('select[name="cbo_type"]').val("").trigger('change');
			$('select[name="cbo_period"]').val("15").trigger('change');
			$('select[name="cbo_month"]').val(('{{date('m')}}'.substring(0, 1) == 0 )?'{{date('m')}}'.substring(1, 2):'{{date('m')}}').trigger('change');
			$('select[name="cbo_year"]').val('{{date('Y')}}').trigger('change');
			$('input[name="txt_amount"]').val(1);
		}

		function OpenModal(id) {
			$('.AddMode').hide();
			$('.DeleteMode').hide();

			$(id).show();
			$('#modal-pp').modal('show');
		}
	</script>

	{{-- <script>
		var date_month = $('#date_month').val();
			if(date_month.length < 2) date_month = '0'+date_month;	
		var date_year = $('#date_year').val();
		var date_x = date_month+'-01-'+date_year;
		var rata_exist = false;
		var office = "";

		// LoadRata(date_x);

		// $('#date_from').datepicker(date_option6);
		// $('#date_from').on('change', function() {
		// 	LoadRata($(this).val());
		// });
		$('#office').on('change', function() {
			$('#date_month')[0].disabled = false;
			$('#date_year')[0].disabled = false;
			office = $(this).val();

			LoadRata(date_x);
		});

		$('#date_month').on('change', function() {
			date_month = $('#date_month').val();
			date_year = $('#date_year').val();
			if(date_month.length < 2) date_month = '0'+date_month;
			date_x = date_month+'-01-'+date_year;

			LoadRata(date_x);
		});
		$('#date_year').on('change', function() {
			date_month = $('#date_month').val();
			date_year = $('#date_year').val();
			if(date_month.length < 2) date_month = '0'+date_month;
			date_x = date_month+'-01-'+date_year;

			LoadRata(date_x);
		});

		function LoadRata(date_sent) { // Load if Rata exist
			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/find')}}",
				data: {"date_queried":date_sent, "ofc_id":office},
				// data: {"date_queried":date_x},
				success: function(response) {
					if(response.length > 0) {
						table.clear().draw();
						rata_exist = true;
						for(i=0; i<response.length; i++) {
							FillTable(response[i]);
						}
					} else {
						table.clear().draw();
						rata_exist = false;
						var flag = confirm('No data found. Generate a new RATA?');

						if(flag) { GetCurrentEmployees(); }
					}
				}
			});
		}

		function GenerateRata(data){ // Fill table with new Rata data -- UNUSED --

			table.row.add([
				data.empid,
				data.name,
				data.count,
				data.position_readable,
				(data.rate_type == "M")?data.pay_rate:"<i>(Employee has daily rate)</i>",
				"<span><input class='form-control exclusive-id' type='number' id='"+data.rata_id+"' value='"+data.monthly_ra+"' oninput='MonthlyRA(this)'></span>",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
			]).draw();
		}

		function FillTable(data) { // Fill table if Rata Exist
			// console.log(data);
			table.row.add([
				data.empid,
				data.name,
				data.count,
				data.position_readable,
				"<span><input class='form-control exclusive-monthly-salary "+data.rata_id+"' type='text' readonly disabled value='"+((data.rate_type == "M")?data.pay_rate:"N/A")+"'></span>",
				// (data.rate_type == "M")?data.pay_rate:"<i>(Employee has daily rate)</i>",
				"<span><input class='form-control exclusive-id ex' type='number' value='"+data.monthly_ra+"' oninput='MonthlyRA(this,\""+data.rata_id+"\")'></span>",
				"<span><input class='form-control exclusive-id ex' type='number' value='"+data.monthly_ta+"' oninput='MonthlyTA(this,\""+data.rata_id+"\")'></span>",
				"<span><input class='form-control exclusive-check "+data.rata_id+" ex' type='checkbox' onchange='AbsenceWPay(this,\""+data.rata_id+"\")'></span>",
				"<span><input class='form-control exclusive-id ex' type='number' value='"+data.deduc_1+"' oninput='Deduc1(this,\""+data.rata_id+"\")'></span>",
				"<span><input class='form-control exclusive-id ex' type='number' value='"+data.deduc_2+"' oninput='Deduc2(this,\""+data.rata_id+"\")'></span>",
				"<span><input class='form-control exclusive-id "+data.rata_id+" ex' type='text' readonly disabled value='"+data.total_deduc+"'></span>",
				"<span><input class='form-control exclusive-net "+data.rata_id+" ex' type='text' readonly disabled value='"+data.net_amount_received+"'></span>",
				"<span><input class='form-control exclusive-paid ex' type='number' value='"+data.amount_paid+"' oninput='AmountPaid(this,\""+data.rata_id+"\")'></span>",
			]).draw();

			FillCheckbox(data.rata_id, data.absent_wo_pay);
		}

		function FillCheckbox(rata_id, value) { // Fill checkbox according to its value in the database

			var xtotal_element = $('.'+rata_id);
			for(j=0; j<xtotal_element.length; j++) {
				
				if(xtotal_element[j].classList.contains('exclusive-check')) {
					xtotal_element[j].checked = (value == "true");
				}
			}
		}

		function GetCurrentEmployees() { // Ajax function to get latest employees
			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/employee')}}",
				data: {"ofc_id":office},
				success: function(response) {
					if(response.length > 0) {
						// for(i=0; i<response.length; i++) {
						// 	location.reload();
						// 	// GenerateRata(response[i]);
						// }
						console.log(response);

						$.ajax({
							type: "post",
							url: "{{url('payroll/other-earnings/generate')}}",
							data: {"empid":response, "date":$('#date_from').val(),},
							data: {"empid":response, "date":date_x},
							success: function(response) {
								alert('RATA Generation successful');
								location.reload();
							}
						});
					} else {
						alert('No employees found. Cannot generate RATA.');
					}
				}
			});
		}
	</script> --}}

	<script>
		/* -- The following scripts are used to ajax-cally set and get data to the database -- */
		function MonthlyRA(element, rata_id) {
			if(element.value < 0 || element.value == "") element.value = 0;

			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/monthlyra')}}",
				data: {"rata_id":rata_id, "value":element.value},
				success: function(response) {
					CalculateNetAmountReceived(rata_id);
				},
			});
		}

		function MonthlyTA(element, rata_id) {
			if(element.value < 0 || element.value == "") element.value = 0;

			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/monthlyta')}}",
				data: {"rata_id":rata_id, "value":element.value},
				success: function(response) {
					CalculateNetAmountReceived(rata_id);
				},
			});
		}

		function Deduc1(element, rata_id) {
			if(element.value < 0 || element.value == "") element.value = 0;

			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/deduc1')}}",
				data: {"rata_id":rata_id, "value":element.value},
				success: function(response) {
					CalculateTotalDeduction(rata_id);
				},
			});
		}

		function Deduc2(element, rata_id) {
			if(element.value < 0 || element.value == "") element.value = 0;

			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/deduc2')}}",
				data: {"rata_id":rata_id, "value":element.value},
				success: function(response) {
					CalculateTotalDeduction(rata_id);
				},
			});
		}

		function AmountPaid(element, rata_id) {
			if(element.value < 0 || element.value == "") element.value = 0;

			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/amount-paid')}}",
				data: {"rata_id":rata_id, "value":element.value},
				success: function(response) {
					
				},
			});
		}

		function AbsenceWPay(element, rata_id) {
			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/absence-w-pay')}}",
				data: {"rata_id":rata_id, "value":element.checked},
				success: function(response) {
					
				},
			});
		}

		function CalculateTotalDeduction(rata_id) {
			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/get-total-deduction')}}",
				data: {"rata_id":rata_id},
				success: function(response) {
					var total = parseFloat(response[0].deduc_1) + parseFloat(response[0].deduc_2);
					var total_element = $('.'+response[0].rata_id);

					for(i=0; i<total_element.length; i++) {
						if(total_element[i].classList.contains('exclusive-id')) {
							total_element[i].value = total;
						}
					}
				},
			});
		}

		function CalculateNetAmountReceived(rata_id) {
			$.ajax({
				type: "post",
				url: "{{url('payroll/other-earnings/get-net-amount')}}",
				data: {"rata_id":rata_id},
				success: function(response) {
					var total = parseFloat(response[0].monthly_ra) + parseFloat(response[0].monthly_ta);
					var total_element = $('.'+response[0].rata_id);

					for(i=0; i<total_element.length; i++) {
						if(total_element[i].classList.contains('exclusive-net')) {
							total_element[i].value = total;
						}
					}
				},
			});
		}
	</script>

	<script>
		function toPrint() {
			if(rata_exist)
				window.location = '{{url('payroll/other-earnings/print/')}}/'+date_x;
			else {
				var flag = confirm('No data found. Generate a new RATA?');
				if(flag) { GetCurrentEmployees(); }
			}	
		}
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