@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-file-excel-o"></i> Payroll Summary Report
		</div>
		<div class="card-body">
			<form method="post" action="{{url('payroll/generate-payroll/find-dtr')}}" id="frm-gp">
				<div class="form-group row">
					<label class="col-sm-2">Office:</label>
					<div class="col-sm-5">
						<select class="form-control mr-2" id="ofc" name="ofc">
							<option value="" selected="" disabled="">-Select office to generate-</option>
							@foreach($data[0] as $office)
							<option value="{{$office->cc_id}}">{{$office->cc_desc}}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-2">Payroll period:</label>
					<div class="col-sm-5">
						<select class="form-control mr-2" id="pp" name="pp">
							<option value="" selected="" disabled="">-Select Generated Payroll Period-</option>
						</select>
					</div>
					<div class="col-sm-4">
						<select class="form-control mr-2" name="gen_type" id="gen_type">
							<option Value="BASIC" selected>Basic</option>
							<option value="OVERTIME">Overtime</option>
						</select>
					</div>
					<div class="col-sm-1">
						<i class="fa fa-spin fa-spinner" id="loading-icon-1" style="display: none;"></i>
					</div>
				</div>


			</form>
		</div>
		<div class="card-header border-top">
			Generated Payroll <button type="button" class="btn btn-primary" onclick="ExportPS()"><i class="fa fa-file-excel-o"></i> Export General Payroll (.xlsx)</button> <i class="fa fa-spin fa-spinner" id="loading-icon-2" style="display: none;"></i>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<col>
					<col width="40%">
					<col>
					<col>
					<col width="10%">
					<thead>
						<tr>
							<th>User ID</th>
							<th>Employee</th>
							<th>Date Generated</th>
							<th>Payroll Period</th>
							<th>Option</th>
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
		// tbl_psr.on('click', 'tbody > tr', function() {
		// 	$(this).parents('tbody').find('.table-active').removeClass('table-active');
		// 	selected_row = $(this);
		// 	$(this).toggleClass('table-active');
		// });
	</script>
	<script type="text/javascript">
		function ExportPS()
		{
			if (ChckReqFlds()) {
				$.ajax({
					type : 'get',
					url : '{{url('reports/payroll-summary-report/export')}}',
					data : {pp: $("#pp").val(), ofc:$('#ofc').val(), gen_type:$('#gen_type').val()},
					dataTy : 'json',
					success : function(data) {
						if (data!="error") {
							PrintPage('{{url('reports/payroll-summary-report/export')}}?pp='+$('#pp').val()+'&ofc='+$('#ofc').val()+'&gen_type='+$('#gen_type').val());
							// alert("Payroll generated.");
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
			if (ChckReqFlds()) {
				$.ajax({
					type : 'post',
					url : '{{url('reports/payroll-summary-report/get-dates')}}',
					data : {ofc:$('#ofc').val()},
					dataTy : 'json',
					beforeSend : function()
					{
						$('#loading-icon-1').show();
						tbl_psr.clear().draw();
					},
					success : function(data) {
						// console.log(data);
						$('#pp').html('<option value="" selected="" disabled="">-Select Generated Payroll Period-</option>');

						if (data!="error") {
							if (data.length > 0) {
								for (var i = 0; i < data.length; i++) {
									$('#pp').append('<option value="'+data[i].date_from+'|'+data[i].date_to+'">'+data[i].pp+'</option>');
								}
							} else {
								$('#pp').html('<option value="" selected="" disabled="">-No Generated Payroll-</option>');
							}
						} else {
							alert("ERROR! Unable to find payroll period");
						}
					},
					complete : function()
					{
						$('#loading-icon-1').hide();
					},
				});
			}
		}

		function SearchRecord()
		{
			if (ChckReqFlds()) {
				$.ajax({
					type : 'post',
					url : '{{url('reports/payroll-summary-report/get-records')}}',
					data : {pp: $("#pp").val(), gen_type : $('#gen_type').val()},
					dataTy : 'json',
					beforeSend : function()
					{
						$('#loading-icon-2').show();
						tbl_psr.clear().draw();
					},
					success : function(data) {
						// console.log(data);
						if (data!="error") {
							if (data.length > 0) {
								for (var i = 0; i < data.length; i++) {
									Load_PSR(data[i]);
								}
							}
						} else {
							alert("ERROR! Unable to find payroll period");
						}
					},
					complete : function()
					{
						$('#loading-icon-2').hide();
					},
				});
			}
		}

		function Load_PSR(data)
		{
			tbl_psr.row.add([
				data.empid,
				data.empname,
				data.date_generated+" at "+data.time_generated,
				data.date_from+" to "+data.date_to,
				'<button type="button" title="View Payslip" class="btn btn-primary mr-2" onclick="PrintPayslip(this);" data="'+data.emp_pay_code+'"><i class="fa fa-print"></i></button>',
			]).draw();
		}

		function PrintPayslip(obj)
		{
			/*if (selected_row != null) {
				PrintPage('{{url('reports/payroll-summary-report/print')}}?item_no='+selected_row[0].cells[0].textContent);
			} else {
				alert("No selected payroll.");
			}*/
			if ($('#gen_type').val() == "OVERTIME") {
				PrintPage('{{url('reports/payroll-summary-report/print-ot')}}?pcode='+$(obj).attr('data'));
			} else {
				PrintPage('{{url('reports/payroll-summary-report/print')}}?pcode='+$(obj).attr('data'));
			}
		}

		function ChckReqFlds()
		{
			if ($('#ofc').val()==null || $('#ofc').val()=="") {
				alert("No office selected");
				return false;
			}
			return true;
		}

		$('#ofc').on('change', function() {SearchOnTable();});
		$('#pp').on('change', function() {SearchRecord();});
		$('#gen_type').on('change', function() {SearchRecord();});
	</script>
@endsection