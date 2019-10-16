@extends('layouts.user')

@section('to-body')
	<div class="card">
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

			<div class="row mt-1">
				<div class="col-1">
					Payroll Period:
				</div>
				<div class="col-4">
					<select class="form-control mr-3" name="payroll_period" id="payroll_period" onchange=""></select>
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
					<button class="btn btn-primary mr-3" id="print_btn" disabled><i class="fa fa-fw fa-print"></i></button>
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

			<div class="table-responsive mt-3">
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
		</div>
	</div>
@endsection

@section('to-bottom')
	<script>
		var table = $('#table').DataTable();
	</script>

	<script>
		$('#office').on('change', function() {
			$('#find_btn')[0].removeAttribute('disabled');

			$.ajax({
				type: 'post',
				url: '{{url('reports/timekeeping/employee-dtr/')}}/getperiods',
				data: {'office':$('#office').val()},
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

		$('#find_btn').on('click', function() {

			if($('#employee').val() == "" || $('#employee').val() == null) {
				alert('Please select an employee');
			} else {
				$.ajax({
					type: 'post',
					url: '{{url('reports/timekeeping/employee-dtr/')}}/findnew2',
					data: {"code":$('#payroll_period').val(), "type":$('#generationtype').val(), "emp":$('#employee').val()},
					success: function(data) {
						table.clear().draw();
						for(i=0; i<data[0].days_worked_readable.length; i++) {
							// FillTable(data[i]);
							let d1 = data[0].days_worked_readable[i];
							FillTable(d1);
						}
					},
				});
			}
		});

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
						option.setAttribute('value', data[i][1]);
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
	</script>
@endsection