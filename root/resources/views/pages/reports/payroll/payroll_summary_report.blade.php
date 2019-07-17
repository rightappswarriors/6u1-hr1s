@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-money"></i> Payroll Summary Report
		</div>
		<div class="card-body">
			<div class="form-inline">
				<div class="form-group">
					<form class="form-inline" method="post" action="{{url('payroll/generate-payroll/find-dtr')}}" id="frm-gp">
						{{csrf_field()}}
						<label class="mr-1">Select Payroll:</label>
						<select class="form-control mr-2" id="month" name="month">
							@foreach(Core::Months() as $key => $value)
							<option value="{{$key}}" {{($key == date('m')) ? 'selected' : ''}}>{{$value}}</option>
							@endforeach
						</select>
						<select class="form-control mr-2" name="payroll_period" id="payroll_period" required>
							<option value="15D" {{(date('d') <= 15) ? 'selected' : ''}}>15th Day</option>
							<option value="30D" {{(date('d') > 15) ? 'selected' : ''}}>30th Day</option>
						</select>
						<select class="form-control mr-2 YearSelector" id="year" name="year">
						</select>
						<button type="button" class="btn btn-primary mr-2" onclick="SearchOnTable()"><i class="fa fa-search"></i></button>
						{{-- <button type="button" class="btn btn-primary mr-2" onclick="PrintPayslip();"><i class="fa fa-print"></i> Print Payslip</button> --}}
						<button type="button" class="btn btn-primary" onclick="ExportPS()"><i class="fa fa-file-excel-o"></i> Export Payroll Summary</button>
					</form>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<thead>
						<tr>
							<th>Date Generated</th>
							<th>Time Generated</th>
							<th>Payroll Period</th>
							<th>Employee</th>
							<th>User ID</th>
						</tr>
					</thead>
					<tbody>
						@if(count($data[0])>0)
						@foreach($data[0] as $ghistory)
						<tr data="{{$ghistory->item_no}}">
							<td>{{$ghistory->date_generated}}</td>
							<td>{{$ghistory->time_generated}}</td>
							<td>{{$ghistory->date_from}} to {{$ghistory->date_to}}</td>
							<td>{{Employee::Name($ghistory->empid)}}</td>
							<td>{{$ghistory->empid}}</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{url('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript" src="{{url('js/print-me.js')}}"></script>
	<script type="text/javascript">
		var dataTable = $('#dataTable').DataTable(dataTable_config3);
	</script>
	<script type="text/javascript">
		var selected_row = null;
		var dtr_summary = null;
		dataTable.on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script>
	<script type="text/javascript">
		$('#month').on('change', function() {
			SearchOnTable();
		});
		$('#year').on('change', function() {
			SearchOnTable();
		});
		$('#payroll_period').on('change', function() {
			SearchOnTable();
		});
	</script>
	<script type="text/javascript">
		function ExportPS()
		{
			PrintPage('{{url('reports/payroll-summary-report/export')}}?pp='+$('#payroll_period').val()+'&year='+$('#year').val()+'&month='+$('#month').val());
		}

		function SearchOnTable()
		{
			var pp_from = ""; var pp_to = ""; var pp_txt = "";
			$.ajax({
				type : 'post',
				url : '{{url('payroll/payroll-period/get-dates')}}',
				data : {month:$('#month').val(), pp:$('#payroll_period').val(), year:$('#year').val()},
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						data = JSON.parse(data);
						pp_from = data.from;
						pp_to = data.to;
						pp_txt = pp_from + " to " + pp_to;
						dataTable.search(pp_txt).draw();
					} else {
						alert("ERROR! Unable to find payroll period");
					}
				}
			});
		}

		function PrintPayslip()
		{
			if (selected_row != null) {
				PrintPage('{{url('reports/payroll-summary-report/print')}}?item_no='+selected_row.attr('data'));
			} else {
				alert("No selected payroll.");
			}
		}
	</script>
@endsection