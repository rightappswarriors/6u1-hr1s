@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-building"></i> Leave Types
			<button type="button" class="btn btn-success" id="opt-add">
				<i class="fa fa-plus"></i> Add
			</button>
			<button type="button" class="btn btn-info" id="opt-print">
				<i class="fa fa-print"></i> Print List
			</button>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover table-bordered" id="dataTable">
									<thead>
										<tr>
											<th>Code</th>
											<th>Name</th>
											<th>Limit</th>
											<th>Carry Over</th>
											<th>Incrementing Monthly?</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@isset($otherearnings)
											@if(count($otherearnings)>0)
												@foreach($otherearnings as $pp)
												<tr data_id="{{$pp->code}}" data_name="{{$pp->description}}" data_le_lmt="{{$pp->leave_limit}}" data_carry_over="{{$pp->carry_over}}" data_increment="{{($pp->incremental ? 'Yes' : 'No')}}">
													<td>{{$pp->code}}</td>
													<td>{{$pp->description}}</td>
													<td>{{$pp->leave_limit}}</td>
													<td>{{($pp->carry_over == 'Y') ? 'Yearly' : 'Monthly'}}</td>
													<td>{{($pp->incremental ? 'Yes' : 'No')}}</td>
													<td>
														<button type="button" class="btn btn-primary mr-1" id="opt-update" onclick="row_update(this)">
															<i class="fa fa-edit"></i>
														</button>
														<button type="button" class="btn btn-danger" id="opt-delete" onclick="row_delete(this)">
															<i class="fa fa-trash"></i>
														</button>
													</td>
												</tr>
												@endforeach
											@endif
										@endisset
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				{{-- <div class="col-3">
					<div class="card">
						<div class="card-body">
							<button type="button" class="btn btn-success btn-block" id="opt-add"><i class="fa fa-plus"></i> Add</button>
							<button type="button" class="btn btn-primary btn-block" id="opt-update"><i class="fa fa-edit"></i> Edit</button>
							<button type="button" class="btn btn-danger btn-block" id="opt-delete"><i class="fa fa-trash"></i> Delete</button>
							<button type="button" class="btn btn-info btn-block" id="opt-print"><i class="fa fa-print"></i> Print List</button>
						</div>
					</div>
				</div> --}}
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Modal -->
	<div class="modal fade" id="modal-pp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-pp" data="#">
						@csrf
						<span class="AddMode">
							<div class="row">
								<div class="col"> <!-- Column 1 -->
									<div class="form-group">
										<label>Code:</label>
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX" onkeyup="this.value = this.value.toUpperCase()" required>
									</div>
									<div class="form-group">
										<label>Name:</label>
										<input type="text" name="txt_name" class="form-control" placeholder="Description" required>
									</div>
									<div class="form-group">
										<label>Leave Limit:</label>
										<input step="any" type="number" name="txt_limit" step="0.01" class="form-control" placeholder="Leave Limit" required>
									</div>
									<div class="form-group">
										<label>Carry Over:</label>
										<select name="txt_carry_over" class="form-control" required="">
											<option value="">Select Carry Over..</option>
											<option value="M">Monthly</option>
											<option value="Y">Yearly</option>
										</select>
									</div>
									<div class="form-group">
										<label>Incremental Per Month?</label>
										<select name="increment" class="form-control" required="">
											<option value="Yes">Yes</option>
											<option value="No" selected="selected">No</option>
										</select>
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Leave Types list?</p>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="frm-pp" class="btn btn-success">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="ClearFld()">Close</button>
					</span>
					<span class="DeleteMode">
						<button type="submit" form="frm-pp" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" onclick="ClearFld()">Cancel</button>
					</span>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{asset('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript">
		$('#date_from').datepicker(date_option5);
		$('#date_to').datepicker(date_option5);
		var table = $('#dataTable').DataTable();
	</script>
	<script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script>
	<script type="text/javascript">
		function LoadDatable()
		{
			data.row.add([
				data.pay_code,
				data.date_from,
				data.date_to
			]).draw();
		}
		$('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/leave-types')}}');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('input[name="txt_name"]').val('');
			$('input[name="txt_limit"]').val('');
			$('select[name="txt_carry_over"]').val('').trigger('change');
			$('[name=increment]').val('No').trigger('change');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		// $('#opt-update').on('click', function() {
		function row_update(obj) {
			selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/leave-types')}}/update');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('input[name="txt_limit"]').val(selected_row.attr('data_le_lmt'));
			$('select[name="txt_carry_over"]').val(selected_row.attr('data_carry_over')).trigger('change');
			$('[name=increment]').val(selected_row.attr('data_increment')).trigger('change');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		// });
		}

		// $('#opt-delete').on('click', function() {
		function row_delete(obj) {
			selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/leave-types')}}/delete');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('#TOBEDELETED').text(selected_row.attr('data_name'));
			$('input[name="txt_limit"]').val(selected_row.attr('data_le_lmt'));
			$('select[name="txt_carry_over"]').val(selected_row.attr('data_carry_over')).trigger('change');
			$('[name=increment]').val('').val(selected_row.attr('data_increment')).trigger('change');

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		// });
		}
	</script>
@endsection