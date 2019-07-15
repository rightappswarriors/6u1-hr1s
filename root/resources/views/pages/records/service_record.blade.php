@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-book"></i> Service Record
		</div>
		<div class="card-body mb-2">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
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
						@isset($data)
							@foreach($data as $key => $value)
								<tr>
									{{-- <form method="post" action="#" id="frm-pp" data="#">
										@csrf --}}
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
										<td {{-- onclick="edit_remarks()" --}}>
											<textarea class="form-control" name="txt_remarks" rows="3" maxlength="50" oninput="save_remark('{{$value->sr_code}}', this)" required>{{trim($value->remarks)}}</textarea>
										</td>
									{{-- </form> --}}
								</tr>
							@endforeach
						@endisset
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script>
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