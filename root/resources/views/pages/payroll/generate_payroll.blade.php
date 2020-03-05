@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-money"></i> Generate Payroll
		</div>
		<div class="card-body">
			<form method="post" action="{{url('payroll/generate-payroll/find-dtr')}}" id="frm-gp">
				{{csrf_field()}}
				<input type="hidden" name="isForDisplay">
				<div class="form-group row">
					<div class="col-sm-4">		
						<select class="form-control" id="ofc" name="ofc">
							<option value="" selected="" disabled="">-Select Office-</option>
							@foreach($data[0] as $office)
							<option value="{{$office->cc_id}}">{{$office->cc_desc}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-4">	
						<select class="form-control" id="empstatus" name="empstatus">
							<option value="" selected="" disabled="">-Select Employee Status-</option>
							@foreach($data[2] as $empstatus)
							<option value="{{$empstatus->status_id}}">{{$empstatus->description}}</option>
							@endforeach
						</select>
					</div>	
				</div>
				<div class="form-group row">
					{{-- <div class="col-sm-2"> --}}
						{{-- <select class="form-control" id="month" name="month">
							@foreach(Core::Months() as $key => $value)
							<option value="{{$key}}" {{($key == date('m')) ? 'selected' : ''}}>{{$value}}</option>
							@endforeach
						</select> --}}
					{{-- </div> --}}
					<div class="col-sm-2">	
						{{-- <select class="form-control" name="payroll_period" id="payroll_period" required>
							<option value="15D">15th Day</option>
							<option value="30D">30th Day</option>
						</select> --}}
						<label class="mr-1">Payroll Period From:</label>
						<input type="date" value="{{Date('Y-m-01')}}" name="dateFrom" id="dateFrom" class="datePicker form-control">
					</div>
					<div class="col-sm-2">
						{{-- <select class="form-control YearSelector" id="year" name="year">
						</select> --}}
						<label class="mr-1">Payroll Period To:</label>
						<input type="date" name="dateTo" value="{{Date('Y-m-t')}}" id="dateTo" class="datePicker form-control">
					</div>	
					<div class="col-sm-2">
						<label class="mr-1">Payroll Preview Type:</label>
						<select class="form-control" name="pptype" id="pptype">
							<option Value="1" selected>1st</option>
							<option value="2">2nd</option>
						</select>
					</div>
					<div class="col-sm-2">	
						<label class="mr-1">Generation Type:</label>
						<select class="form-control" name="gen_type" id="gen_type">
							<option Value="BASIC" selected>Basic</option>
							<option value="OVERTIME">Overtime</option>
						</select>
					</div>
				</div>	
					<div class="form-group row">
						<div class="col-sm-2">
							<button type="button" class="btn btn-primary border-right" onclick="FindDTRS()"><i class="fa fa-search"></i> Search</button>
							<button type="button" class="btn btn-primary border-left" onclick="ClearSearch()"><i class="fa fa-eraser"></i> Clear</button>
						</div>
					</div>	
						
				</div>
			</form>
			<div>
				<div class="card text-white bg-danger collapse mb-3" id="alert-generate-error">
					<div class="card-header">
						<i class="fa fa-exclamation"></i> Error
					</div>
					<div class="card-body" id="alert-generate-error-body"></div>
				</div>
			</div>
		</div>
		<div class="card-header border-top">
			<div class="row">
				<div class="col">
					Available DTR Summary
					<button type="button" class="btn btn-primary float-right" id="btn-generate" onclick="GeneratePayroll()" disabled=""><i class="fa fa-share"></i> <i class="fa fa-server"></i> Generate All</button>
					<button type="button" class="btn btn-primary float-right" onclick="previewPayroll()" id="btn-preview" disabled=""><i class="fa fa-share"></i> <i class="fa fa-server"></i> Preview Payroll Summary Report</button>
				</div>
				<div class="col border-left">
					Generated Payroll
					{{-- <div class="progress">
					    <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
					</div> --}}
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dt-gds">
							<col width="10%">
							<thead>
								<tr>
									<th>Date Generated</th>
									{{-- <th>Time Generated</th> --}}
									{{-- <th>Payroll Period</th> --}}
									<th>Employee</th>
									<th>User ID</th>
								</tr>
							</thead>
							<tbody>
								{{-- @if(count($data[0])>0)
								@foreach($data[0] as $gsum)
								<tr data="{{$gsum->code}}">
									<td>{{$gsum->date_generated}}</td>
									<td>{{$gsum->time_generated}}</td>
									<td>{{$gsum->date_from}} to {{$gsum->date_to}}</td>
									<td>{{Employee::Name($gsum->empid)}}</td>
									<td>{{$gsum->empid}}</td>
								</tr>
								@endforeach
								@endif --}}
							</tbody>
						</table>
					</div>
				</div>
				<div class="col border-left">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dt-gph">
							<col width="10%">
							<thead>
								<tr>
									<th>Date Generated</th>
									{{-- <th>Time Generated</th> --}}
									{{-- <th>Payroll Period</th> --}}
									<th>Employee</th>
									<th>User ID</th>
								</tr>
							</thead>
							<tbody>
								@if(count($data[1])>0)
								@foreach($data[1] as $ghistory)
								<tr data="{{$ghistory->item_no}}">
									<td>{{$ghistory->date_generated}}</td>
									{{--<td>{{$ghistory->time_generated}}</td>--}}
									{{--<td>{{$ghistory->date_from}} to {{$ghistory->date_to}}</td>--}}
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
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{url('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript">
		var dataTable_gds = $('#dt-gds').DataTable(dataTable_config6);
		var dataTable_gdh = $('#dt-gph').DataTable(dataTable_config6);
		var dtrs = [];
	</script>
	<script type="text/javascript">
		// ,#payroll_period, #year
		$('#ofc, #empstatus, #dateFrom, #dateTo').on('change', function() {
			ClearSearch();
		});
		$("#frm-gp").on('submit', function(e)
		{
			e.preventDefault();
			if (ChckReqFlds()) {
				$.ajax({
					type : $(this).attr('method'),
					url : $(this).attr('action'),
					data : $(this).serialize(),
					dataTy : 'json',
					beforeSend : function()
					{
						// togglePreloader();
						$('#btn-frm-submit').html('Searching DTR <i class="fa fa-spin fa-spinner"></i>');
					},
					complete : function()
					{
						// togglePreloader();
						$('#btn-frm-submit').html('<i class="fa fa-search"></i> Search');
					},
					success : function(data)
					{
						// console.log(data);
						dataTable_gds.clear().draw();
						dataTable_gdh.clear().draw();
						if (data!="error") {
							var d = JSON.parse(data);
							// dataTable_gds.search(d.search).draw();
							// dataTable_gdh.search(d.search).draw();
							if (d.dtr_summaries.length > 0) {
								for (var i = 0; i < d.dtr_summaries.length; i++) {
									//console.log(d.dtr_summaries[i])
									LoadTable_gds(d.dtr_summaries[i]);
								}
							}
							if (d.payroll_history.length > 0) {
								for (var i = 0; i < d.payroll_history.length; i++) {
									LoadTable_gdh(d.payroll_history[i]);
								}
							}
							$('#btn-generate').removeAttr('disabled');
							$('#btn-preview').removeAttr('disabled');
						} else {
							alert("Error submiting your request.");
						}
					},
					error : function()
					{
						alert("Error submiting your request. Please reload the page.");
					}
				});
			}
		});

		function ChckReqFlds()
		{
			if ($('#ofc').val()==null || $('#ofc').val()=="") {
				alert("No office selected");
				return false;
			} else if ($('#empstatus').val()==null || $('#empstatus').val()=="") {
				alert("No employee status selected");
				return false;
			}
			return true;
		}

		function ClearSearch()
		{
			dataTable_gds.search('').draw();
			dataTable_gds.clear().draw();
			dataTable_gdh.search('').draw();
			$('#btn-generate').attr('disabled', true);
			$('#btn-preview').attr('disabeld',true);
			hideErrorDiv();
		}

		function hideErrorDiv()
		{
			// $('#alert-generate-error').hide();
			$('#alert-generate-error').collapse('hide');
			$('#alert-generate-error-body').empty();
		}

		function FindDTRS()
		{
			ClearSearch();
			$("#frm-gp").submit();
		}

		function previewPayroll(){
			window.open('{{url('payroll/generate-payroll/preview/?ofc=')}}'+$('#ofc').val()+'&empstatus='+$('#empstatus').val()+'&dateFrom='+$('#dateFrom').val()+'&dateTo='+$('#dateTo').val()/*+'&month='+$('#month').val()+'&payroll_period='+$('#payroll_period').val()+'&year='+$('#year').val()*/+'&gen_type='+$("#gen_type").val()+'&pptype='+$('#pptype').val());
		}

		function GeneratePayroll()
		{
			let r = confirm('Are you sure you want to generate this Payroll? Please check preview payroll summary report for accuracy');
			if(r){
				if (ChckReqFlds()) {
					$.ajax({
						type : 'post',
						url : '{{url('/payroll/generate-payroll/generate')}}',
						data : $("#frm-gp").serialize(),
						dataTy : 'json',
						beforeSend : function()
						{
							togglePreloader();
						},
						success : function(data)
						{
							hideErrorDiv();
							if (data[1].length > 0) {
								$('#alert-generate-error').collapse('show');
								for (var i = 0; i < data[1].length; i++) {
									var data_error = data[1][i];
									$('#alert-generate-error-body').append(data_error+'<br>');
								}
								alert("Some errors occured when generating.");
							} else {
								alert("Payroll Generated.");
								FindDTRS();
							}
						},
						error : function()
						{
							alert('Error in generating dtr. Please try again or reload the page.');
						},
						complete : function()
						{
							togglePreloader();
						},
					});
				}
			}
		}

		function LoadTable_gds(data)
		{
			dataTable_gds.row.add([
				data.date_generated + data.time_generated,
				// data.date_from+" to "+data.date_to,
				data.empname,
				data.empid,
			]).draw();
		}

		function LoadTable_gdh(data)
		{
			dataTable_gdh.row.add([
				data.date_generated + data.time_generated,
				// data.time_generated,
				// data.date_from+" to "+data.date_to,
				data.empname,
				data.empid,
			]).draw();
		}
	</script>
@endsection