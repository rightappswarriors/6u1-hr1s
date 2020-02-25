@extends('layouts.user')

<style>
	@media print{
		#wholeBody{
			width: 700px!important;
			margin-top: 100px!important;
		}
		.border{
			border:0px solid black!important;
		}

/*		table, thead, tbody, tr, td{
			border: 2px solid black!important;
		}*/
	}
</style>

@section('to-body')
	<div class="card" id="wholeBody">
		<div class="card-header" id="print_name_hide">
			<div class="form-inline">
				<i class="fa fa-fw fa-clock-o"></i>Print Employee DTR<br>
			</div>
		</div>
		<div class="card-body">
			<span id="print_hide">
			<div class="row">
				<div class="col-1">
					Office:
				</div>
				<div class="col-4">
					<select class="form-control w-100" name="office" id="office" onchange="">
						<option disabled selected value="">Please select an office</option>
						@if(!empty($data[1]))
							@foreach($data[1] as $off)
								<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
							@endforeach
						@endif
					</select>
				</div>

				<div class="col-2">
					Generation Type:
				</div>
				<div class="col-4">
					<select class="form-control mr-3" name="generationtype" id="generationtype" onchange="">
						<option value="BASIC">BASIC</option>
						<option value="OVERTIME">OVERTIME</option>
					</select>
				</div>
				<div class="col-1">
					<button class="btn btn-primary mr-3" id="print_btn" onclick="printAll()" disabled><i class="fa fa-fw fa-print"></i></button>
				</div>
			</div>

			<div class="row mt-1">
				<div class="col-1">
					Payroll Period:
				</div>
				<div class="col-4">
					<select class="form-control mr-3" name="payroll_period" id="payroll_period" onchange=""></select>
				</div>

				<div class="col-2">
					Employee:
				</div>
				<div class="col-4">
					<select name="" id="employee" class="form-control mr-3">
						<option value="" disabled selected>SELECT EMPLOYEE</option>
					</select>
				</div>

				<div class="col-1">
					<button class="btn btn-primary mr-3" id="find_btn" disabled><i class="fa fa-fw fa-search"></i></button>
				</div>
			</div>
			</span>
			<!--<div class="form-inline mb-4" id="print_hide">
				<div class="form-group">
					<label for="date_month">Office: </label>
					<select class="form-control mr-3 w-25" name="office" id="office" onchange="">
						<option disabled selected value="">Please select an office</option>
						@if(!empty($data[1]))
							@foreach($data[1] as $off)
								<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
							@endforeach
						@endif
					</select>

					<label for="payroll_period">Payroll Period: </label>
					<select class="form-control mr-3" name="payroll_period" id="payroll_period" onchange="">
						{{-- <option value="15">15th Day</option>
						<option value="30">30th Day</option> --}}

						{{-- @if(!empty($data[0]))
							@foreach($data[0] as $off)
								<option value="{{$off->date_from}}">{{$off->date_from}} to {{$off->date_to}}</option>
							@endforeach
						@else
							<option value="" disabled>No payroll generated</option>
						@endif --}}
					</select>
					<label for="generationtype">Generation Type: </label>
					<select class="form-control mr-3" name="generationtype" id="generationtype" onchange="">
						{{-- <option disabled selected value="">Please select a type</option> --}}
						<option value="BASIC">BASIC</option>
						<option value="OVERTIME">OVERTIME</option>
					</select>
					<button class="btn btn-primary mr-3" id="find_btn" disabled>Find</button>
					<button class="btn btn-primary mr-3" id="print_btn" disabled><i class="fa fa-fw fa-print"></i></button>
				</div>
			</div>-->
			
			<div class="table-responsive hidden mt-3">
				<table class="table table-hover" style="font-size: 13px;" id="table">
					<thead>
						<tr>
							<th>Day</th>
							<th>Arrival (AM)</th>
							<th>Departure (AM)</th>
							<th>Arrival (PM)</th>
							<th>Departure (PM)</th>
							{{-- <th>Undertime</th> --}}
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>

			<div class="table-responsive table-bordered mt-3" id="dtr">
				<table class="table table-hover" style="font-size: 13px;">
					<thead>
						<tr>
							<th colspan="10" scope="col" style="text-align: center; width:auto; border: none !important"></th>
						</tr>
					</thead>
					<tbody id="timelogs"></tbody>
				</table>
			</div>
			<div class="jumbotron" style="background: transparent; border:none;" id="loadAnimation">
				<center><i class="fa fa-spin fa-spinner" style="font-size: 50px;"></i></center>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script>
		var table = $('#table').DataTable();
	</script>

	<script>
		$('#office').on('change', function() {
			$('#employee').find('option').remove().end();
			generateDate();
		});
		$('#generationtype').on('change', function(){
			$('#employee').find('option').remove().end();
			generateDate();
		});
		function generateDate(){

			$('#find_btn')[0].removeAttribute('disabled');

			$.ajax({
				type: 'post',
				url: '{{url('reports/timekeeping/employee-dtr/')}}/getperiods',
				data: {'office':$('#office').val()},
				beforeSend: function(){
				    $('#loadAnimation').show();
				},
				success: function(data) {
					setPeriods(data);
				},
			});

			while($('#employee')[0].firstChild) {
				$('#employee')[0].removeChild($('#employee')[0].firstChild);
			}

			var hiddenChild = document.createElement('option');
				hiddenChild.setAttribute('selected', '');
				hiddenChild.setAttribute('disabled', '');
				hiddenChild.setAttribute('value', '');
				hiddenChild.innerText='SELECT EMPLOYEE';

			$('#employee')[0].appendChild(hiddenChild);
			setTimeout(generateEmployees,500);

		}
		function generateEmployees(){

			$(document).ready(function() {

				var dategenerated = $('#payroll_period').val();
				var date = dategenerated.split('|');
				var from = date[0];
				var to = date[1];
				var gentype = $('#generationtype').val();
				$.ajax({
	             url: '{{url('reports/timekeeping/employee-dtr/getgeneratedemployee')}}/'+gentype+'/'+from+'/'+to,
	             method: 'GET',
	             success : function(data)
	             {
	             	$('#employee option').remove();
	              	if(data.length > 0) {
						for(i=0; i<data.length; i++) {
							var option = document.createElement('option');
								option.setAttribute('value', data[i].empid);
								option.innerText=data[i].lastname;

							$('#employee')[0].appendChild(option);
						}
					} else {
						$("#employee").append(new Option("No Employee Available", ""));
					}
					
				       $('#loadAnimation').hide();		 
	             }
	           });
			});
			
		}
		
			
		$('#generationtype').on('change', function(){
			generateDate();
		});	
	

		$('#find_btn').on('click', function() {

			if($('#employee').val() == "" || $('#employee').val() == null) {
				alert('Please select an employee');
			} else {
				var dategenerated = $('#payroll_period').val();
				var date = dategenerated.split('|');
				var from = date[0];
				var to = date[1];
				$.ajax({
					type: 'post',
					url: '{{url('reports/timekeeping/employee-dtr/')}}/findnew2',
					data: {"code":from, "type":$('#generationtype').val(), "emp":$('#employee').val()},
					success: function(data) {
						console.log(data);
						// table.clear().draw();
						// for(i=0; i<data[0].days_worked_readable.length; i++) {
						// 	// FillTable(data[i]);
						// 	let d1 = data[0].days_worked_readable[i];
						// 	FillTable(d1);
						// }

						if(data.length > 0) {
							MakeDTR(data[0]);
						}
					},
				});
			}
		});



		function MakeDTR(data) {
			$('#print_btn')[0].removeAttribute('disabled');
			var divX = document.getElementById('dtr');
				divX.setAttribute('style', 'overflow-x: hidden !important');

			var tbody = document.getElementById('timelogs');

			while(tbody.firstChild) {
				tbody.removeChild(tbody.firstChild);
			}

			var tr1 = document.createElement('tr');
				var td1 = document.createElement('td');
					td1.setAttribute('colspan', '7');
					td1.innerHTML = "Name: <b>"+data.employee_readable+"</b>";
				tr1.appendChild(td1);
			var tr2 = document.createElement('tr');
				var td2 = document.createElement('td');
					td2.setAttribute('colspan', '7');
					td2.innerHTML = "Payroll Period: <b>"+data.date_from_readable+" to "+data.date_to_readable+"</b>";
				// var td3 = document.createElement('td');
				// 	td3.setAttribute('colspan', '4');
				// var td4 = document.createElement('td');
				// 	td4.setAttribute('colspan', '3');
				// 	td4.innerHTML = "Year: <b>"+response[1].Year+"</b>";
				tr2.appendChild(td2);
				// tr2.appendChild(td3);
				// tr2.appendChild(td4);
			var tr3 = document.createElement('tr');
				var td6 = document.createElement('td');
					td6.setAttribute('style', 'text-align: center; width: 10%');
					td6.innerHTML = "<b>Day</b>";
				var td7 = document.createElement('td');
					td7.setAttribute('colspan', '2');
					td7.setAttribute('style', 'text-align: center; font-weight: bold');
					td7.innerHTML = "A.M.";
				var td8 = document.createElement('td');
					td8.setAttribute('colspan', '2');
					td8.setAttribute('style', 'text-align: center; font-weight: bold');
					td8.innerHTML = "P.M.";
				// var td10 = document.createElement('td');
				// 	td10.setAttribute('colspan', '2');
				// 	td10.setAttribute('style', 'text-align: center; font-weight: bold');
				// 	td10.innerHTML = "Undertime";
				tr3.appendChild(td6);
				tr3.appendChild(td7);
				tr3.appendChild(td8);
				// tr3.appendChild(td10);
			var tr4 = document.createElement('tr');
				var td12 = document.createElement('td');
				var td13 = document.createElement('td');
					td13.setAttribute('style', 'text-align: center; width: 10%');
					td13.innerHTML = "Arrival";
				var td14 = document.createElement('td');
					td14.setAttribute('style', 'text-align: center; width: 10%');
					td14.innerHTML = "Departure";
				var td15 = document.createElement('td');
					td15.setAttribute('style', 'text-align: center; width: 10%');
					td15.innerHTML = "Arrival";
				var td16 = document.createElement('td');
					td16.setAttribute('style', 'text-align: center; width: 10%');
					td16.innerHTML = "Departure";
				// var td19 = document.createElement('td');
				// 	td19.setAttribute('style', 'text-align: center; width: 10%');
				// 	td19.innerHTML = "Hours";
				// var td20 = document.createElement('td');
				// 	td20.setAttribute('style', 'text-align: center; width: 10%');
				// 	td20.innerHTML = "Minutes";
				var td21 = document.createElement('td');

				tr4.appendChild(td12);
				tr4.appendChild(td13);
				tr4.appendChild(td14);
				tr4.appendChild(td15);
				tr4.appendChild(td16);
				// tr4.appendChild(td19);
				// tr4.appendChild(td20);

			var tr5 = document.createElement('tr');
				var td22 = document.createElement('td');
					td22.setAttribute('colspan', '7');
					td22.innerHTML = 'Note:';
			var tr6 = document.createElement('tr');
				var td23 = document.createElement('td');
					td23.setAttribute('colspan', '7');
			var tr7 = document.createElement('tr');
				var td24 = document.createElement('td');
					td24.setAttribute('colspan', '7');
					td24.innerHTML = 'Employer Signature:';
			var tr8 = document.createElement('tr');
				var td25 = document.createElement('td');
					td25.setAttribute('colspan', '7');
					td25.innerHTML = 'Date Sign in:';

				tr5.appendChild(td22);
				tr6.appendChild(td23);
				tr7.appendChild(td24);
				tr8.appendChild(td25);


			tbody.appendChild(tr1);
			tbody.appendChild(tr2);
			tbody.appendChild(tr3);
			tbody.appendChild(tr4);


			for(i=0; i<data.covered_dates.length; i++) {
				var tr = document.createElement('tr');

				var number = document.createElement('td');
					number.innerHTML = data.covered_dates[i][0];

				var in1 = document.createElement('td');
					in1.setAttribute('style', 'text-align: center;');
					in1.innerHTML = "";

				var out1 = document.createElement('td');
					out1.setAttribute('style', 'text-align: center;');
					out1.innerHTML = "";

				var in2 = document.createElement('td');
					in2.setAttribute('style', 'text-align: center;');
					in2.innerHTML = "";

				var out2 = document.createElement('td');
					out2.setAttribute('style', 'text-align: center;');
					out2.innerHTML = "";

				// var undertime_in = document.createElement('td');
				// 	undertime_in.setAttribute('style', 'text-align: center;');
				// 	undertime_in.innerHTML = "";

				// var undertime_out = document.createElement('td');
				// 	undertime_out.setAttribute('style', 'text-align: center;');
				// 	undertime_out.innerHTML = "";

				if(data.days_worked_readable.length > 0) {
					for(j=0; j<data.days_worked_readable.length; j++) {
						if(data.covered_dates[i][1] == data.days_worked_readable[j][0]) {
							in1.innerHTML = formatAMPM2(data.days_worked_readable[j][1][0]);
							out1.innerHTML = '12:00nn';
							in2.innerHTML = '1:00pm';

							if(data.days_worked_readable[j][1].length > 2) {
								out1.innerHTML = formatAMPM2(data.days_worked_readable[j][1][1]);
								in2.innerHTML = formatAMPM2(data.days_worked_readable[j][1][2]);
								out2.innerHTML = formatAMPM2(data.days_worked_readable[j][1][3]);
							} else {
								out2.innerHTML = formatAMPM2(data.days_worked_readable[j][1][1]);
							}
						}
					}
				}

				tr.appendChild(number);
				tr.appendChild(in1);
				tr.appendChild(out1);
				tr.appendChild(in2);
				tr.appendChild(out2);
				// tr.appendChild(undertime_in);
				// tr.appendChild(undertime_out);

				tbody.appendChild(tr);

			}
				tbody.appendChild(tr5);
				tbody.appendChild(tr6);
				tbody.appendChild(tr7);
				tbody.appendChild(tr8);

			// for (i = 0; i < response[0].length; i++) {
			// 	var tr = document.createElement('tr');

			// 	var number = document.createElement('td');
			// 		number.innerHTML = response[0][i]['_Date'];

			// 	var in1 = document.createElement('td');
			// 		in1.setAttribute('style', 'text-align: center;');
			// 		in1.innerHTML = formatAMPM2(response[0][i]['AM']['Arrival']);

			// 	var out1 = document.createElement('td');
			// 		out1.setAttribute('style', 'text-align: center;');
			// 		out1.innerHTML = formatAMPM2(response[0][i]['AM']['Departure']);

			// 	var in2 = document.createElement('td');
			// 		in2.setAttribute('style', 'text-align: center;');
			// 		in2.innerHTML = formatAMPM2(response[0][i]['PM']['Arrival']);
			// 		// console.log("1 "+response[0][i]['PM']['Arrival']);
			// 		// console.log("2 "+formatAMPM2(response[0][i]['PM']['Arrival']));

			// 	var out2 = document.createElement('td');
			// 		out2.setAttribute('style', 'text-align: center;');
			// 		out2.innerHTML = formatAMPM2(response[0][i]['PM']['Departure']);

			// 	var undertime_in = document.createElement('td');
			// 		undertime_in.setAttribute('style', 'text-align: center;');
			// 		undertime_in.innerHTML = Math.floor(response[0][i]['_Rendered'] / 60);

			// 	var undertime_out = document.createElement('td');
			// 		undertime_out.setAttribute('style', 'text-align: center;');
			// 		undertime_out.innerHTML = Math.floor(response[0][i]['_Rendered'] % 60);
			// 	tr.appendChild(number);
			// 	tr.appendChild(in1);
			// 	tr.appendChild(out1);
			// 	tr.appendChild(in2);
			// 	tr.appendChild(out2);
			// 	tr.appendChild(undertime_in);
			// 	tr.appendChild(undertime_out);

			// 	tbody.appendChild(tr);
				
			// }
		}

		function FillTable(data) {

			let amin = "", amout = "", pmin = "", pmout = "";

			if(data[1].length > 2) {
				amin = data[1][0];
				amout = data[1][1];
				pmin = data[1][2];
				pmout = data[1][3];
			} else {
				amin = data[1][0];
				amout = "";
				pmin = "";
				pmout = data[1][1];
			}
			
			table.row.add([
				data[3],
				amin,
				amout,
				pmin,
				pmout,

			]).draw();
		}

		function formatAMPM2(time) {
			if(time=="") return "";
			if(time=="<span class='text-danger'>missing</span>") return "";
			var timeString = time;
			var H = +timeString.substr(0, 2);
			var h = H % 12 || 12;
			var ampm = (H < 12 || H === 24) ? "am" : "pm";
			timeString = h + timeString.substr(2, 3) + ampm;
			return timeString;
		}

		function setPeriods(data) {
			data = Object.keys(data).map(function(key) {
				return [key, data[key]];
			});

			let select = $('#payroll_period');

			while(select[0].firstChild) {
				select[0].removeChild(select[0].firstChild)
			}

			if(data.length > 0) {

				for(i=0; i<data.length; i++) {
					var option = document.createElement('option');
						option.setAttribute('value', data[i][1] + '|' + data[i][0]);
						option.innerText = data[i][1]+' to '+data[i][0];

					select[0].appendChild(option);
				}
			} else {
				var option = document.createElement('option');
					option.setAttribute('value', '');
					option.innerText = 'No payroll generated for this office';

				select[0].appendChild(option);
			}

		}
	
	$('#loadAnimation').hide();
	
	function printAll(){
		
		window.print();
	}

	
	
	</script>
@endsection