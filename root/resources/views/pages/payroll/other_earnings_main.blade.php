@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<div class="form-inline">
				<i class="fa fa-building"></i> Other Earnings - RATA <br>
					<div class="form-group mr-2">
						{{-- <input type="text" name="date_from" id="date_from" class="form-control" value="SELECT DATE" readonly> --}}
						<select class="form-control MonthSelector ml-3" name="date_month" id="date_month" onchange="">
							<option value="" disabled selected hidden>MONTH</option>
						</select>
						<select class="form-control YearSelector ml-3" name="date_year" id="date_year" onchange="">
							{{-- <option value="" disabled selected hidden>YEAR</option> --}}
						</select>
						<button class="btn btn-primary ml-3" onclick="toPrint()"><i class="fa fa-print"></i></button>
					</div>
				{{-- <div class="float-right">
					<button class="btn btn-success" onclick="GenerateRata($('#date_from').val())">Generate</button>
				</div> --}}
			</div>
		</div>
		<div class="card-body">
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
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{asset('js/for-fixed-tag.js')}}"></script>

	<script>
		var table = $('#dataTable').DataTable(dataTable_short);
		var date_month = $('#date_month').val();
			if(date_month.length < 2) date_month = '0'+date_month;	
		var date_year = $('#date_year').val();
		var date_x = date_month+'-01-'+date_year;
		var rata_exist = false;

		LoadRata(date_x);

		// $('#date_from').datepicker(date_option6);
		// $('#date_from').on('change', function() {
		// 	LoadRata($(this).val());
		// });

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
				data: {"date_queried":date_sent,},
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
				data: {"":""},
				success: function(response) {
					if(response.length > 0) {
						for(i=0; i<response.length; i++) {
							location.reload();
							// GenerateRata(response[i]);
						}

						$.ajax({
							type: "post",
							url: "{{url('payroll/other-earnings/generate')}}",
							data: {"empid":response, "date":$('#date_from').val(),},
							data: {"empid":response, "date":date_x},
							success: function(response) {
								alert('RATA Generation successful');
							}
						});
					} else {
						alert('No employees found. Cannot generate RATA.');
					}
				}
			});
		}
	</script>

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