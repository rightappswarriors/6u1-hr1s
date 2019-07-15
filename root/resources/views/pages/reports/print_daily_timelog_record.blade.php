@extends('layouts.user')

@section('to-body')
	<div class="card" >
		<div class="card-header" id="print_name_hide">
			<div class="form-inline">
				<i class="fa fa-fw fa-clock-o"></i>Daily Timelog Record<br>
			</div>
		</div>
		<div class="card-body">
			<div class="form-inline" id="print_hide">
				<div class="form-group">
					<label for="date_from">Date:</label>
					<input type="text" name="date_from" id="date_from" class="form-control mr-3" value="{{date('m-d-Y')}}" readonly>
					<button class="btn btn-primary mr-3" id="generate_btn">Find</button>

					<button class="btn btn-primary mr-3" id="print_btn"><i class="fa fa-fw fa-print"></i></button>
				</div>
			</div>
			<div class="table-responsive table-bordered mt-3" hidden id="dtr">
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

	<script type="text/javascript">
		$('#date_from').datepicker(date_option);
	</script>

	<script>
		var yoarel = "{{url('reports/timekeeping/DailyTimelogRecord/find')}}";
		var date_sent = 0;
		var empid = "";
		$('#date_from').datepicker(date_option5);

		$('#generate_btn').on('click', function() {
			date_sent = $('#date_from').val();
			$('#dtr').removeAttr('hidden');
			process();
		});


		function process() {
			$.ajax({
				type: "post",
				url: yoarel,
				data: {"date_sent":date_sent} ,
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
							td.setAttribute('colspan', '7');
							td.setAttribute('style', 'text-align: left;');
							td.innerHTML = "<b>Date: </b>"+response[0];

						tr.appendChild(td);

					var tr1 = document.createElement('tr');
						var td1 = document.createElement('td');
							td1.setAttribute('style', 'text-align: center; font-weight: bold; width: 10%');
							td1.setAttribute('colspan', '1');
							td1.innerHTML = "Employee";
						var td2 = document.createElement('td');
							td2.setAttribute('style', 'text-align: center; font-weight: bold; width: 10%');
							td2.setAttribute('colspan', '2');
							td2.innerHTML = "Morning";
						var td3 = document.createElement('td');
							td3.setAttribute('style', 'text-align: center; font-weight: bold; width: 10%');
							td3.setAttribute('colspan', '2');
							td3.innerHTML = "Afternoon";
						var td4 = document.createElement('td');
							td4.setAttribute('style', 'text-align: center; font-weight: bold; width: 10%');
							td4.setAttribute('colspan', '2');
							td4.innerHTML = "OT Hours";

						tr1.appendChild(td1);
						tr1.appendChild(td2);
						tr1.appendChild(td3);
						tr1.appendChild(td4);

					var tr2 = document.createElement('tr');
						var td5 = document.createElement('td');
						var td6 = document.createElement('td');
							td6.setAttribute('style', 'text-align: center; width: 5%');
							td6.innerHTML = "In";
						var td7 = document.createElement('td');
							td7.setAttribute('style', 'text-align: center; width: 5%');
							td7.innerHTML = "Out";
						var td8 = document.createElement('td');
							td8.setAttribute('style', 'text-align: center; width: 5%');
							td8.innerHTML = "In";
						var td9 = document.createElement('td');
							td9.setAttribute('style', 'text-align: center; width: 5%');
							td9.innerHTML = "Out";
						var td10 = document.createElement('td');
							td10.setAttribute('style', 'text-align: center; width: 5%');
							td10.innerHTML = "Out";
						var td11 = document.createElement('td');
							td11.setAttribute('style', 'text-align: center; width: 5%');
							td11.innerHTML = "Out";

						tr2.appendChild(td5);
						tr2.appendChild(td6);
						tr2.appendChild(td7);
						tr2.appendChild(td8);
						tr2.appendChild(td9);
						tr2.appendChild(td10);
						tr2.appendChild(td11);

					tbody.appendChild(tr);
					tbody.appendChild(tr1);
					tbody.appendChild(tr2);

					for(i=0; i<response[1].length; i++) {

						var time_log = false;
						var time_log1 = false;

						/**/
						if(response[2][0][i] == null) {
							time_log = false;
						}
						else {
							time_log = new Date(response[2][0][i].work_date+" "+response[2][0][i].time_log);
						}
						if(response[2][1][i] == null) 
							time_log1 = false;
						else 
							time_log1 = new Date(response[2][1][i].work_date+" "+response[2][1][i].time_log);
						/**/


						console.log(time_log);
						console.log(time_log1);

						var tr3 = document.createElement('tr');
							var td12 = document.createElement('td');
								td12.setAttribute('style', 'text-align: left;');
								td12.innerHTML = response[1][i][1];
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
							var overtime_in = document.createElement('td');
								overtime_in.setAttribute('style', 'text-align: center;');
								if(time_log)
									overtime_in.innerHTML = (time_log.getHours() >= {{ explode(":",Timelog::ReqTimeOut())[0] }})?formatAMPM(time_log):"";
							var overtime_out = document.createElement('td');
								overtime_out.setAttribute('style', 'text-align: center;');
								if(time_log1 && time_log)
									overtime_out.innerHTML = (time_log.getHours() >= {{ explode(":",Timelog::ReqTimeOut())[0] }})?formatAMPM(time_log1):"";

							tr3.appendChild(td12);
							tr3.appendChild(in1);
							tr3.appendChild(out1);
							tr3.appendChild(in2);
							tr3.appendChild(out2);
							tr3.appendChild(overtime_in);
							tr3.appendChild(overtime_out);

						tbody.appendChild(tr3);
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