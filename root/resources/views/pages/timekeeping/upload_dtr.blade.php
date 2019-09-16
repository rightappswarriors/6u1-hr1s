@extends('layouts.user')


@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-upload"></i> Upload DTR File
		</div>
		<div class="card-body">
			@if ($errors->has('file_dtr'))
            <div class="error-span">
                {{ ucfirst(strtolower($errors->first('file_dtr'))) }}
            </div>
            @endif
			<form class="form-inline" autocomplete="off" method="post" action="{{url('timekeeping/upload-dtr')}}" enctype="multipart/form-data" id="frm-uploaddtr">
				{{csrf_field()}}
				<div class="form-group">
					<label class="mr-2">DTR File:</label>
					<input type="file" name="file_dtr" class="form-control" placeholder="Answer" disabled="">
				</div>
				<div class="ml-2 form-group">
					<button type="submit" class="btn btn-success btn-spin" onclick="event.preventDefault();document.getElementById('frm-uploaddtr').submit();" disabled="">Upload</button>
				</div>
			</form>
		</div>
	</div>
	@isset($data)
	<div class="mt-2 card">
		<div class="card-header">
			<i class="fa fa-upload"></i> Upload Time Log History
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<thead>
						<tr>
							<th>Date</th>
							<th>Time</th>
							<th>Employee ID</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>

						@foreach($data[0] as $log)
						<tr>
							<td>{{$log->work_date}}</td>
							<td>{{$log->time_log}}</td>
							<td>{{$log->empid}}</td>
							<td>{{$log->status_desc}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endisset
@endsection