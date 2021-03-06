@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-building"></i> Employee Status <button type="button" class="btn btn-success" id="opt-add"><i class="fa fa-plus"></i> Add</button>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover" id="dataTable">
									<col>
									<col>
									{{-- <col> --}}
									<col width="10%">
									<thead>
										<tr>
											<th>Status Code</th>
											<th>Name</th>
											{{-- <th>Type</th> --}}
											<th></th>
										</tr>
									</thead>
									<tbody>
										@isset($dept)
											@if(count($dept)>0)
												@foreach($dept as $pp)
												<tr data_id="{{$pp->statcode}}" data_name="{{$pp->description}}" data_type="{{$pp->type}}">
													<td>{{$pp->statcode}}</td>
													<td>{{$pp->description}}</td>
													{{-- <td>{{$pp->type}}</td> --}}
													<td>
														<button type="button" class="btn btn-primary mr-1" id="opt-update" onclick="row_update(this)"><i class="fa fa-edit"></i></button>
														<button type="button" class="btn btn-danger" id="opt-delete" onclick="row_delete(this)"><i class="fa fa-trash"></i></button>
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
										<label>Status Code:</label>
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="3" placeholder="XXX" required>
									</div>
									<div class="form-group">
										<label>Name:</label>
										<input type="text" name="txt_name" style="text-transform: uppercase;" class="form-control" placeholder="DESCRIPTION" required>
									</div>
									{{-- <div class="form-group">
										<label>Type:</label>
										<select name="cbo_type" class="form-control" required>
											<option value="" disabled readonly selected>-- Select a Type --</option>
											<option value="es">Employee Status</option>
											<option value="et">Employee Type</option>
										</select>
									</div> --}}
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Employee Status list?</p>
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
		var table = $('#dataTable').DataTable(date_option_min);
	</script>
	<script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script>
	<script type="text/javascript">
		$('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/employee-status')}}');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('input[name="txt_name"]').val('');
			$('select[name="cbo_type"]').val('').trigger('change');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		function row_update(obj) {
			var selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/employee-status')}}/update');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('select[name="cbo_type"]').val(selected_row.attr('data_type')).trigger('change');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		};

		function row_delete(obj) {
			var selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/employee-status')}}/delete');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('select[name="cbo_type"]').val(selected_row.attr('data_type')).trigger('change');
			$('#TOBEDELETED').text(selected_row.attr('data_name'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		};
	</script>
@endsection