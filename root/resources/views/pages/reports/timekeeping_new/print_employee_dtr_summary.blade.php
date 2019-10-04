@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header" id="print_name_hide">
			<div class="form-inline">
				<i class="fa fa-fw fa-clock-o"></i>Print Employee DTR Summary<br>
			</div>
		</div>
		<div class="card-body">
			<div class="form-inline mb-4" id="print_hide">
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
			</div>

			<div class="table-responsive">
				<table class="table table-hover" style="font-size: 13px;" id="table">
					<thead>
						<tr>
							<th>Employee</th>
							<th>Days Worked</th>
							<th>Absences</th>
							<th>Late</th>
							<th>Undertime</th>
							<th>Total Undertime</th>
							{{-- <th>Total Hours Rendered</th> --}}
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
		});

		$('#find_btn').on('click', function() {
			$.ajax({
				type: 'post',
				url: '{{url('reports/timekeeping/employee-dtr/')}}/findnew',
				data: {"code":$('#payroll_period').val(), "type":$('#generationtype').val()},
				success: function(data) {
					table.clear().draw();
					for(i=0; i<data.length; i++) {
						FillTable(data[i]);
					}
				},
			});
		});

		function FillTable(data) {
			table.row.add([
				data.employee_readable,
				data.days_worked,
				data.days_absent,
				data.late,
				data.undertime,
				data.total_overtime
			]).draw();
		}

		function setPeriods(data) {
			data = Object.keys(data).map(function(key) {
				return [key, data[key]];
			});

			// console.log(data);
			let select = $('#payroll_period');

			while(select[0].firstChild) {
				select[0].removeChild(select[0].firstChild)
			}

			if(data.length > 0) {
				// for(i=0; i<data.length; i++) {
				// 	var option = document.createElement('option');
				// 		option.setAttribute('value', data[i].code);
				// 		option.innerText = data[i].date_from+' to '+data[i].date_to;

				// 	select[0].appendChild(option);
				// }

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