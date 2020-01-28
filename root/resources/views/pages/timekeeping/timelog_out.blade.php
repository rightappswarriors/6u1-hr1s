@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col">
					<i class="fa fa-clock-o"></i> Employees with no timeout
				</div>
				<div class="col">
					<div class="float-right">
						<a href="{{url('timekeeping/timelog-entry')}}"><i class="fa fa-clock-o"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
            {{-- <div class="card">
				<div class="card-header">
					<i class="fa fa-info-circle"></i> Batch Time Log Info
				</div>
				<div class="card-body">
					<form method="post" action="{{url('timekeeping/timelog-entry/batch-time-log-info')}}" id="frm-batchtimeloginfo">
						{{csrf_field()}}
						<div class="form-group form-inline" hidden>
			            	<label class="ml-5">Search by ID:</label>
							<input type="text" name="tito_id" id="tito_id" class="form-control float-right ml-2" placeholder="Search by ID">
						</div>

						<div class="form-group form-inline">
							<label>Search Filters:</label>
						</div>
						<div class="form-group form-inline">
							<div class="row w-75">
								<div class="col">
									<select class="form-control w-100" name="office" id="office" required>
										<option disabled selected value="">Please select an office</option>
										@if(!empty($data[1]))
										@foreach($data[1] as $off)
										<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
										@endforeach
										@endif
									</select>
								</div>
								<div class="col">
									<select class="form-control w-100" name="tito_emp" id="tito_emp" required>
										<option disabled selected value="">Please select an employee</option>
									</select>
								</div>
								<button type="submit" class="btn btn-primary ml-3">Go</button>
							</div>
						</div>
					</form>
				</div>
			</div> --}}
			<div class="mt-2 card">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<i class="fa fa-calendar"></i> Employee Work Dates <span id="empid"></span>
						</div>
						<div class="col text-right">
							@if ($errors->has('file_dtr'))
				            <div class="error-span">
				                {{ ucfirst(strtolower($errors->first('file_dtr'))) }}
				            </div>
				            @endif
				            <button hidden class="btn btn-primary" onclick="onModalToggle($('#tito_emp').val())">Add Log</button>
				            <button hidden type="button" class="btn btn-danger" onclick="toggleDeleteAllModal()">Remove All Logs</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dataTable">
							<thead>
								<tr>
									<th>Employee Name</th>
									<th>Work Date</th>
									<th>Time Log</th>
									<th>Source</th>
									<th>Options</th>
								</tr>
							</thead>
							<tbody class="text-center">
								@isset($filtered)
									@foreach($filtered as $key => $value)
										@foreach($value as $trueData)
										<tr>
											<td>{{$trueData[1]}}</td>
											<td>{{Date('F j, Y',strtotime($trueData[0]))}}</td>
											<td>{{Date('g:i A',strtotime($trueData[2]))}}</td>
											<td>{{Core::SOURCE((string)$trueData[3])}}</td>
											<td><a href="{{url('timekeeping/timelog-entry/'.$trueData[0].'/'.$trueData[6].'/'.$trueData[5])}}" target="_blank" title="Edit Time log" class="btn btn-success btn-edit mr-1"><i class="fa fa-edit"></i></a></td>
										</tr>
										@endforeach
									@endforeach
								@endisset
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Entry Modal -->
	<div class="modal fade" id="modal-addlog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add Log</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-addlog">
						<div class="form-group">
							<label>Work Date</label>
							<input type="text" class="form-control" name="date_workdate" id="date_workdate" value="{{date('Y-m-d')}}" required>
						</div>
						<div class="form-group">
							<label>Time Log</label>
							<input type="time" class="form-control" name="time_timelog" id="time_timelog" value="{{date('g:i A')}}" required>
						</div>
						<div class="form-group">
							<label>Status</label>
							<select class="form-control" name="sel_status" id="sel_status" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="ClearFld()">Close</button>
					<button type="submit" form="frm-addlog" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Edit Entry Modal -->
	<div class="modal fade" id="modal-editlog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit Log</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-editlog">
						<div class="form-group">
							<label>Work Date</label>
							<input type="text" class="form-control" name="date_workdate2" id="date_workdate2" required>
						</div>
						<div class="form-group">
							<label>Time Log</label>
							<input type="time" class="form-control" name="time_timelog2" id="time_timelog2" required>
						</div>
						<div class="form-group">
							<label>Status</label>
							<select class="form-control" name="sel_status2" id="sel_status2" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="ClearFld()">Close</button>
					<button type="submit" form="frm-editlog" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Delete Modal -->
	<div class="modal fade" id="modal-deletelog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> Delete</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<h4>Are you sure you want to remove this?</h4>
				</div>
				<div class="modal-footer">
					<form id="frm-delete" method="post" action="#">
						<button type="button" class="btn btn-default" data-dismiss="modal" onclick="ClearFld();">Cancel</button>
						<button type="submit" class="btn btn-danger">Delete</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Delete Modal -->
	<div class="modal fade" id="modal-deleteAll" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> Delete</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<h6>Are you sure you want to remove all logs from <span id="da-date1"></span> to <span id="da-date2"></span>?</h6>
				</div>
				<div class="modal-footer">
					<form id="frm-deleteAll" method="post" action="#">
						<button type="button" class="btn btn-default" data-dismiss="modal" onclick="ClearFld();">No</button>
						<button type="submit" class="btn btn-danger">Yes</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript">
		$( "#date_workdate").datepicker(date_option);
		$( "#date_workdate2").datepicker(date_option);
		var table = $('#dataTable').DataTable();
	</script>
@endsection