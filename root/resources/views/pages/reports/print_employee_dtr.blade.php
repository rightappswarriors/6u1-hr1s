@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header" id="print_name_hide">
			<div class="form-inline">
				<i class="fa fa-fw fa-clock-o"></i>Print Employee DTR<br>
			</div>
		</div>
		<div class="card-body">
			<div class="form-inline mb-3" id="print_hide">

				<div class="form-group">
					<div class="col-3 mr-3">
						<div class="row">
							<label for="date_month">Office: </label>
						</div>

						<div class="row">
							<select class="form-control mr-3 w-100" name="office" id="office" onchange="">
								<option disabled selected value="">Please select an office</option>
								@if(!empty($data[1]))
									@foreach($data[1] as $off)
										<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
									@endforeach
								<@endif></@endif>
							</select>
						</div>
					</div>
					
							
					<div class="col-2 mr-3">
						<div class="row">
							<label for="employee">Employee: </label>
						</div>

						<div class="row">
							<select name="" id="employee" class="form-control mr-3">
								<option value="" disabled selected>SELECT EMPLOYEE</option>
								{{-- @isset($data[0])
									@foreach($data[0] as $k => $v)
										<option value="{{$v->empid}}">{{Employee::Name($v->empid)}}</option>
									@endforeach
								@endisset --}}
							</select>
						</div>
					</div>

					<div class="col-2 mr-3">
						<div class="row">
							<div class="col-3">
								<label for="date_month">Month: </label>
							</div>
							<div class="col">
								<select class="form-control MonthSelector mr-3 w-100" name="date_month" id="date_month" onchange=""></select>
							</div>
							
						</div>

						<div class="row">
							<div class="col-3">
								<label for="date_year">Year: </label>
							</div>
							<div class="col">
								<select class="form-control YearSelector mr-3 w-100" name="date_year" id="date_year" onchange=""></select>
							</div>
						</div>
					</div>

					<div class="col-2 mr-3">
						<div class="row">
							<label for="payroll_period">Payroll Period: </label>
						</div>

						<div class="row">
							<select class="form-control mr-3 w-100" name="payroll_period" id="payroll_period" onchange="">
								<option value="15">15th Day</option>
								<option value="30">30th Day</option>
							</select>
						</div>
					</div>
					
						
					<button class="btn btn-primary mr-3" id="generate_btn">Find</button>

					<button class="btn btn-primary mr-3" id="print_btn"><i class="fa fa-fw fa-print"></i></button>
				</div>
			</div>
			<div class="table-responsive table-bordered hidden" id="dtr">
				<table class="table table-hover" style="font-size: 13px;">
					<thead>
						<tr>
							<th colspan="10" scope="col" style="text-align: center; width:auto; border: none !important"></th>
						</tr>
					</thead>
					<tbody id="timelogs"></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<style>
		@media print {
			* {
				
			}

			#Header, #Footer { display: none ! important; }
			@page {size: landscape}
	
			#sidebar-parent {
				display: none;
			}

			#print_hide, #print_name_hide {
				display: none;
			}

			.card {
				border: none !important;
			}

			body {
				margin: -8mm -8mm -8mm -8mm;
			}
		}
	</style>

	<script type="text/javascript" src="{{asset('js/for-fixed-tag.js')}}"></script>

	<script>
		var yoarel = {!! json_encode(url('/')) !!} + '/reports/timekeeping/EmployeeDTR/find';
		var date_month = 0;
		var date_year = 0;
		var empid = "";
		var payroll_period = 0;

		$('#generate_btn').on('click', function() {
			$('#date_month').removeAttr('disabled');
			$('#date_year').removeAttr('disabled');
			date_month = $('#date_month').val();
			date_year = $('#date_year').val();
			empid = $('#employee').val();
			payroll_period = $('#payroll_period').val();
			if($('#employee').val() == null)
				alert('Please select an employee.');
			else
				// process();
				process2();
		});

		$('#office').on('change', function() {

			while($('#employee')[0].firstChild) {
				$('#employee')[0].removeChild($('#employee')[0].firstChild);
			}

			var hiddenChild = document.createElement('option');
				hiddenChild.setAttribute('selected', '');
				hiddenChild.setAttribute('disabled', '');
				hiddenChild.setAttribute('value', '');
				hiddenChild.innerText='SELECT EMPLOYEE';

			$('#employee')[0].appendChild(hiddenChild);

			$.ajax({
				type: 'post',
				url: '{{url('timekeeping/timelog-entry/find-emp-office')}}',
				data: {ofc_id: $(this).val()},
				success: function(data) {
					// console.log(typeof(data));
					if(data.length > 0) {
						for(i=0; i<data.length; i++) {
							var option = document.createElement('option');
								option.setAttribute('value', data[i].empid);
								option.innerText=data[i].name;

							$('#employee')[0].appendChild(option);
						}
					}
				},
			});
		});

		function process2() {
			$.ajax({
				type: "post",
				url: yoarel+"2",
				data: {"empid":empid, "month":date_month, "year":date_year, "period":payroll_period} ,
				success: function(response) {
					$('#dtr').removeClass('hidden');

					var divX = document.getElementById('dtr');
						divX.setAttribute('style', 'overflow-x: hidden !important');

					var tbody = document.getElementById('timelogs');

					while(tbody.firstChild) {
						tbody.removeChild(tbody.firstChild);
					}

					var tr1 = document.createElement('tr');
						var td1 = document.createElement('td');
							td1.setAttribute('colspan', '7');
							td1.innerHTML = "Name: <b>"+response[1].Name+"</b>";
						tr1.appendChild(td1);
					var tr2 = document.createElement('tr');
						var td2 = document.createElement('td');
							td2.setAttribute('colspan', '3');
							td2.innerHTML = "For the Month of: <b>"+response[1].Month+"</b>";
						var td3 = document.createElement('td');
							td3.setAttribute('colspan', '1');
						var td4 = document.createElement('td');
							td4.setAttribute('colspan', '3');
							td4.innerHTML = "Year: <b>"+response[1].Year+"</b>";
						tr2.appendChild(td2);
						tr2.appendChild(td3);
						tr2.appendChild(td4);
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
						var td10 = document.createElement('td');
							td10.setAttribute('colspan', '2');
							td10.setAttribute('style', 'text-align: center; font-weight: bold');
							td10.innerHTML = "Undertime";
						tr3.appendChild(td6);
						tr3.appendChild(td7);
						tr3.appendChild(td8);
						tr3.appendChild(td10);
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
						var td19 = document.createElement('td');
							td19.setAttribute('style', 'text-align: center; width: 10%');
							td19.innerHTML = "Hours";
						var td20 = document.createElement('td');
							td20.setAttribute('style', 'text-align: center; width: 10%');
							td20.innerHTML = "Minutes";
						var td21 = document.createElement('td');
						tr4.appendChild(td12);
						tr4.appendChild(td13);
						tr4.appendChild(td14);
						tr4.appendChild(td15);
						tr4.appendChild(td16);
						tr4.appendChild(td19);
						tr4.appendChild(td20);

					tbody.appendChild(tr1);
					tbody.appendChild(tr2);
					tbody.appendChild(tr3);
					tbody.appendChild(tr4);

					for (i = 0; i < response[0].length; i++) {
						var tr = document.createElement('tr');

						var number = document.createElement('td');
							number.innerHTML = response[0][i]['_Date'];

						var in1 = document.createElement('td');
							in1.setAttribute('style', 'text-align: center;');
							in1.innerHTML = formatAMPM2(response[0][i]['AM']['Arrival']);

						var out1 = document.createElement('td');
							out1.setAttribute('style', 'text-align: center;');
							out1.innerHTML = formatAMPM2(response[0][i]['AM']['Departure']);

						var in2 = document.createElement('td');
							in2.setAttribute('style', 'text-align: center;');
							in2.innerHTML = formatAMPM2(response[0][i]['PM']['Arrival']);
							// console.log("1 "+response[0][i]['PM']['Arrival']);
							// console.log("2 "+formatAMPM2(response[0][i]['PM']['Arrival']));

						var out2 = document.createElement('td');
							out2.setAttribute('style', 'text-align: center;');
							out2.innerHTML = formatAMPM2(response[0][i]['PM']['Departure']);

						var undertime_in = document.createElement('td');
							undertime_in.setAttribute('style', 'text-align: center;');
							undertime_in.innerHTML = Math.floor(response[0][i]['_Rendered'] / 60);

						var undertime_out = document.createElement('td');
							undertime_out.setAttribute('style', 'text-align: center;');
							undertime_out.innerHTML = Math.floor(response[0][i]['_Rendered'] % 60);
						tr.appendChild(number);
						tr.appendChild(in1);
						tr.appendChild(out1);
						tr.appendChild(in2);
						tr.appendChild(out2);
						tr.appendChild(undertime_in);
						tr.appendChild(undertime_out);

						tbody.appendChild(tr);
						
					}
				},
			});
		}

		/*DO NOT DELETE*/
		function process() {
			$.ajax({
				type: "post",
				url: yoarel,
				data: {"empid":empid, "month":date_month, "year":date_year, "period":payroll_period} ,
				success: function(response) {
					$('#dtr').removeClass('hidden');

					var divX = document.getElementById('dtr');
						divX.setAttribute('style', 'overflow-x: hidden !important');

					var tbody = document.getElementById('timelogs');

					while(tbody.firstChild) {
						tbody.removeChild(tbody.firstChild);
					}

					var tr1 = document.createElement('tr');
						var td1 = document.createElement('td');
							td1.setAttribute('colspan', '8');
							td1.innerHTML = "Name: <b>"+response[2]+"</b>";
						tr1.appendChild(td1);
					var tr2 = document.createElement('tr');
						var td2 = document.createElement('td');
							td2.setAttribute('colspan', '3');
							td2.innerHTML = "For the Month of: <b>"+response[3]+"</b>";
						var td3 = document.createElement('td');
							td3.setAttribute('colspan', '1');
						var td4 = document.createElement('td');
							td4.setAttribute('colspan', '3');
							td4.innerHTML = "Year: <b>"+response[4]+"</b>";
						var td5 = document.createElement('td');
							td5.setAttribute('colspan', '1');
						tr2.appendChild(td2);
						tr2.appendChild(td3);
						tr2.appendChild(td4);
						tr2.appendChild(td5);
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
						// var td9 = document.createElement('td');
						// 	td9.setAttribute('colspan', '2');
						// 	td9.setAttribute('style', 'text-align: center; font-weight: bold');
						// 	td9.innerHTML = "OT Hours";
						var td10 = document.createElement('td');
							td10.setAttribute('colspan', '2');
							td10.setAttribute('style', 'text-align: center; font-weight: bold');
							td10.innerHTML = "Undertime";
						var td11 = document.createElement('td');
							td11.setAttribute('rowspan', '2');
							td11.setAttribute('style', 'text-align: center; font-weight: bold; vertical-align: middle;');
							td11.innerHTML = "Remarks"
						tr3.appendChild(td6);
						tr3.appendChild(td7);
						tr3.appendChild(td8);
						// tr3.appendChild(td9);
						tr3.appendChild(td10);
						tr3.appendChild(td11);
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
						// var td17 = document.createElement('td');
						// 	td17.setAttribute('style', 'text-align: center; width: 10%');
						// 	td17.innerHTML = "Am";
						// var td18 = document.createElement('td');
						// 	td18.setAttribute('style', 'text-align: center; width: 10%');
						// 	td18.innerHTML = "Pm";
						var td19 = document.createElement('td');
							td19.setAttribute('style', 'text-align: center; width: 10%');
							td19.innerHTML = "Hours";
						var td20 = document.createElement('td');
							td20.setAttribute('style', 'text-align: center; width: 10%');
							td20.innerHTML = "Minutes";
						var td21 = document.createElement('td');
						tr4.appendChild(td12);
						tr4.appendChild(td13);
						tr4.appendChild(td14);
						tr4.appendChild(td15);
						tr4.appendChild(td16);
						// tr4.appendChild(td17);
						// tr4.appendChild(td18);
						tr4.appendChild(td19);
						tr4.appendChild(td20);

					tbody.appendChild(tr1);
					tbody.appendChild(tr2);
					tbody.appendChild(tr3);
					tbody.appendChild(tr4);

					for (i = 0; i < response[8].length; i++) {

						if(response[6][i] == "Saturday" || response[6][i] == "Sunday") {} 
						else {
							var tr = document.createElement('tr');
							var time_log = false;
							var time_log1 = false;

							/**/
							if(response[0][i] == null) {
								time_log = false;
							}
							else {
								time_log = new Date(response[0][i].work_date+" "+response[0][i].time_log);
							}
							if(response[1][i] == null) 
								time_log1 = false;
							else 
								time_log1 = new Date(response[1][i].work_date+" "+response[1][i].time_log);
							/**/

							var number = document.createElement('td');
								number.innerHTML = response[5][i]/*.split('-')[2]*/+"";

							var in1 = document.createElement('td');
								in1.setAttribute('style', 'text-align: center;');
								if(time_log)
									in1.innerHTML = (time_log.getHours() < {{ explode(":",Timelog::ReqTimeOut_2())[0] }} )?formatAMPM(time_log):"";

							var out1 = document.createElement('td');
								out1.setAttribute('style', 'text-align: center;');
								if(time_log1 && time_log)
									out1.innerHTML = (time_log1.getHours() <= {{ explode(":",Timelog::ReqTimeOut_2())[0] }})?formatAMPM(time_log1):(time_log.getHours() < {{ explode(":",Timelog::ReqTimeOut_2())[0] }})?'12:00pm':"";

							var in2 = document.createElement('td');
								in2.setAttribute('style', 'text-align: center;');
								if(time_log)
									in2.innerHTML = (!time_log1 && time_log.getHours() >= {{ explode(":",Timelog::ReqTimeOut_2())[0] }} && time_log.getHours() < 17 )?formatAMPM(time_log):(time_log1)?"1:00pm":"";

							var out2 = document.createElement('td');
								out2.setAttribute('style', 'text-align: center;');
								if(time_log1 && time_log)
									out2.innerHTML = (time_log.getHours() < {{ explode(":",Timelog::ReqTimeOut())[0] }} && time_log1.getHours() > {{ explode(":",Timelog::ReqTimeOut_2())[0] }})?formatAMPM(time_log1):"<span class='text-danger'>missing</span>";
								else if (time_log) out2.innerHTML = "<span class='text-danger'>missing</span>";
								// if(time_log.getHours() < 17) {
								// 	if(response[1][i] == null)
								// 		out2.innerHTML = "";
								// 	else
								// 		out2.innerHTML = formatAMPM(time_log1);
								// }

							/*  */
							// var overtime_in = document.createElement('td');
							// 	overtime_in.setAttribute('style', 'text-align: center;');
							// 	if(time_log)
							// 		overtime_in.innerHTML = (time_log.getHours() >= {{ explode(":",Timelog::ReqTimeOut())[0] }})?formatAMPM(time_log):"";
							// 	// if(time_log.getHours() >= 17)
							// 	// 	overtime_in.innerHTML = formatAMPM(time_log);

							// var overtime_out = document.createElement('td');
							// 	overtime_out.setAttribute('style', 'text-align: center;');
							// 	if(time_log1 && time_log)
							// 		overtime_out.innerHTML = (time_log.getHours() >= {{ explode(":",Timelog::ReqTimeOut())[0] }})?formatAMPM(time_log1):"";
							/*  */


								// if(time_log.getHours() >= 17) {
								// 	if(response[1][i] == null)
								// 		overtime_out.innerHTML = "";
								// 	else
								// 		overtime_out.innerHTML = formatAMPM(time_log1);
								// }

							var signature_in = document.createElement('td');
								signature_in.setAttribute('style', 'text-align: center;');
								if(time_log1 && time_log)
									signature_in.innerHTML = Math.floor((time_log1 - time_log) / 1000 / 60 / 60);
								else if(time_log)
									signature_in.innerHTML = "";
							var signature_out = document.createElement('td');
								signature_out.setAttribute('style', 'text-align: center;');
								if(time_log1 && time_log)
									signature_out.innerHTML = Math.floor((time_log1 - time_log) / 1000 / 60 % 60);
								else if(time_log)
									signature_out.innerHTML = "";
							var remarks = document.createElement('td');
								remarks.setAttribute('style', 'text-align: center;');

							tr.appendChild(number);
							tr.appendChild(in1);
							tr.appendChild(out1);
							tr.appendChild(in2);
							tr.appendChild(out2);
							// tr.appendChild(overtime_in);
							// tr.appendChild(overtime_out);
							tr.appendChild(signature_in);
							tr.appendChild(signature_out);
							tr.appendChild(remarks);

							tbody.appendChild(tr);
						}
					}
				},
			});
		}
		/*DO NOT DELETE*/

		$('#print_btn').on('click', function() {
			window.print();
		});
	</script>

	<script>
		function formatAMPM(date) {
			var hours = date.getHours();
			var minutes = date.getMinutes();
			var ampm = hours >= 12 ? 'pm' : 'am';
			hours = hours % 12;
			hours = hours ? hours : 12; // the hour '0' should be '12'
			minutes = minutes < 10 ? '0'+minutes : minutes;
			var strTime = hours + ':' + minutes + ' ' + ampm;
			return strTime;
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
	</script>
@endsection