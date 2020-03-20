@extends('layouts.user')

<style>
	table{
		width:100%;
	}
	table td{
		padding: 0.35rem;
	}
	@media print{
		#wholeBody{
			width: 700px!important;
		}
		@page{
			margin: 0;
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
						<table border="0">
				<tr>
					<td>CSC FORM No. 48</td>
				</tr>
				<tr>
					<td class="text-center"><b>DAILY TIME RECORD</b></td>
				</tr>
				<tr>
					<td class="text-center namesection">Sample Name</td>
				</tr>
				<tr>
					<td class="text-center" style="border-top: 1px solid #000;">Name</td>
				</tr>
				<tr>
					<td>For the Month of <u><span class="formonth">______________________ 2020</span></u></td>
				</tr>
				<tr>
					<td>official hours of arrival(Regular Days) _______________________</td>
				</tr>
				<tr>
					<td>and Department (Saturday) ______________________</td>
				</tr>
			</table>
			<table border="1">
				<thead>
					<tr>
						<td rowspan="2" style="vertical-align: middle;" class="text-center">DAY</td>
						<td colspan="2" class="text-center">AM</td>
						<td colspan="2" class="text-center">PM</td>
						<td colspan="2" class="text-center">UNDERTIME</td>
					</tr>
					<tr>
						<td class="text-center">Arrival</td>
						<td class="text-center">Departure</td>
						<td class="text-center">Arrival</td>
						<td class="text-center">Departure</td>
						<td class="text-center">Hours</td>
						<td class="text-center">Minutes</td>
					</tr>
				</thead>
				<tbody id="mainTable">
					
				</tbody>
			</table>
			<table border="1" >
				<tr>
					<td style="width: 50%;border: 2px solid #000;" class="text-center"><strong>TOTAL</strong></td>
					<td style="width: 50%;border: 2px solid #000;" class="totalhours"></td>
				</tr>
			</table>
			<table>
				<tr>
					<td>I CERTIFY on my honor that the above is a true and correct report of the hours of work perform, record of which was made daily at the time of arrival and departure from office.</td>
				</tr>
				<tr>
					{{-- Sample Verified --}}
					<td class="text-center"></td>
				</tr>
				<tr>
					<td style="border-top: 1px solid #000;">Verified as to prescribed office hours.</td>
				</tr>
				<tr>
					<td class="text-center"  style="padding-top: 4%;">CARLO JORGE JOAN L. REYES</td>
				</tr>
				<tr>
					<td class="text-center">City Mayor</td>

				</tr>
			</table>
			{{-- <div class="table-responsive hidden mt-3">
				<table class="table table-hover" style="font-size: 13px;" id="table">
					<thead>
						<tr>
							<th>Day</th>
							<th>Arrival (AM)</th>
							<th>Departure (AM)</th>
							<th>Arrival (PM)</th>
							<th>Departure (PM)</th>
							<th>Undertime</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div> --}}

			{{-- <div class="table-responsive table-bordered mt-3" id="dtr">
				<table class="table table-hover" style="font-size: 13px;">
					<thead>
						<tr>
							<th colspan="10" scope="col" style="text-align: center; width:auto; border: none !important"></th>
						</tr>
					</thead>
					<tbody id="timelogs"></tbody>
				</table>
			</div> --}}
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
			$('#mainTable').empty();
			let string = '';

			$('.namesection').html(data.employee_readable);
			$('.formonth').html(data.date_from_month_readable);
			$('.totalhours').html(data.weekdayhrs);

			for(i=0; i<data.covered_dates.length; i++) {
				let amin = '', pmout = '', amout = '', pmin = '', trigger = ['',''];
				if(data.days_worked_readable.length > 0) {

					for(k=0; k<data.undertime_readable.length; k++) {
						let readableUndertime = data.undertime_readable[k];
						if(readableUndertime[0] == data.covered_dates[i][1]){
							trigger = readableUndertime[2].split(':');
							break;
						}
					}

					for(j=0; j<data.days_worked_readable.length; j++) {
						if(data.covered_dates[i][1] == data.days_worked_readable[j][0]) {
							amin = formatAMPM2(data.days_worked_readable[j][1][0]);
							// arrival am
							pmout = '';
							amout = '12:00nn';
							pmin = '1:00pm';

							if(data.days_worked_readable[j][1].length > 2) {
								
								amout = formatAMPM2(data.days_worked_readable[j][1][1]);
								// departure am
								pmin = formatAMPM2(data.days_worked_readable[j][1][2]);
								// arrival pm
								pmout = formatAMPM2(data.days_worked_readable[j][1][3]);
								// departure pm
							} else {
								pmout = formatAMPM2(data.days_worked_readable[j][1][1]);
							}
							
						}
					}

				}
				string += 
				'<tr>'+
					'<td class="text-center">'+data.covered_dates[i][0]+'</td>'+
					'<td>'+amin+'</td>'+
					'<td>'+amout+'</td>'+
					'<td>'+pmin+'</td>'+
					'<td>'+pmout+'</td>'+
					'<td>'+trigger[0]+'</td>'+
					'<td>'+trigger[1]+'</td>'+
				'</tr>';
				
			}
			$('#mainTable').append(string);
			
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
			if(isNaN( parseInt(time) )){return time}
			if(time=="") return "";
			if(time=="<span class='text-danger'>missing</span>") return "";
			var timeString = time;
			var H = +timeString.substr(0, 2);
			var h = H % 12 || 12;
			var ampm = (H < 12 || H === 24) ? "am" : "pm";
			timeString = h + timeString.substr(2, 3)/* + ampm*/;
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