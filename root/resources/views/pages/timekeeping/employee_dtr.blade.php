@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-calendar"></i> Employee DTR
		</div>
		<div class="card-body mb-2">
			<form method="post" action="{{url('timekeeping/employee-dtr/load-dtr')}}" id="frm-loaddtr">
				<div class="form-inline">
					<div class="form-group mr-2">
						<label>From:</label>
						<input type="text" name="date_from" id="date_from" class="form-control" value="{{date('m/d/Y')}}" required>
					</div>
					<div class="form-group mr-2">
						<label>To:</label>
						<input type="text" name="date_to" id="date_to" class="form-control" value="{{date('m/d/Y')}}" required>
					</div>
					<div class="form-group mr-2">
						<label>Employee:</label>
						<select class="form-control" name="tito_emp" id="tito_emp" required>
							<option disabled selected value="">---</option>
							@if(!empty($data[0]))
							@foreach($data[0] as $emp)
							<option value="{{$emp->empid}}">{{$emp->firstname." ".$emp->lastname}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<button type="submit" class="btn btn-primary mr-2">Go</button>
					<button type="button" class="btn btn-primary" id="btn-print" data="#" onclick="PrintPage(this.getAttribute('data'))"><i class="fa fa-print"></i></button>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<thead>
						<tr>
							<th>Date</th>
							<th>Time In</th>
							<th>Time Out</th>
						</tr>
					</thead>
					<tbody class="text-center"></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript">
		$('#date_from').datepicker(date_option);
		$('#date_to').datepicker(date_option);
	</script>
	<script type="text/javascript">
		var table = $('#dataTable').DataTable();
		function LoadTable(data)
		{
			table.row.add([
				data.work_date,
				data.timein,
				data.timeout
			]).draw();
		}
		$('#frm-loaddtr').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type : $(this).attr('method'),
				url : $(this).attr('action'),
				data : $(this).serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="No record found.") {
							table.clear().draw();
							for(var i = 0 ; i < data.length; i++) {
								LoadTable(data[i]);
							}
							$('#btn-print').attr('data', '{{url('/timekeeping/employee-dtr/print-dtr')}}?tito_emp='+$('#tito_emp').val()+'&date_from='+$('#date_from').val()+'&date_to='+$('#date_to').val());
						} else {
							alert(data);
						}
					} else {
						alert('Error on loading data.');
					}
				}
			});
		})

		function ClearFld() {
			$('#btn-print').attr('data', '#');
		}
	</script>
	<script type="text/javascript">
		function PrintPage(page_location) {
			$("<iframe>")
	        .hide()
	        .attr("src", page_location)
	        .appendTo("body");   
		}
	</script>
@endsection