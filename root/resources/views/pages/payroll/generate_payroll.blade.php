@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-money"></i> Generate Payroll
		</div>
		<div class="card-body">
			<div class="form-inline">
				<div class="form-group">
					<form method="post" action="{{url('payroll/generate-payroll/find-dtr')}}" id="frm-gp">
						{{csrf_field()}}
						<select class="form-control mr-2" id="month" name="month">
							@foreach(Core::Months() as $key => $value)
							<option value="{{$key}}" {{($key == date('m')) ? 'selected' : ''}}>{{$value}}</option>
							@endforeach
						</select>
						<select class="form-control mr-2" name="payroll_period" id="payroll_period" required>
							<option value="15D">15th Day</option>
							<option value="30D">30th Day</option>
						</select>
						<select class="form-control mr-2 YearSelector" id="year" name="year">
						</select>
						<select class="form-control mr-2" id="ofc" name="ofc">
							<option value="" selected="" disabled="">-Select office to generate-</option>
							@foreach($data[0] as $office)
							<option value="{{$office->cc_id}}">{{$office->cc_desc}}</option>
							@endforeach
						</select>
						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
						<button type="button" class="btn btn-primary" onclick="ClearSearch()"><i class="fa fa-eraser"></i></button>
						<button type="button" class="btn btn-primary" onclick="GeneratePayroll()">Generate</button>
					</form>
				</div>
			</div>
		</div>
		<div class="card-header">
			<div class="row">
				<div class="col">
					<i class="fa fa-clock-o"></i> Available DTR Summary
				</div>
				<div class="col border-left">
					<i class="fa fa-clock-o"></i> Generated Payroll History
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dt-gds">
							<thead>
								<tr>
									<th>Date Generated</th>
									{{-- <th>Time Generated</th> --}}
									<th>Payroll Period</th>
									<th>Employee</th>
									<th>Office</th>
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
								@if(count($data[1])>0)
								@foreach($data[1] as $ghistory)
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
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{url('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript">
		var dataTable_gds = $('#dt-gds').DataTable(dataTable_config6);
		var dataTable_gdh = $('#dt-gph').DataTable(dataTable_config6);
	</script>
	<script type="text/javascript">
		$("#frm-gp").on('submit', function(e)
		{
			e.preventDefault();
			if (ChckReqFlds()) {
				$.ajax({
					type : $(this).attr('method'),
					url : $(this).attr('action'),
					data : $(this).serialize(),
					dataTy : 'json',
					success : function(data)
					{
						// console.log(data);
						dataTable_gds.clear().draw();
						if (data!="error") {
							var d = JSON.parse(data);
							dataTable_gds.search(d.search).draw();
							dataTable_gdh.search(d.search).draw();
							if (d.dtr_summaries.length > 0) {
								for (var i = 0; i < d.dtr_summaries.length; i++) {
									LoadTable_gds(d.dtr_summaries[i]);
								}
							}
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
			}
			return true;
		}

		function ClearSearch()
		{
			dataTable_gds.search('').draw();
			dataTable_gdh.search('').draw();
		}

		function GeneratePayroll()
		{
			if (ChckReqFlds()) {
				$.ajax({
					type : 'post',
					url : '{{url('/payroll/generate-payroll/generate')}}',
					data : $('#frm-gp').serialize(),
					dataTy : 'json',
					success : function(data)
					{
						// console.log(data);
						if (data == "no record") {
							alert("No generated DTR available.");
						} else {
							// console.log(data);
							data = JSON.parse(data);
							if (data.results.length > 0) {
								var results = data.results;
								for (var i = 0; i < results.length; i++) {
									var d = results[i];
									var e = d.split(":");
									if (e[1]=="ok") {
										alert(d);
									}
								}
							}
							dataTable_gds.clear().draw();
							if (data.dtrsum.length > 0) {
								dtrsum = data.dtrsum;
								for (var i = 0; i < dtrsum.length; i++) {
									LoadTable_gds(dtrsum[i]);
								}
							}
							dataTable_gdh.clear().draw();
							if (data.ghistory.length > 0) {
								ghistory = data.ghistory;
								for (var i = 0; i < ghistory.length; i++) {
									LoadTable_gdh(ghistory[i]);
								}
							}
							alert("Payroll Generated.");
						}
					}
				});
			}
		}

		function LoadTable_gds(data)
		{
			dataTable_gds.row.add([
				data.date_generated+"<br>"+data.time_generated,
				data.date_from+" to "+data.date_to,
				data.name,
				// "-office-goes-here-",
				data.office,
				data.empid,
			]).draw();
		}

		function LoadTable_gdh(data)
		{
			dataTable_gdh.row.add([
				data.date_generated,
				data.time_generated,
				data.date_from+" to "+data.date_to,
				data.name,
				data.empid,
			]).draw();
		}
	</script>
@endsection