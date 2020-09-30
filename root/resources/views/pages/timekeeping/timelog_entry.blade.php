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
			@isset($ref)
			<div class="mt-2 card">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<i class="fa fa-calendar"></i> Employee Biometric Error Data
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="container">
						<div class="row">
							<div class="col">
								<label>Date: {{Date('F, j, Y',strtotime($ref->date))}}</label>
							</div>
							<div class="col text-center">
								<label>Time: {{Date('g:iA',strtotime($ref->time))}}</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endisset

            <div class="card mt-2">
				<div class="card-header">
					<i class="fa fa-info-circle"></i> Batch Time Log Info
				</div>
				<div class="card-body">
					<form method="post" action="{{url('timekeeping/timelog-entry/batch-time-log-info')}}" id="frm-batchtimeloginfo">
						{{csrf_field()}}
						<div class="form-group form-inline">
							<input type="hidden" name="forGroup">
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

			            	<label hidden class="ml-5">Search by ID:</label>
							<input hidden type="text" name="tito_id" id="tito_id" class="form-control float-right ml-2" placeholder="Search by ID">
						</div>

						<div class="form-group form-inline">
							<label>Search Filters:</label>
						</div>
						<div class="form-group form-inline">
							<div class="row w-75 no-gutters">
								<div class="col ml-1 mr-3">
									<select class="form-control w-100" name="office" id="office" required>
										<option disabled selected value="">Please select an office</option>
										@if(!empty($data['offices']))
										@foreach($data['offices'] as $off)
										<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
										@endforeach
										@endif
									</select>
								</div>
								<div class="col col ml-1 mr-1">
									<select class="form-control w-100" name="tito_emp" id="tito_emp" required>
										<option disabled selected value="">Please select an employee</option>
									</select>
									{{-- <div class="row">
										<div class="col">
											<input type="text" name="tito_id" id="tito_id" class="form-control w-100" placeholder="Search by ID">
										</div>
									</div> --}}
								</div>
								<div class="col-1 d-none" id="loader-conainter">
									<div class="loader-circle"></div>
								</div>
								<div class="col-1 ml-2">
									<button type="submit" class="btn btn-primary" id="filters-submit">Go</button>
								</div>
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
								 	<th rowspan="2" width="10%" style="white-space: nowrap;" >Work Date</th>
					                <th style="text-align: center;" colspan="3">AM</th>
					                <th style="text-align: center;" colspan="3">PM</th>
					                <th></th>
					            </tr>
					            <tr>
					                <th width="10%">In</th>
					                <th width="10%">Out</th>
					                <th>Source</th>
					                <th width="10%">In</th>
					                <th width="10%">Out</th>
					                <th>Source</th>
					                <th>Option</th>
					            </tr>
								{{-- <tr>
									<th width="15%">Option</th>
								</tr> --}}
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
							<input type="time" class="form-control hasDatepicker" name="time_timelog" id="time_timelog" value="{{date('H:i:s')}}" required>
						</div>
						<div class="form-group">
							<label>Status</label>
							<select class="form-control hasDatepicker" name="sel_status" id="sel_status" required>
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
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit Log</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<form method="post" action="#" id="frm-editlog">
					<div class="row">
						<div class="col-sm-4 offset-4">
								<input type="text" placeholder="Work Date" class="form-control" name="date_workdate2" id="date_workdate2" required>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<center><strong>AM IN</strong></center>
							<input type="time" class="form-control" name="time_timelog2" id="time_timelog2" required>
							<select class="form-control" name="sel_status2" id="sel_status2" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
						<div class="col-sm-6">
							<center><strong>AM OUT</strong></center>
							<input type="time" class="form-control" name="time_timelog3" id="time_timelog3" required>
							<select class="form-control" name="sel_status3" id="sel_status3" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<center><strong>PM IN</strong></center>
							<input type="time" class="form-control" name="time_timelog4" id="time_timelog4" required>
							<select class="form-control" name="sel_status4" id="sel_status4" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
						<div class="col-sm-6">
							<center><strong>PM OUT</strong></center>
							<input type="time" class="form-control" name="time_timelog5" id="time_timelog5" required>
							<select class="form-control" name="sel_status5" id="sel_status5" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
					</div>
					<div class="row" style="padding-top: 2%;">
						<div class="col-sm-12" style="border: 1px solid;color: #D8000C;background-color: #FFBABA;border-radius: 3px;">
							
						<div style="padding: 15px 10px 15px 50px;background-repeat: no-repeat;	background-position: 10px center;background-image: url('https://i.imgur.com/GnyDvKN.png');">ERROR</div>
						<!--Diri ibutang ang mga errors kol-->
 						<div class="row" id="arikol">
							
						</div>
						</div>

					</div>
					{{-- <div class="row">
						<div class="col-sm-6 text-center"><strong>AM IN</strong></div><div class="col-sm-6 text-center"><strong>AM OUT</strong></div>
					</div>
					<div class="row">
			
						<div class="col-sm-6" style="border: 1px solid #ddd;border-radius: 6px;">
							<div style="padding: 1% 1% 0 1%;" class="form-group">
								<label>Work Date</label>
								<input type="text" class="form-control" name="date_workdate2" id="date_workdate2" required>
							</div>
						<div style="padding: 1% 1% 0 1%;" class="form-group">
							<label>Time Log</label>
							<input type="time" class="form-control" name="time_timelog2" id="time_timelog2" required>
						</div>
						<div style="padding: 1% 1% 0 1%;" class="form-group">
							<label>Status</label>
							<select class="form-control" name="sel_status2" id="sel_status2" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
						</div>
						
						<div class="col-sm-6" style="border: 1px solid #ddd;border-radius: 6px;">
							<div style="padding: 1% 1% 0 1%;" class="form-group">
								<label>Work Date</label>
								<input type="text" class="form-control" name="date_workdate3" id="date_workdate3" required>
							</div>
						<div style="padding: 1% 1% 0 1%;" class="form-group">
							<label>Time Log</label>
							<input type="time" class="form-control" name="time_timelog3" id="time_timelog3" required>
						</div>
						<div style="padding: 1% 1% 0 1%;" class="form-group">
							<label>Status</label>
							<select class="form-control" name="sel_status3" id="sel_status3" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
						</div>
					</div> --}}
					{{-- <div class="row">
						<div class="col-sm-6 text-center"><strong>PM IN</strong></div><div class="col-sm-6 text-center"><strong>PM OUT</strong></div>
					</div> --}}
					{{-- <div class="row">
						<div class="col-sm-6" style="border: 1px solid #ddd;border-radius: 6px;">
							<div style="padding: 1% 1% 0 1%;" class="form-group">
								<label>Work Date</label>
								<input type="text" class="form-control" name="date_workdate4" id="date_workdate4" required>
							</div>
						<div style="padding: 1% 1% 0 1%;" class="form-group">
							<label>Time Log</label>
							<input type="time" class="form-control" name="time_timelog4" id="time_timelog4" required>
						</div>
						<div style="padding: 1% 1% 0 1%;" class="form-group">
							<label>Status</label>
							<select class="form-control" name="sel_status4" id="sel_status4" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
						</div>
						<div class="col-sm-6" style="border: 1px solid #ddd;border-radius: 6px;">
							<div style="padding: 1% 1% 0 1%;" class="form-group">
								<label>Work Date</label>
								<input type="text" class="form-control" name="date_workdate5" id="date_workdate5" required>
							</div>
						<div style="padding: 1% 1% 0 1%;" class="form-group">
							<label>Time Log</label>
							<input type="time" class="form-control" name="time_timelog5" id="time_timelog5" required>
						</div>
						<div style="padding: 1% 1% 0 1%;" class="form-group">
							<label>Status</label>
							<select class="form-control" name="sel_status5" id="sel_status5" required>
								<option selected disabled value="">---</option>
								<option value="1">In</option>
								<option value="0">Out</option>
							</select>
						</div>
						</div>
					</div> --}}
				
						
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
		$( "#date_workdate2, #date_workdate3, #date_workdate4, #date_workdate5").datepicker(date_option);

		const OFFICE_EMPLOYEES = "OFFICE_EMPLOYEES";

		// add  $ prefix on jquery selector to differentiate from other variables
		var $filterLoader = $("#loader-conainter");
		var $filterSubmit = $("#filters-submit");
		var $filterSelectEmployee = $("#tito_emp");

		// will hold the fetched employees for faster display of previously loaded data
		var data = getLocalStorageItem(OFFICE_EMPLOYEES);
		var officeEmployees = data === null ? {} : data;

		function getLocalStorageItem (key) {
			return JSON.parse(window.localStorage.getItem(key));
		}

		function setLocalStorageItem(key, value) {
			window.localStorage.setItem(key, JSON.stringify(value));
		}
	</script>
	<script type="text/javascript">
		function formatAMPM2(time) {
			if(isNaN( parseInt(time) )){return time}
			if(time=="") return "";
			if(time=="<span class='text-danger'>missing</span>") return "";
			var timeString = time;
			var H = +timeString.substr(0, 2);
			var h = H % 12 || 12;
			var ampm = (H < 12 || H === 24) ? "am" : "pm";
			timeString = h + timeString.substr(2, 3) + ampm;
			return timeString;
		}

		// show/hide loader for search filter
		function showFilterLoader(show) {
			var displayNone = "d-none";
			var filterSubmitLoaderShow = "filter-submit-loader-show";

			if (show) {
				$filterLoader.removeClass(displayNone);
				$filterSubmit.addClass(filterSubmitLoaderShow); // fixes the submit button's spacing when loader is shown
			} else {
				$filterLoader.addClass(displayNone);
				$filterSubmit.removeClass(filterSubmitLoaderShow);
			}
		}

		function fillSelectEmployeeOptions(data) {
			if(data.length > 0) {
				for(i=0; i<data.length; i++) {
					var option = document.createElement('option');
					var firstname = data[i].firstname;
					var lastname = data[i].lastname;
					var mi = data[i].mi;
					var name = firstname + " " + mi + " " + lastname;

					option.setAttribute('value', data[i].empid);
					option.innerText=name;

					$filterSelectEmployee[0].appendChild(option);
				}
			}
		}

		function initFilterSelectEmployee() {
			// removes all select options
			$filterSelectEmployee.html("");

			var hiddenChild = document.createElement('option');
			hiddenChild.setAttribute('selected', '');
			hiddenChild.setAttribute('disabled', '');
			hiddenChild.setAttribute('value', '');
			hiddenChild.innerText='Please select an employee';

			$filterSelectEmployee[0].appendChild(hiddenChild);
		}

		var table = $('#dataTable').DataTable();
		var selected_row = "";
		$('#dataTable').on('click', '.btn-delete', function() {
			$('#modal-deletelog').modal('show');
			$('#frm-delete').attr('action', '{{url('timekeeping/timelog-entry/delete-log')}}?row='+$(this).attr('data'));
			selected_row = $(this).parents('tr');
		});

		$filterSelectEmployee.on('input', function() {
			$('#tito_id').val('');
		});

		$('#office').on('change', function() {
			var officeId = $(this).val();
			var employees = officeEmployees[officeId];

			showFilterLoader(true);
			initFilterSelectEmployee();

			// loads previously stored data for faster experience then do the query on background to update the stored data
			if (employees !== undefined && employees.length > 0) {
				fillSelectEmployeeOptions(employees);
				showFilterLoader(false);
			}

			$.ajax({
				type: 'post',
				url: '{{url('timekeeping/timelog-entry/find-emp-office')}}',
				data: {ofc_id: officeId},
				success: function(data) {
					showFilterLoader(false);
					initFilterSelectEmployee();

					// store data for later use
					officeEmployees[officeId] = data;
					setLocalStorageItem(OFFICE_EMPLOYEES, officeEmployees);

					fillSelectEmployeeOptions(data);
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
			}, 600);
		});

		$('#dataTable').on('click', '.btn-edit', function() {
			var emp = $filterSelectEmployee.val();
			let stringFix = '';

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
								let unfined = [];
								// $('#frm-editlog').attr('action', '{{url('timekeeping/timelog-entry/edit-log')}}?logid='+log.logs_id+'&empid='+emp);
								$('#frm-editlog').attr('action', '{{url('timekeeping/timelog-entry/edit-log')}}?empid='+emp);
								// $('#date_workdate2').val(log.work_date);
								// $('#time_timelog2').val(log.time_log);
								// $('#sel_status2').val(log.status).trigger('change');
								let suffix = '';
								$('#date_workdate2').val('');
								$('[id*="time_timelog"]:not(.hasDatepicker), [id*="sel_status"]:not(.hasDatepicker)' ).val('').removeAttr('name required').attr('disabled',true);
								for (var i = 0; i < log.length; i++) {
									let dataParsed = log[i];
									if(dataParsed['ampm'][1] == 'am'){
										if(dataParsed['status'] == '1' && dataParsed['ampm'][0] == '1'){
											suffix = '2';
										} else if(dataParsed['status'] == '0' && dataParsed['ampm'][0] == '0'){
											suffix = '3';
										} else {
											unfined.push(dataParsed);
										}
										// pm
									} else if(dataParsed['ampm'][1] == 'pm'){
										if(dataParsed['status'] == '1' && dataParsed['ampm'][0] == '1'){
											suffix = '4';
										} else if(dataParsed['status'] == '0' && dataParsed['ampm'][0] == '0'){
											suffix = '5';
										} else {
											unfined.push(dataParsed);
										}
									}
									// $('#date_workdate'+suffix).val(dataParsed['work_date']);
									if(suffix != ''){
										$('#time_timelog'+suffix).val(dataParsed['time_log']).trigger('change').attr({'name':'timelog['+dataParsed['logs_id']+'][]','required':true}).removeAttr('disabled');
										$('#sel_status'+suffix).val(dataParsed['status']).trigger('change').attr({'name':'timelog['+dataParsed['logs_id']+'][]','required':true}).removeAttr('disabled');
										$('#date_workdate2').val(dataParsed['work_date']);
									}
								}
								$('#modal-editlog').modal('show');
								$('#arikol').empty();
								if(unfined.length){
									for (var j = 0; j < unfined.length; j++) {
										stringFix += 
										'<div class="col-sm-6">'+
											'<input style="border: 1px solid #D8000C;" type="time" class="form-control" name="timelog['+unfined[j]['logs_id']+'][]" id="timelog['+unfined[j]['logs_id']+'][]" required value="'+unfined[j]['time_log']+'">'+
											'<select class="form-control" value="'+unfined[j]['status']+'" name="timelog['+unfined[j]['logs_id']+'][]" id="sel_status['+unfined[j]['logs_id']+'][]" required>'+
												'<option selected disabled value="">---</option>'+
												'<option value="1">In</option>'+
												'<option value="0">Out</option>'+
											'</select>'+
										'</div>'
										$('#arikol').append(stringFix);
										$('select[name="timelog['+unfined[j]['logs_id']+'][]"]').val(unfined[j]['status']).trigger('change');
									}
								}
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
			for (x in data) {
				let amin = '', amout = '', amsource = '', pmin = '', pmout = '', pmsource = '';
				let log_id = [];
				for (var i = 0; i < data[x].length; i++) {
					// am
					let dataParsed = data[x][i];
					log_id.push(dataParsed['logs_id']);
					if(dataParsed['ampm'][1] == 'am'){
						amsource = dataParsed['source_desc'];
						if(dataParsed['status'] == '1' && dataParsed['ampm'][0] == '1'){
							amin = formatAMPM2(dataParsed['time_log']);
						} else if(dataParsed['status'] == '0' && dataParsed['ampm'][0] == '0'){
							amout = formatAMPM2(dataParsed['time_log']);
						}
						// pm
					} else if(dataParsed['ampm'][1] == 'pm'){
						pmsource = dataParsed['source_desc'];
						if(dataParsed['status'] == '1' && dataParsed['ampm'][0] == '1'){
							pmin = formatAMPM2(dataParsed['time_log']);
						} else if(dataParsed['status'] == '0' && dataParsed['ampm'][0] == '0'){
							pmout = formatAMPM2(dataParsed['time_log']);
						}
					}
				}


				table.row.add([
					x,
					amin,
					amout,
					amsource,
					pmin,
					pmout,
					pmsource,
					'<button type="button" class="btn btn-success btn-edit mr-1" data="'+log_id.toString()+'"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger btn-delete" data="'+log_id.toString()+'"><i class="fa fa-trash"></i></button>'
				]).draw();
				amin = '', amout = '', amsource = '', pmin = '', pmout = '', pmsource = '';
				log_id = [];
			}
			
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
			if ($filterSelectEmployee.val()!=null) {
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
			if ($filterSelectEmployee.val()==null) {
				alert("No employee selected");
			}
			$('#empid').html("(Employee ID: " + $filterSelectEmployee.val() + " )");
			$.ajax({
				type : this.getAttribute('method'),
				url : this.getAttribute('action'),
				data : $('#frm-batchtimeloginfo').serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="empty") {
							// for(var i = 0 ; i < data.length; i++) {
							// 	LoadTable(data[i]);
							// }
							LoadTable(data);
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
					if (data!="error" && data!="exceed") {
						// LoadTable(JSON.parse(data));
						$('#frm-batchtimeloginfo').submit();
						alert("Time log added.");
					}
					else if('exceed'){
						alert("Time log for daily entry exceeded. Please edit entries already existed.");
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
							// RefreshRow(JSON.parse(data));
							$('#frm-batchtimeloginfo').submit();
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
						// table.row(selected_row).remove().draw();
						$('#frm-batchtimeloginfo').submit();
						
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

		@if(isset($misc[0]) && isset($misc[1]) && $misc[2])
		$(function(){
			$('#tito_dateStrt, #tito_dateEnd').val('{{$misc[0]}}').trigger('change');
			$('#office').val('{{$misc[1]}}').trigger('change');
			setTimeout(function() {
				$filterSelectEmployee.val('{{$misc[2]}}').trigger('change');
			}, 100);
			setTimeout(function() {
				$('#frm-batchtimeloginfo').submit();
			}, 500);
		})
		@endif
	</script>
@endsection