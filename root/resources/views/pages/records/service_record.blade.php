@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col-2">
					<i class="fa fa-fw fa-book"></i> Service Record
				</div>
				<div class="col">
					<select class="form-control w-25" name="office" id="office" required>
						<option disabled selected value="">Please select an office</option>
						@if(!empty($data[1]))
						@foreach($data[1] as $off)
						<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div>
		</div>
		<div class="card-body mb-2">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable1">
					<col width="9%">
					<col width="9%">
					<col width="8%">
					<col width="15%">
					<col width="10%">
					<col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="5%">
					<col width="10%">
					{{-- 96% total --}}
					<thead>
						<th>From</th>
						<th>To</th>
						<th>ID</th>
						<th>Employee</th>
						<th>Designation</th>
						<th>Status</th>
						<th>Salary</th>
						<th>Branch</th>
						<th>Leave w/o pay</th>
						<th>Remarks</th>
					</thead>
					<tbody>
						{{-- @isset($data[0])
							@foreach($data[0] as $key => $value)
								<tr>
									
										<input type="hidden" name="txt_sr_code" value="{{$value->sr_code}}">
										<td>{{trim(\Carbon\Carbon::parse($value->service_from)->format('M d, Y'))}}</td>
										<td>{{trim(\Carbon\Carbon::parse($value->service_to)->format('M d, Y'))}}</td>
										<td>{{trim($value->empid)}}</td>
										<td>{{trim($value->employee_name)}}</td>
										<td>{{JobTitle::Get_JobTitle(trim($value->designation))}}</td>
										<td>{{trim($value->status)}}</td>
										<td>{!!Core::currSign().trim($value->salary)!!}</td>
										<td>{{Employee::GetDepartment($value->branch)}}</td>
										<td>{{trim($value->leave_wo_pay)}}</td>
										<td>
											<textarea class="form-control" name="txt_remarks" rows="3" maxlength="50" oninput="save_remark('{{$value->sr_code}}', this)" required>{{trim($value->remarks)}}</textarea>
										</td>
									
								</tr>
							@endforeach
						@endisset --}}
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script>
		var table = $('#dataTable1').DataTable({
			"paging": false
		});

		$('#office').on('change', function() {
			$.ajax({
				type: "post",
				url: "{{url('records/service-record/find')}}",
				data: {"ofc_id":$(this).val()},
				success: function(response) {
					table.clear().draw();
					for(i=0; i<response.length; i++) {
						FillTable(response[i]);
					}
				},
			});
		});

		function FillTable(d) {
			table.row.add([
				d.date_from_readable,
				d.date_to_readable,
				d.empid,
				d.employee_name,
				d.designation_readable,
				d.status,
				d.salary,
				d.branch_readable,
				d.lwp_readable,
				'<textarea class="form-control" name="txt_remarks" rows="3" maxlength="50" oninput="save_remark(\''+d.sr_code+'\', this)" required>'+d.remarks_readable+'</textarea>',
			]).draw();
		}

		function save_remark(sr_code, remarks) {
			remarks.style.height = "1px";
			remarks.style.height = (25+remarks.scrollHeight)+"px";
			$.ajax({
				type: "post",
				url: "{{url('records/service-record/')}}",
				data: {"sr_code":sr_code, "remarks":remarks.value,},
				success: function(response) {
					// location.reload();
				}
			});
		}
	</script>


	{{-- <script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');

			edit_remarks();
		});
	</script>

	<script>
		function edit_remarks() {
			console.log(selected_row.attr('employee_name'));
			$('#modal-pp').modal('show');

			$('#exampleModalLabel').html(selected_row.attr('employee_name'));
			$('textarea[name="txt_remarks"]').val(selected_row.attr('remarks'));
		}
	</script> --}}

@endsection