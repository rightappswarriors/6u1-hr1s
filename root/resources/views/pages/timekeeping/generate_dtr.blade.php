@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-calendar"></i> Generate DTR 
		</div>
		<div class="card-body">
			<div class="card mb-2">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<div class="table-responsive mb-2">
								<table class="table table-bordered table-hover" id="dataTable-employee">
									<thead>
										<th>Employee ID</th>
										<th>Name</th>
									</thead>
									<tbody>
										@foreach($data[2] as $emp)
										<tr>
											<td>{{$emp->empid}}</td>
											<td>{{$emp->name}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
						<div class="col">
							<form method="post" action="{{url('timekeeping/generate-dtr/generate-dtr')}}" id="frm-pp" class="mb-2">
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
											<option value="15D" {{(date('d') <= 15) ? 'selected' : ''}}>15th Day</option>
											<option value="30D" {{(date('d') > 15) ? 'selected' : ''}}>30th Day</option>
										</select>
										<select class="form-control YearSelector" name="payroll_year" id="payroll_year" required>
										</select>
									</div>
									<div class="form-group">
										<button type="button" class="btn btn-primary btn-spin" id="btn-generate">Generate</button>
									</div>
								</div>
							</form>
							<div class="table-responsive mb-2">
								<table class="table table-bordered">
									<col width="35%">
									<col>
									<tr>
										<th style="text-align: center;">Payroll Period</th>
										<td id="pp-dates"></td>
									</tr>
									<tr>
										<th style="text-align: center;">Total Workdays</th>
										<td id="sum-tw"></td>
									</tr>
									<tr>
										<th style="text-align: center;">Days Worked</th>
										<td id="sum-dw"></td>
									</tr>
									<tr>
										<th style="text-align: center;">Abscences</th>
										<td id="sum-a"></td>
									</tr>
									<tr>
										<th style="text-align: center;">Late</th>
										<td id="sum-l"></td>
									</tr>
									<tr>
										<th style="text-align: center;">Undertime</th>
										<td id="sum-u"></td>
									</tr>
									<tr>
										<th style="text-align: center;">Total Overtime</th>
										<td id="sum-to"></td>
									</tr>
									<tr>
										<th style="text-align: center;">Generated</th>
										<td id="sum-stat"></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-12">
							<div class="card text-white bg-danger collapse" id="alert-generate-error">
								<div class="card-header">
									<i class="fa fa-exclamation"></i> Error
								</div>
								<div class="card-body" id="alert-generate-error-body"></div>
								<div class="card-body">
									<a href="{{url('timekeeping/timelog-entry')}}" style="color:white;"><i class="fa fa-hand-o-right"></i> Check missing time logs</a>
								</div>
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
						<button type="submit" class="btn btn-primary">Yes</button>
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
		tbl_emp.on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
			LoadDtrTable();
			SearchTable();
		});
	</script>
	<script type="text/javascript">
		function LoadDtrTable()
		{
			$('#alert-generate-error').collapse('hide');
			$('#alert-generate-error-body').empty();
			$('#btn-generate').attr('disabled', false);
			$.ajax({
				type : 'get',
				url : '{{url('timekeeping/generate-dtr/partial-generation')}}',
				data : {
					code:selected_row.children()[0].innerText,
					pp:$('#payroll_period').val(),
					month: $('#payroll_month').val(),
					year: $('#payroll_year').val()
				},
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						var d = JSON.parse(data);
						LoadSummaryTable(d);
						if (d.errors.length>0) {
							$('#alert-generate-error').collapse('show');
							for (var i=0; i<d.errors.length; i++) {
								$('#alert-generate-error-body').append('Missing time logs on '+d.errors[i]+'<br>');
							}
							$('#btn-generate').attr('disabled', true);
							alert("DTR has errors. Cannot be saved.");
						}
					} else {
						alert("Error in generating DTR.");
					}
				}
			});
		}

		function LoadSummaryTable(data)
		{
			$('#sum-tw').text(data.workdays);
			$('#sum-dw').text(data.daysworked);
			$('#sum-a').text(data.absences);
			$('#sum-l').text(data.late);
			$('#sum-u').text(data.undertime);
			$('#sum-to').text(data.overtime);
			$('#sum-stat').html((data.isgenerated==1) ? '<span class="btn btn-success">Yes</span>' : '<span class="btn btn-danger">No</span>');
			dtr_summary = data;
			$('#pp-dates').text(data.date_from2+" to "+data.date_to2);
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

		function onToggleSaveDTRModal()
		{
			$('#frm-add').attr('action', '{{url('timekeeping/generate-dtr/save-dtr')}}?empid='+selected_row.children()[0].innerText+"&ppid="+$('#payroll_period').val());
			$('#modal-add').modal('show');
		}

		$('#modal-add').on('hidden.bs.modal', function() {
			RemoveSpinningIcon();
		});

		$('#frm-add').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type : $(this).attr('method'),
				url : $(this).attr('action'),
				data : {dtrs:dtr_summary},
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="existing-error") {
							if (data!="max") {
								if (data[1]=="isgenerated") {
									alert("Payroll period is already generated. DTR cannot be re-generated.");
								} else {
									alert("DTR Generated.");
								}
								var parse = JSON.parse(data[0]);
								maintable.clear().draw();
								for (var i = 0; i < parse.length; i++) {
									LoadHistoryTable(parse[i]);
								}
								$('#sum-stat').html('<span class="btn btn-success">Yes</span>');
								SearchTable();
							} else {
								alert("DTR is already generated. Cannot generated DTR again.");
								alert("Failed on saving.");
							}
						} else {
							alert("There still errors on the timelog entry. Unable to save DTR summary.");
						}
					} else {
						alert("Error in saving DTR.");
					}
					RemoveSpinningIcon();
				}
			});
			$('#modal-add').modal('hide');
		});

		$('#btn-generate').on('click', function() {
			if (selected_row!=null) {
				onToggleSaveDTRModal();
			} else {
				alert("Please select an employee.");
			}
		});

		$('#payroll_period').on('change', function() {
			if (selected_row!=null) {
				LoadDtrTable();
			}
		});

		$('#payroll_month').on('change', function() {
			if (selected_row!=null) {
				LoadDtrTable();
			}
		});

		$('#payroll_year').on('change', function() {
			if (selected_row!=null) {
				LoadDtrTable();
			}
		});
	</script>
@endsection