@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-file-excel-o"></i> Payroll Summary Report
		</div>
		<div class="card-body">
			<form method="post" action="{{url('payroll/generate-payroll/find-dtr')}}" id="frm-gp">
				<div class="form-group">
					<div class="form-inline">
						{{csrf_field()}}
						<label class="mr-1">Select Payroll:</label>
						<select class="form-control mr-2" id="month" name="month">
							@foreach(Core::Months() as $key => $value)
							<option value="{{$key}}" {{($key == date('m')) ? 'selected' : ''}}>{{$value}}</option>
							@endforeach
						</select>
						<select class="form-control mr-2" name="payroll_period" id="payroll_period" required>
							<option value="15D">15th Day</option>
							<option value="30D">30th Day</option>
						</select>
						<select class="form-control mr-2 YearSelector" id="year" name="year"></select>
					</div>
				</div>
				<div class="form-group">
					<div class="form-inline">
						<select class="form-control mr-2" id="ofc" name="ofc">
							<option value="" selected="" disabled="">-Select office to generate-</option>
							@foreach($data[0] as $office)
							<option value="{{$office->cc_id}}">{{$office->cc_desc}}</option>
							@endforeach
						</select>
						<button type="button" class="btn btn-primary mr-2" onclick="SearchOnTable()"><i class="fa fa-search"></i></button>
						{{-- <button type="button" class="btn btn-primary mr-2" onclick="PrintPayslip();"><i class="fa fa-print"></i> Print Payslip</button> --}}
					</div>
				</div>
			</form>
		</div>
		<div class="card-header">
			Generated Payroll <button type="button" class="btn btn-primary" onclick="ExportPS()"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<col>
					<col>
					<col>
					<col>
					<col>
					<thead>
						<tr>
							<th>User ID</th>
							<th>Employee</th>
							<th>Date Generated</th>
							<th>Payroll Period</th>
						</tr>
					</thead>
					<tbody>
						{{-- @if(count($data[0])>0)
						@foreach($data[0] as $ghistory)
						<tr data="{{$ghistory->item_no}}">
							<td>{{$ghistory->date_generated}}</td>
							<td>{{$ghistory->time_generated}}</td>
							<td>{{$ghistory->date_from}} to {{$ghistory->date_to}}</td>
							<td>{{Employee::Name($ghistory->empid)}}</td>
							<td>{{$ghistory->empid}}</td>
						</tr>
						@endforeach
						@endif --}}
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
		var tbl_psr = $('#dataTable').DataTable(dataTable_config3);
	</script>
	<script type="text/javascript">
		var selected_row = null;
		var dtr_summary = null;
		tbl_psr.on('click', 'tbody > tr', function() {
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
			if (ChckReqFlds()) {
				$.ajax({
					type : 'get',
					url : '{{url('reports/payroll-summary-report/export')}}',
					data : {pp:$('#payroll_period').val(), year:$('#year').val(), month:$('#month').val(), ofc:$('#ofc').val()},
					dataTy : 'json',
					success : function(data) {
						if (data!="error") {
							PrintPage('{{url('reports/payroll-summary-report/export')}}?pp='+$('#payroll_period').val()+'&year='+$('#year').val()+'&month='+$('#month').val()+"&ofc="+$('#ofc').val());
							alert("Payroll generated.");
						} else {
							alert("Error in exporting payroll.");
						}
					},
					error : function()
					{
						alert("Error on exporting payroll. Please try again later.");
					}
				});
			}
		}

		function SearchOnTable()
		{
			var pp_from = ""; var pp_to = ""; var pp_txt = "";
			if (ChckReqFlds()) {
				$.ajax({
					type : 'post',
					url : '{{url('reports/payroll-summary-report/get-dates')}}',
					data : {month:$('#month').val(), pp:$('#payroll_period').val(), year:$('#year').val(), ofc:$('#ofc').val()},
					dataTy : 'json',
					success : function(data) {
						// console.log(data);
						tbl_psr.clear().draw();
						data = JSON.parse(data);
						if (data!="error") {
							pp = JSON.parse(data.pp);
							pp_from = pp.from;
							pp_to = pp.to;
							pp_txt = pp_from + " to " + pp_to;
							// tbl_psr.search(pp_txt).draw();
							for (var i = 0; i < data.psr.length; i++) {
								var psr =  data.psr[i];
								Load_PSR(psr);
							}
						} else {
							alert("ERROR! Unable to find payroll period");
						}
					}
				});
			}
		}

		function Load_PSR(data)
		{
			tbl_psr.row.add([
				data.empid,
				data.name,
				data.date_generated+" @ "+data.time_generated,
				data.date_from+" to "+data.date_to,
			]).draw();
		}

		function PrintPayslip()
		{
			if (selected_row != null) {
				PrintPage('{{url('reports/payroll-summary-report/print')}}?item_no='+selected_row[0].cells[0].textContent);
			} else {
				alert("No selected payroll.");
			}
		}

		function ChckReqFlds()
		{
			if ($("#month").val()==null || $("#month").val()=="") {
				alert("No month selected");
				return false;
			}
			if ($("#payroll_period").val()==null || $("#payroll_period").val()=="") {
				alert("No payroll period selected");
				return false;
			}
			if ($("#year").val()==null || $("#year").val()=="") {
				alert("No year selected");
				return false;
			}
			if ($('#ofc').val()==null || $('#ofc').val()=="") {
				alert("No office selected");
				return false;
			}
			return true;
		}

		$('#month').on('change', function() {SearchOnTable();});
		$('#payroll_period').on('change', function() {SearchOnTable();});
		$('#year').on('change', function() {SearchOnTable();});
		$('#ofc').on('change', function() {SearchOnTable();});
	</script>
@endsection