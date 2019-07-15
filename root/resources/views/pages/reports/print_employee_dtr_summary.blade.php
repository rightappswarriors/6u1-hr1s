@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header" id="print_name_hide">
			<div class="form-inline">
				<i class="fa fa-fw fa-clock-o"></i>Print Employee DTR Summary<br>
			</div>
		</div>
		<div class="card-body">
			<div class="form-inline mb-3" id="print_hide">
					<div class="form-group">
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
		var yoarel = {!! json_encode(url('/')) !!} + '/reports/timekeeping/EmployeeDTRSummary/find';
		var date_month = 0;
		var date_year = 0;
		var payroll_period = 0;

		$('#generate_btn').on('click', function() {
			$('#date_month').removeAttr('disabled');
			$('#date_year').removeAttr('disabled');
			date_month = $('#date_month').val();
			date_year = $('#date_year').val();
			payroll_period = $('#payroll_period').val();
			process();
		});


		function process() {
			$.ajax({
				type: "post",
				url: yoarel,
				data: {"month":date_month, "year":date_year, "period":payroll_period} ,
				success: function(response) {
					$('#dtr').removeClass('hidden');

					var divX = document.getElementById('dtr');
						divX.setAttribute('style', 'overflow-x: hidden !important');

					var tbody = document.getElementById('timelogs');

					while(tbody.firstChild) {
						tbody.removeChild(tbody.firstChild);
					}
					var tr = document.createElement('tr');
						var td = document.createElement('td');
							td.setAttribute('colspan', '2');
							td.setAttribute('style', 'text-align: left;');
							td.innerHTML = "<b>Payroll Period: </b>"+response[3][0]+" to "+response[3][response[3].length-1];
						var tdA = document.createElement('td');
							tdA.setAttribute('colspan', '2');
							tdA.setAttribute('style', 'text-align: left;');
							tdA.innerHTML = "<b>Total Workdays: </b>"+response[3].length+" days";
						var tdB = document.createElement('td');
							tdB.setAttribute('colspan', '3');
							tdB.setAttribute('style', 'text-align: left;');
							tdB.innerHTML = "<b>Total Working Hours: </b>"+hoursToTime(response[7]);

						tr.appendChild(td);
						tr.appendChild(tdA);
						tr.appendChild(tdB);

					var tr1 = document.createElement('tr');
						var td1 = document.createElement('td');
							td1.setAttribute('style', 'text-align: center; font-weight: bold; width: 10%');
							td1.innerHTML = "Employee";
						var td2 = document.createElement('td');
							td2.setAttribute('style', 'text-align: center; font-weight: bold; width: 10%');
							td2.innerHTML = "Days Worked";
						var td3 = document.createElement('td');
							td3.setAttribute('style', 'text-align: center; font-weight: bold; width: 10%');
							td3.innerHTML = "Absences";
						var td4 = document.createElement('td');
							td4.setAttribute('style', 'text-align: center; font-weight: bold; width: 10%');
							td4.innerHTML = "Late";
						var td5 = document.createElement('td');
							td5.setAttribute('style', 'text-align: center; font-weight: bold; vertical-align: middle; width: 10%');
							td5.innerHTML = "Undertime"
						var td6 = document.createElement('td');
							td6.setAttribute('style', 'text-align: center; font-weight: bold; vertical-align: middle; width: 10%');
							td6.innerHTML = "Total Overtime"
						var td6a = document.createElement('td');
							td6a.setAttribute('style', 'text-align: center; font-weight: bold; vertical-align: middle; width: 10%');
							td6a.innerHTML = "Total Hours rendered";

						tr1.appendChild(td1);
						tr1.appendChild(td2);
						tr1.appendChild(td3);
						tr1.appendChild(td4);
						tr1.appendChild(td5);
						tr1.appendChild(td6);
						tr1.appendChild(td6a);

					tbody.appendChild(tr);
					tbody.appendChild(tr1);

					for (i = 0; i < response[0].length; i++) {
						var tr2 = document.createElement('tr');

							var late = new Date(response[3][i]+" "+response[6][1][i]);
							var undertime = new Date(response[3][i]+" "+response[6][2][i]);
							var overtime = new Date(response[3][i]+" "+response[6][3][i]);
							var totalhours = response[7] - ( getHoursFromTime(late) + getHoursFromTime(undertime) );
								totalhours = totalhours - ((response[8] - response[6][0][i]) * (response[7] / response[3].length)) 

							var td8 = document.createElement('td');
								td8.setAttribute('style', 'text-align: left;');
								td8.innerHTML = response[0][i][1];
							var td9 = document.createElement('td');
								td9.setAttribute('style', 'text-align: center;');
								td9.innerHTML = response[6][0][i];
							var td10 = document.createElement('td');
								td10.setAttribute('style', 'text-align: center;');
								td10.innerHTML = response[8] - response[6][0][i];
							var td11 = document.createElement('td');
								td11.setAttribute('style', 'text-align: center;');
								td11.innerHTML = formatTimeWord(late);
							var td12 = document.createElement('td');
								td12.setAttribute('style', 'text-align: center;');
								td12.innerHTML = formatTimeWord(undertime);
							var td13 = document.createElement('td');
								td13.setAttribute('style', 'text-align: center;');
								td13.innerHTML = formatTimeWord(overtime);
							var td14 = document.createElement('td');
								td14.setAttribute('style', 'text-align: center;');
								td14.innerHTML = (response[6][0][i] > 0 && response[8] >= response[3].length)?/*?totalhours+" hours":""*/ hoursToTime(totalhours):"";

							tr2.appendChild(td8);
							tr2.appendChild(td9);
							tr2.appendChild(td10);
							tr2.appendChild(td11);
							tr2.appendChild(td12);
							tr2.appendChild(td13);
							tr2.appendChild(td14);

						tbody.appendChild(tr2);
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

		function formatTimeWord(time) {
			var string = "";
			if(time.getHours() > 0) {
				string += time.getHours();
				string += (time.getHours() > 1)?" hours ":" hour ";
			}
			if(time.getMinutes() > 0) {
				string += time.getMinutes();
				string += (time.getMinutes() > 1)?" minutes ":" minute ";
			}
			if(time.getSeconds() > 0) {
				string += time.getSeconds();
				string +=(time.getSeconds() > 1)?" seconds ":" second";
			}

			return string;
		}

		function getHoursFromTime(time) {
			var hour_from_minute = time.getMinutes() / 60;
			var hour = time.getHours() + hour_from_minute;

			return hour;
		}

		function hoursToTime(hrs) {
			string = "";
			var minutes = (hrs % 1) * 60;
			// var seconds = (minutes % 60) * 60;

			if(hrs > 0) {
				string += Math.floor(hrs);
				string += (hrs > 1)?" hours ":" hour ";
			}
			if(minutes > 0) {
				string += Math.round(minutes);
				string += (minutes > 1)?" minutes ":" minute ";
			}

			return string;
		}

	</script>
@endsection