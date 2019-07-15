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
						<label for="employee">Employee: </label>
						<select name="" id="employee" class="form-control mr-3">
							<option value="" disabled readonly selected>SELECT EMPLOYEE</option>
							@isset($data[0])
								@foreach($data[0] as $k => $v)
									<option value="{{$v->empid}}">{{Employee::Name($v->empid)}}</option>
								@endforeach
							@endisset
						</select>

						<label for="date_month">Month: </label>
						<select class="form-control MonthSelector mr-3" name="date_month" id="date_month" onchange=""></select>

						<label for="date_year">Year: </label>
						<select class="form-control YearSelector mr-3" name="date_year" id="date_year" onchange=""></select>

						<label for="payroll_period">Payroll Period: </label>
						<select class="form-control mr-3" name="payroll_period" id="payroll_period" onchange="">
							<option value="15">15th Day</option>
							<option value="30">30th Day</option>
						</select>
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
				process();
		});


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
							td1.setAttribute('colspan', '10');
							td1.innerHTML = "Name: <b>"+response[2]+"</b>";
						tr1.appendChild(td1);
					var tr2 = document.createElement('tr');
						var td2 = document.createElement('td');
							td2.setAttribute('colspan', '4');
							td2.innerHTML = "For the Month of: <b>"+response[3]+"</b>";
						var td3 = document.createElement('td');
							td3.setAttribute('colspan', '1');
						var td4 = document.createElement('td');
							td4.setAttribute('colspan', '4');
							td4.innerHTML = "Year: <b>"+response[4]+"</b>";
						var td5 = document.createElement('td');
							td5.setAttribute('colspan', '1');
						tr2.appendChild(td2);
						tr2.appendChild(td3);
						tr2.appendChild(td4);
						tr2.appendChild(td5);
					var tr3 = document.createElement('tr');
						var td6 = document.createElement('td');
							td6.innerHTML = "<b>Day</b>";
						var td7 = document.createElement('td');
							td7.setAttribute('colspan', '2');
							td7.setAttribute('style', 'text-align: center; font-weight: bold');
							td7.innerHTML = "Morning";
						var td8 = document.createElement('td');
							td8.setAttribute('colspan', '2');
							td8.setAttribute('style', 'text-align: center; font-weight: bold');
							td8.innerHTML = "Afternoon";
						var td9 = document.createElement('td');
							td9.setAttribute('colspan', '2');
							td9.setAttribute('style', 'text-align: center; font-weight: bold');
							td9.innerHTML = "OT Hours";
						var td10 = document.createElement('td');
							td10.setAttribute('colspan', '2');
							td10.setAttribute('style', 'text-align: center; font-weight: bold');
							td10.innerHTML = "Signature";
						var td11 = document.createElement('td');
							td11.setAttribute('rowspan', '2');
							td11.setAttribute('style', 'text-align: center; font-weight: bold; vertical-align: middle;');
							td11.innerHTML = "Remarks"
						tr3.appendChild(td6);
						tr3.appendChild(td7);
						tr3.appendChild(td8);
						tr3.appendChild(td9);
						tr3.appendChild(td10);
						tr3.appendChild(td11);
					var tr4 = document.createElement('tr');
						var td12 = document.createElement('td');
						var td13 = document.createElement('td');
							td13.setAttribute('style', 'text-align: center; width: 10%');
							td13.innerHTML = "In";
						var td14 = document.createElement('td');
							td14.setAttribute('style', 'text-align: center; width: 10%');
							td14.innerHTML = "Out";
						var td15 = document.createElement('td');
							td15.setAttribute('style', 'text-align: center; width: 10%');
							td15.innerHTML = "In";
						var td16 = document.createElement('td');
							td16.setAttribute('style', 'text-align: center; width: 10%');
							td16.innerHTML = "Out";
						var td17 = document.createElement('td');
							td17.setAttribute('style', 'text-align: center; width: 10%');
							td17.innerHTML = "Am";
						var td18 = document.createElement('td');
							td18.setAttribute('style', 'text-align: center; width: 10%');
							td18.innerHTML = "Pm";
						var td19 = document.createElement('td');
							td19.setAttribute('style', 'text-align: center; width: 10%');
							td19.innerHTML = "Am";
						var td20 = document.createElement('td');
							td20.setAttribute('style', 'text-align: center; width: 10%');
							td20.innerHTML = "Pm";
						var td21 = document.createElement('td');
						tr4.appendChild(td12);
						tr4.appendChild(td13);
						tr4.appendChild(td14);
						tr4.appendChild(td15);
						tr4.appendChild(td16);
						tr4.appendChild(td17);
						tr4.appendChild(td18);
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
									out1.innerHTML = (time_log1.getHours() < {{ explode(":",Timelog::ReqTimeOut_2())[0] }})?formatAMPM(timelog1):"";

							var in2 = document.createElement('td');
								in2.setAttribute('style', 'text-align: center;');
								if(time_log)
									in2.innerHTML = (time_log.getHours() >= {{ explode(":",Timelog::ReqTimeOut_2())[0] }} && time_log.getHours() < 17 )?formatAMPM(time_log):"";

							var out2 = document.createElement('td');
								out2.setAttribute('style', 'text-align: center;');
								if(time_log1 && time_log)
									out2.innerHTML = (time_log.getHours() < {{ explode(":",Timelog::ReqTimeOut())[0] }})?formatAMPM(time_log1):"";
								// if(time_log.getHours() < 17) {
								// 	if(response[1][i] == null)
								// 		out2.innerHTML = "";
								// 	else
								// 		out2.innerHTML = formatAMPM(time_log1);
								// }

							var overtime_in = document.createElement('td');
								overtime_in.setAttribute('style', 'text-align: center;');
								if(time_log)
									overtime_in.innerHTML = (time_log.getHours() >= {{ explode(":",Timelog::ReqTimeOut())[0] }})?formatAMPM(time_log):"";
								// if(time_log.getHours() >= 17)
								// 	overtime_in.innerHTML = formatAMPM(time_log);

							var overtime_out = document.createElement('td');
								overtime_out.setAttribute('style', 'text-align: center;');
								if(time_log1 && time_log)
									overtime_out.innerHTML = (time_log.getHours() >= {{ explode(":",Timelog::ReqTimeOut())[0] }})?formatAMPM(time_log1):"";
								// if(time_log.getHours() >= 17) {
								// 	if(response[1][i] == null)
								// 		overtime_out.innerHTML = "";
								// 	else
								// 		overtime_out.innerHTML = formatAMPM(time_log1);
								// }

							var signature_in = document.createElement('td');
								signature_in.setAttribute('style', 'text-align: center;');
							var signature_out = document.createElement('td');
								signature_out.setAttribute('style', 'text-align: center;');
							var remarks = document.createElement('td');
								remarks.setAttribute('style', 'text-align: center;');

							tr.appendChild(number);
							tr.appendChild(in1);
							tr.appendChild(out1);
							tr.appendChild(in2);
							tr.appendChild(out2);
							tr.appendChild(overtime_in);
							tr.appendChild(overtime_out);
							tr.appendChild(signature_in);
							tr.appendChild(signature_out);
							tr.appendChild(remarks);

							tbody.appendChild(tr);
						}
					}
				},
			});
		}

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
	</script>
@endsection