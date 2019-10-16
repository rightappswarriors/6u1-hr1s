@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-calendar"></i> Generate DTR 
			<div class="float-right">
				<a href="{{ url('timekeeping/timelog-entry') }}" class="btn btn-default btn-sm"><i class="fa fa-clock-o"></i> View Timelogs</a>
			</div>
		</div>
		<div class="card-body">
			<form method="post" action="{{url('timekeeping/generate-dtr/generate-dtr')}}" id="frm-pp" class="mb-2">
				<div class="form-inline mb-2">
					<div class="form-group mr-2">
						<label class="mr-1">Select Office:</label>
						<select class="form-control" name="payroll_dep" id="payroll_ofc">
							<option value="" selected="" disabled="">-no office selected-</option>
							@isset($data[3])
								@if(count($data[3]) > 0)
									@foreach($data[3] as $ofc)
									<option value="{{$ofc->cc_id}}">{{$ofc->cc_desc}}</option>
									@endforeach
								@endif
							@endisset
						</select>
					</div>
					<div class="form-group mr-2">
						<label class="mr-1">Employee Status</label>
						<select class="form-control" name="payroll_emp_stat" id="payroll_emp_stat">
							@isset($data[4])
								@if(count($data[4]) > 0)
									@foreach($data[4] as $empstatus)
									<option value="{{$empstatus->status_id}}">{{$empstatus->description}}</option>
									@endforeach
								@endif
							@endisset
						</select>
					</div>
				</div>
				<div class="form-inline">
					<div class="form-group mr-2">
						<label class="mr-1">Month:</label>
						<select class="form-control mr-2" name="payroll_month" id="payroll_month" required>
							@foreach(Core::Months() as $key => $value)
							<option value="{{$key}}" {{($key == date('m')) ? 'selected' : ''}}>{{$value}}</option>
							@endforeach
						</select>
						<label class="mr-1">Payroll Period:</label>
						<select class="form-control mr-2" name="payroll_period" id="payroll_period" required>
							<option value="15D">15th Day</option>
							<option value="30D">30th Day</option>
						</select>
						<select class="form-control YearSelector" name="payroll_year" id="payroll_year" required>
						</select>
					</div>
					<div class="form-group mr-2">
						<label class="mr-1">Generation Type</label>
						<select class="form-control" name="payroll_gen_type" id="payroll_gen_type">
							<option Value="BASIC" selected>Basic</option>
							<option value="OVERTIME">Overtime</option>
						</select>
					</div>
					<div class="form-group">
						{{-- <button type="button" class="btn btn-primary btn-spin mr-1" id="btn-generate-ind" disabled="">Generate (Individual)</button> --}}
						<button type="button" class="btn btn-primary" id="btn-generate-ofc"><i class="fa fa-share"></i> <i class="fa fa-server"></i> Generate (By Office)</button>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="card mb-2">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="card text-white bg-danger collapse mb-3" id="alert-generate-error">
								<div class="card-header">
									<i class="fa fa-exclamation"></i> Error
								</div>
								<div class="card-body" id="alert-generate-error-body"></div>
							</div>
						</div>
						<div class="col">
							<h6 id="hdr-tbl-employee">No office selected</h6>
							<div class="table-responsive mb-2">
								<table class="table table-bordered table-hover" id="dataTable-employee">
									<col width="15%">
									<col>
									<col width="25%">
									<col width="10%">
									<thead>
										<th>Employee ID</th>
										<th>Name</th>
										<th>Job Title</th>
										<th>Option</th>
									</thead>
									<tbody>
										{{-- @foreach($data[2] as $emp)
										<tr>
											<td>{{$emp->empid}}</td>
											<td>{{$emp->name}}</td>
										</tr>
										@endforeach --}}
									</tbody>
								</table>
							</div>
						</div>
						<div class="col">
							<h6 id="hdr-tbl-employee">DTR Summary Details: <span id="dtr_summary_loader" style="display: none;">Refreshing table. Please wait. <i class="fa fa-spin fa-spinner"></i></span></h6>
							<div class="table-responsive mb-2">
								<table class="table table-bordered">
									<col width="35%">
									<col>
									<tr>
										<th style="text-align: center;" colspan="2">Payroll Period</th>
										<td id="pp-dates" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">{{-- Total Overtime --}} Generate Type</th>
										<td id="sum-to" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">Total Workdays</th>
										<td id="sum-tw" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">Days Worked</th>
										<td id="sum-dw" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">Abscences</th>
										<td id="sum-a" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">Leave</th>
										<td id="sum-le" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">Holiday</th>
										<td id="sum-h" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">Total Hours</th>
										<td id="sum-th" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">Late</th>
										<td id="sum-l" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;" colspan="2">Undertime</th>
										<td id="sum-u" colspan="2"></td>
									</tr>
									<tr>
										<th style="text-align: center;">Generated</th>
										<td id="sum-stat"></td>
										<th style="text-align: center;">Flagged</th>
										<td id="sum-flagged"></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					Generated DTR History
					<div class="float-right">
						<button type="button" class="btn btn-light btn-sm" data-toggle="collapse" data-target="#card-history" aria-expanded="false"><i class="fa fa-chevron-down fa-sm"></i></button>
					</div>
				</div>
				<div class="card-body" id="card-history">
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
								@foreach($data[0] as $dh)
								<tr>
									<td>{{$dh->date_generated}}</td>
									<td>{{$dh->time_generated}}</td>
									<td>{{$dh->pp}}</td>
									<td>{{$dh->empname}}</td>
									<td>{{$dh->empid}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Modal -->
	<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Save DTR</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h4>Do you want to save generated employee's DTR?</h4>
				</div>
				<div class="modal-footer">
					<form method="post" action="#" id="frm-add">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="ClearFld()">No</button>
						<button type="submit" class="btn btn-primary" id="modal-add-submitbtn">Yes <i class="fa fa-spin fa-spinner" id="modal-add-submitbtn-loader" style="display: none;"></i></button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{url('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript">
		var maintable = $('#dataTable').DataTable(dataTable_config2);
		var tbl_emp = $('#dataTable-employee').DataTable(dataTable_short_ordered);
	</script>
	<script type="text/javascript">
		var selected_row = null;
		var dtr_summary = null;
		var emp_count = 0;
		tbl_emp.on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
			LoadDtrTable();
			SearchTable();
		});
	</script>
	<script type="text/javascript">
		function hideErrorDiv()
		{
			$('#alert-generate-error').hide();
			$('#alert-generate-error').collapse('hide');
			$('#alert-generate-error-body').empty();
		}
		function LoadDtrTable()
		{
			hideErrorDiv();
			$.ajax({
				type : 'get',
				url : '{{url('timekeeping/generate-dtr/partial-generation')}}',
				data : {
					code:selected_row.children()[0].innerText,
					pp:$('#payroll_period').val(),
					month: $('#payroll_month').val(),
					year: $('#payroll_year').val(),
					gtype : $('#payroll_gen_type').val()
				},
				dataTy : 'json',
				beforeSend : function() {
					$('#dtr_summary_loader').show();
					$('#modal-add-submitbtn').attr('disabled', true);
					$('#modal-add-submitbtn-loader').show();
				},
				success : function(data) {
					if (data!="error") {
						if (data!="noemp") {
							var d = JSON.parse(data);
							LoadSummaryTable(d);
							if (d.errors.length>0) {
								$('#alert-generate-error').collapse('show');
								for (var i=0; i<d.errors.length; i++) {
									$('#alert-generate-error-body').append('Missing time logs on '+d.errors[i]+'<br>');
								}
								$('#alert-generate-error-body').append(
									'<div class="card-body">'+
										'<a href="{{url('timekeeping/timelog-entry')}}" style="color:white;"><i class="fa fa-hand-o-right"></i> Check missing time logs</a>'+
									'</div>'
								)
								alert("DTR has errors. Cannot be saved.");
							}
						} else {
							alert("No employee selected.");
						}
					} else {
						alert("Error in generating DTR.");
					}
				},
				complete : function()
				{
					$('#dtr_summary_loader').hide();
					$('#modal-add-submitbtn').removeAttr('disabled');
					$('#modal-add-submitbtn-loader').hide();
				},
			});
		}

		function LoadSummaryTable(data)
		{
			
			$('#sum-tw').text(data.workdays+((data.req_hrs!=null) ? " ("+data.req_hrs+" hrs/day)" : ""));
			$('#sum-dw').text(data.daysworked);
			$('#sum-a').text(data.absences);
			$('#sum-l').text(data.late);
			$('#sum-u').text(data.undertime);
			$('#sum-th').text(data.weekdayhrs);
			$('#sum-h').text(data.holidays);
			$('#sum-le').text(data.leaves);
			var pt = $('#payroll_gen_type').val().toLowerCase();
			$('#sum-to').text(/*data.overtime*/ pt.charAt(0).toUpperCase() + pt.slice(1));
			$('#sum-stat').html((data.isgenerated==1) ? '<span class="btn btn-success">Yes</span>' : '<span class="btn btn-danger">No</span>');
			$('#sum-flagged').html((data.flag==true) ? '<span class="btn btn-success">Yes</span>' : '<span class="btn btn-danger">No</span>');
			dtr_summary = data;
			$('#pp-dates').text(data.date_from2+" to "+data.date_to2);
			hideErrorDiv();
		}

		function emptySummaryTable()
		{
			$('#sum-tw').text('');
			$('#sum-dw').text('');
			$('#sum-a').text('');
			$('#sum-l').text('');
			$('#sum-u').text('');
			$('#sum-to').text('');
			$('#sum-th').text('');
			$('#sum-h').text('');
			$('#sum-le').text('');
			$('#sum-stat').html('');
			$('#sum-flagged').html('');
			$('#pp-dates').text('');
			selected_row = null;
		}

		function LoadHistoryTable(data)
		{
			maintable.row.add([
				data.date_generated,
				data.time_generated,
				data.pp,
				data.empname,
				data.empid,
			]).draw();
		}

		function LoadEmployeeTable(data)
		{
			tbl_emp.row.add([
				data.empid,
				data.empname,
				data.jobtitle,
				'<button type="button" class="btn btn-primary btn-spin mr-1" onclick="GenerateIndv(this)"><i class="fa fa-share"></i> <i class="fa fa-server"></i></button>'
			]).draw();
			hideErrorDiv();
		}

		// $('#frm-pp').on('submit', function(e) {
		// 	e.preventDefault();
			
		// });

		function ClearFld()
		{
			$('#frm-add').attr('action', '#');
		}

		function SearchTable()
		{
			maintable.search(selected_row.children()[1].innerText).draw();
		}

		function onToggleSaveDTRModal_ind()
		{
			$('#frm-add').attr('action', '{{url('timekeeping/generate-dtr/save-dtr')}}?code='+selected_row.children()[0].innerText+'&pp='+$('#payroll_period').val()+'&ofc_id='+$('#payroll_ofc').val()+'&month='+$('#payroll_month').val()+'&year='+$('#payroll_year').val()+'&empstat='+$('#payroll_emp_stat').val()+'&gtype='+$('#payroll_gen_type').val());
			$('#modal-add').modal('show');
		}

		function onToggleSaveDTRModal_ofc()
		{
			$('#frm-add').attr('action', '{{url('timekeeping/generate-dtr/save-dtr/by-department')}}?ppid='+$('#payroll_period').val()+'&ofc_id='+$('#payroll_ofc').val()+'&month='+$('#payroll_month').val()+'&year='+$('#payroll_year').val()+'&empstat='+$('#payroll_emp_stat').val()+'&gtype='+$('#payroll_gen_type').val());
			$('#modal-add').modal('show');
		}

		// $('#modal-add').on('hidden.bs.modal', function() {
		// 	RemoveSpinningIcon();
		// });

		$('#frm-add').on('submit', function(e) {
			e.preventDefault();
			$('#modal-add').modal('hide');
			$.ajax({
				type : $(this).attr('method'),
				url : $(this).attr('action'),
				data : {dtrs:dtr_summary},
				dataTy : 'json',
				beforeSend : function()
				{
					togglePreloader();
				},
				success : function(data) {
					console.log(data);
					var a = data[0];
					var b = data[1];
					var parse = null;
					emptySummaryTable();
					togglePreloader();
					if (b=="indv") {
						if (a!="error") {
							if (a!="existing-error") {
								if (a!="max") {
									parse = JSON.parse(a[0]);
									if (a[1]=="isgenerated") {
										alert("Payroll period is already generated. DTR cannot be re-generated.");
									} else {
										alert("DTR Generated (Individual).");
									}
									maintable.clear().draw();
									for (var i = 0; i < parse.length; i++) {
										LoadHistoryTable(parse[i]);
									}
									$('#sum-stat').html('<span class="btn btn-success">Yes</span>');
									SearchTable();
								} else {
									alert("DTR is already generated. Cannot generated DTR again. Failed on saving.");
								}
							} else {
								alert("There still errors on the timelog entry. Unable to save DTR summary.");
							}
						} else {
							alert("Error in saving DTR.");
						}
					}
					else if (b=="group") {
						var error = new Array();
						var e_num = 0;
						console.log(Array.isArray(a));
						if (Array.isArray(a)) {
							if (a.length > 0) {
								for (var i = 0; i < a.length; i++) {
									var c = a[i]; e_num++;
									if (c=="error") {
										error.push((e_num)+".) "+"Error on saving. (Line no."+i+")");
									} else if (c=="existing-error") {
										error.push((e_num)+".)"+"There still errors on the timelog entry. Unable to save DTR summary. (Line no."+i+")");
									} else if (c=="max") {
										error.push((e_num)+".)"+"DTR is already generated. Cannot generated DTR again. Failed on saving. (Line no."+i+")");
									} else if (Array.isArray(c)) {
										d = c[1];
										if (d != "ok") {
											error.push((e_num)+".) "+"An error occured. Cannot save DTR. (Line no."+i+")");
										}
									}
								}
							}
						} else {
							if (a=='error') {
								alert("Error on saving.");
							} else if(a=="no-employees") {
								alert("There are no employees on the selected office/employee status.");
							}
						}
						if (error.length > 0) {
							$('#alert-generate-error').show();
							for (var i = 0; i < error.length; i++) {
								$('#alert-generate-error-body').append(error[i]+'<br>');
							}
							alert("There are errors when generating.");
						} else {
							alert("DTR Generated (Group).");
						}
					}
				},
				error : function() {
					togglePreloader();
					alert("Error on saving DTR. Please try again later.");
				}
			});
		});

		$('#btn-generate-ind').on('click', function() {
			
		});

		function GenerateIndv(obj)
		{
			selected_row = $($(obj).parents()[1]);
			if (selected_row!=null) {
				onToggleSaveDTRModal_ind();
			} else {
				$('#spinning-icon').hide();
				alert("Please select an employee.");
			}
		}

		$('#btn-generate-ofc').on('click', function() {
			if ($('#payroll_ofc').val()!=null && $('#payroll_emp_stat').val()!=null) {
				if (emp_count > 0) {
					onToggleSaveDTRModal_ofc();
				} else {
					$('#spinning-icon').hide();
					alert("No employees");
				}
			} else {
				$('#spinning-icon').hide();
				alert("Please select an office.");
			}
		});

		$('#payroll_month').on('change', function() {
			// if (selected_row!=null) {
			// 	LoadDtrTable();
			// }
			$('#payroll_period').val('15D').trigger('change');
			SearchEmployees();
			emptySummaryTable();
		});

		$('#payroll_period').on('change', function() {
			// if (selected_row!=null) {
			// 	LoadDtrTable();
			// }
			SearchEmployees();
			emptySummaryTable();
		});

		$('#payroll_year').on('change', function() {
			// if (selected_row!=null) {
			// 	LoadDtrTable();
			// }
			SearchEmployees();
			emptySummaryTable();
		});

		$('#payroll_ofc').on('change', function() {
			emp_count = 0;
			SearchEmployees();
			emptySummaryTable();
		});

		$('#payroll_emp_stat').on('change', function() {
			emp_count = 0;
			SearchEmployees();
			emptySummaryTable();
		});

		$('#payroll_gen_type').on('change', function() {
			emp_count = 0;
			SearchEmployees();
			emptySummaryTable();
		});

		function SearchEmployees()
		{
			tbl_emp.clear().draw();
			hideErrorDiv();
			if ($('#payroll_ofc').val()==null || $('#payroll_emp_stat').val()==null) {
				$('#hdr-tbl-employee').text("No office selected");
			} else {
				$.ajax({
					type : 'get',
					url : '{{url('master-file/office/get-employees')}}',
					data : {
						ofc_id : $('#payroll_ofc').val(),
						emp_status : $('#payroll_emp_stat').val()
					},
					dataTy : 'json',
					beforeSend : function()
					{
						$('#hdr-tbl-employee').html('Loading Employees <i class="fa fa-spin fa-spinner"></i>');
					},
					success : function(data) {
						// console.log(data);
						var d = JSON.parse(data);
						emp_count = d.length;
						for (var i = 0; i < d.length; i++) {
							LoadEmployeeTable(d[i]);
						}
					},
					error : function()
					{
						$('#hdr-tbl-employee').html('Unable to retrieve employees.');
						alert('An error occured. please reload the page.');
					},
					complete : function()
					{
						$('#hdr-tbl-employee').html('Employees in "'+$('#payroll_ofc option:selected').text().toUpperCase()+'"');
					},
				});
			}
		}
	</script>
@endsection