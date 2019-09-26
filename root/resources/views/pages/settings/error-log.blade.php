@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-wrench"></i> Error Log <button class="btn btn-info btn-sm float-right" onclick="ErrorToday();">today</button>
		</div>
		<div class="card-body mb-2">
			{{-- <ul>
			@if(count($logs) != 0)
				@foreach($logs as $log)
				<li>{{$log}}</li>
				@endforeach
			@else
				<li>No errors.</li>
			@endif
			</ul> --}}
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<col width="10%">
					<col width="20%">
					<col>
					<thead>
						<th>Date</th>
						<th>Module</th>
						<th>Details</th>
					</thead>
					<tbody>
						@if($logs!=null)
							@if(count($logs) > 0)
								@foreach($logs as $log)
								<tr>
									<td>{{$log['date']}}</td>
									<td>{{$log['module']}}</td>
									<td>{{$log['msg']}}</td>
								</tr>
								@endforeach
							@else
							<tr>
								<td colspan="3">No errors.</td>
							</tr>
							@endif
						@else
						<tr>
							<td colspan="3">Some files are missing.</td>
						</tr>
						@endisset
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript">
		function ErrorToday()
		{
			$('#dataTable').DataTable().search("{{date('m-d-Y')}}").draw();
		}
		$(document).ready(function() {
			$('#dataTable').DataTable().search("{{date('m-d-Y')}}").draw();
		});
	</script>
@endsection