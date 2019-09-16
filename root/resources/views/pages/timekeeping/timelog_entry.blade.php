@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col">
					<i class="fa fa-clock-o"></i> Timelog Entry
				</div>
				<div class="col">
					<div class="float-right">
						<a href="{{url('timekeeping')}}"><i class="fa fa-clock-o"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
            <div class="card">
				<div class="card-header">
					<i class="fa fa-info-circle"></i> Batch Time Log Info
				</div>
				<div class="card-body">
					<form method="post" action="{{url('timekeeping/timelog-entry/batch-time-log-info')}}" id="frm-batchtimeloginfo">
						{{csrf_field()}}
						<div class="form-group form-inline">
							<label>Work Dates</label>
							<input type="input" name="tito_dateStrt" id="tito_dateStrt" class="form-control ml-2" value="{{date('Y-m-1')}}" required> <span class="ml-2">to</span> 
							<input type="input" name="tito_dateEnd" id="tito_dateEnd" class="form-control ml-2" value="{{date('Y-m-d')}}" required>
							@if ($errors->has('tito_dateStrt'))
				            <div class="error-span ml-2">
				                {{ ucfirst(strtolower("tito_dateStrt")) }}
				            </div>
				            @endif
				            @if ($errors->has('tito_dateEnd'))
				            <div class="error-span ml-2">
				                {{ ucfirst(strtolower("tito_dateEnd")) }}
				            </div>
				            @endif

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
										{{-- @if(!empty($data[0]))
										@foreach($data[0] as $emp)
										<option value="{{$emp->empid}}">{{$emp->firstname." ".$emp->lastname}}</option>
										@endforeach
										@endif --}}
									</select>
									{{-- <div class="row">
										<div class="col">
											<input type="text" name="tito_id" id="tito_id" class="form-control w-100" placeholder="Search by ID">
										</div>
									</div> --}}
								</div>
								<button type="submit" class="btn btn-primary ml-3">Go</button>
							</div>
						</div>
					</form>
				</div>
			</div>
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
				            <button class="btn btn-primary" onclick="onModalToggle($('#tito_emp').val())">Add Log</button>
				            <button type="button" class="btn btn-danger" onclick="toggleDeleteAllModal()">Remove All Logs</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dataTable">
							<thead>
								<tr>
									<th>Work Date</th>
									<th>Time Log</th>
									<th>Status</th>
									<th>Source</th>
									<th width="15%">Option</th>
								</tr>
							</thead>
							<tbody class="text-center"></tbody>
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
							<input type="time" class="form-control" name="time_timelog" id="time_timelog" value="{{date('H:i:s')}}" required>
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
		$( "#tito_dateStrt").datepicker(date_option);
		$( "#tito_dateEnd").datepicker(date_option);
		$( "#date_workdate").datepicker(date_option);
		$( "#date_workdate2").datepicker(date_option);
	</script>
	<script type="text/javascript">
		var table = $('#dataTable').DataTable();
		var selected_row = "";
		$('#dataTable').on('click', '.btn-delete', function() {
			$('#modal-deletelog').modal('show');
			$('#frm-delete').attr('action', '{{url('timekeeping/timelog-entry/delete-log')}}?row='+$(this).attr('data'));
			selected_row = $(this).parents('tr');
		});

		$('#tito_emp').on('input', function() {
			$('#tito_id').val('');
		});

		$('#office').on('change', function() {

			while($('#tito_emp')[0].firstChild) {
				$('#tito_emp')[0].removeChild($('#tito_emp')[0].firstChild);
			}

			var hiddenChild = document.createElement('option');
				hiddenChild.setAttribute('selected', '');
				hiddenChild.setAttribute('disabled', '');
				hiddenChild.setAttribute('value', '');
				hiddenChild.innerText='Please select an employee';

			$('#tito_emp')[0].appendChild(hiddenChild);

			$.ajax({
				type: 'post',
				url: '{{url('timekeeping/timelog-entry/find-emp-office')}}',
				data: {ofc_id: $(this).val()},
				success: function(data) {
					// console.log(typeof(data));
					if(data.length > 0) {
						for(i=0; i<data.length; i++) {
							var option = document.createElement('option');
								option.setAttribute('value', data[i].empid);
								option.innerText=data[i].name;

							$('#tito_emp')[0].appendChild(option);
						}
					}
				},
			});
		});

		$('#tito_id').on('input', function() {

			$('#empid').text('');
			$('select[name=office]').val('').trigger('change');

			if($('select[name="tito_emp"]').val($(this).val()).trigger('change').val() == null)	
				$('select[name="tito_emp"]').val('').trigger('change')

			$.ajax({
				type : 'post',
				url : '{{url('timekeeping/timelog-entry/find-id')}}',
				data : {id:$(this).val(), date_start:$('#tito_dateStrt').val(), date_to:$('#tito_dateEnd').val()},
				success: function(data) {
					table.clear().draw();
					if (data!="error") {
						if (data!="empty") {
							if(data.length > 0) {
								$('#empid').text('(Employee ID: '+data[0].empid+' )');
								$('select[name=office]').val(data[0].deptid).trigger('change');
								for(var i = 0 ; i < data.length; i++) {
									LoadTable(data[i]);
								}
							} 	
						} else {
							
						}
					} else {
						
					}
				},
			});

			setTimeout(function() {
				if($('select[name=office]').val() != null) {
					$('select[name=tito_emp]').val($('#tito_id').val()).trigger('change');
				}
			}, 500);
		});

		$('#dataTable').on('click', '.btn-edit', function() {
			var emp = $('#tito_emp').val();

			selected_row = $(this).parents('tr');
			$.ajax({
				type : 'post',
				url : '{{url('timekeeping/timelog-entry/get-log')}}',
				data : {id:emp,log:$(this).attr('data')},
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="no user") {
							if (data!="no record") {
								var log = JSON.parse(data);
								$('#frm-editlog').attr('action', '{{url('timekeeping/timelog-entry/edit-log')}}?logid='+log.logs_id);
								$('#date_workdate2').val(log.work_date);
								$('#time_timelog2').val(log.time_log);
								$('#sel_status2').val(log.status_type);
								$('#modal-editlog').modal('show');
							} else {
								alert("No record found. Refresh the table.");
							}
						} else {
							alert("Select an employee.");
						}
					} else {
						alert("Error in loading log.");
					}
				}
			});
		});

		function LoadTable(data) {
			table.row.add([
				data.work_date,
				data.time_log,
				data.status_desc,
				data.source_desc,
				'<button type="button" class="btn btn-success btn-edit mr-1" data="'+data.logs_id+'"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger btn-delete" data="'+data.logs_id+'"><i class="fa fa-trash"></i></button>'
			]).draw();
		}

		function RefreshRow(data) {
			table.row(selected_row).data([
				data.work_date,
				data.time_log,
				data.status_desc,
				data.source_desc,
				'<button type="button" class="btn btn-success btn-edit mr-1" data="'+data.logs_id+'"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger btn-delete" data="'+data.logs_id+'"><i class="fa fa-trash"></i></button>'
			]);
		}

		function onModalToggle(val) {
			if (val==null) {
				alert('Please select an Employee.');
				$('#modal-addlog').modal('hide');
			} else {
				$('#frm-addlog').attr('action','{{url('timekeeping/timelog-entry/add-log')}}?id='+val);
				$('#modal-addlog').modal('show');
			}
		}

		function toggleDeleteAllModal() {
			if ($('#tito_emp').val()!=null) {
				$('#frm-deleteAll').attr('action', '{{url('timekeeping/timelog-entry/delete-all-log')}}');
				$('#da-date1').html($('#tito_dateStrt').val());
				$('#da-date2').html($('#tito_dateEnd').val());
				$('#modal-deleteAll').modal('show');
			} else {
				alert("Select an employee");
			}
		}

		$('#frm-batchtimeloginfo').on('submit', function(e) {
			e.preventDefault();
			table.clear().draw();
			if ($('#tito_emp').val()==null) {
				alert("No employee selected");
			}
			$('#empid').html("(Employee ID: "+$('#tito_emp').val()+" )");
			$.ajax({
				type : this.getAttribute('method'),
				url : this.getAttribute('action'),
				data : $('#frm-batchtimeloginfo').serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="empty") {
							for(var i = 0 ; i < data.length; i++) {
								LoadTable(data[i]);
							}
						} else {
							alert("No record.");
						}
					} else {
						alert("Error in fetching data.");
					}
				}
			});
		});

		$('#frm-addlog').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type : this.getAttribute('method'),
				url : this.getAttribute('action'),
				data : $('#frm-addlog').serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						LoadTable(JSON.parse(data));
						alert("Time log added.");
					}
					else {
						alert("Error in Adding Log");
					}
				}
			});
			$('#modal-addlog').modal('hide');
			ClearFld();
		});

		$('#frm-editlog').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type : $(this).attr('method'),
				url : $(this).attr('action'),
				data : $(this).serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="no record") {
							alert("Log updated.");
							RefreshRow(JSON.parse(data));
						} else{
							alert("No record found.");
						}
					} else {
						alert('Error in updating log.');
					}
				}
			});
			$('#modal-editlog').modal('hide');
			ClearFld();
		});

		$('#frm-delete').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type : this.getAttribute('method'),
				url : this.getAttribute('action'),
				data : $('#frm-delete').serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						alert("Time Log Deleted.");
						table.row(selected_row).remove().draw();
						
					} else {
						alert("Error in Removing. Row was not deleted");
					}
				}
			});
			$('#modal-deletelog').modal('hide');
			ClearFld();
			
		});

		$('#frm-deleteAll').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type : this.getAttribute('method'),
				url : this.getAttribute('action'),
				data : $('#frm-batchtimeloginfo').serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data=="empty") {
						alert("No Record.");
					}
					if (data!="error") {
						table.clear().draw();
						alert(data);
					} else {
						alert("Error in Removing. Records were not deleted.");
					}
				}
			});
			$('#modal-deleteAll').modal('hide');
			ClearFld();
		});

		function ClearFld()
		{
			// Add Modal
			$('#frm-addlog').attr('action','#');
			$('#date_workdate').val('{{date('Y-m-d')}}');
			$('#time_timelog').val('{{date('H:i:s')}}');
			$('#sel_status').val('');

			// Edit Modal
			$('#frm-editlog').attr('action','#');
			$('#date_workdate2').val('');
			$('#time_timelog2').val('');
			$('#sel_status2').val('');

			// Modal - Delete
			$('#frm-delete').attr('action', '#');

			// Modal - Delete All
			$('#frm-deleteAll').attr('action', '#');
			$('#da-date1').html('');
			$('#da-date2').html('');
		}
	</script>
@endsection