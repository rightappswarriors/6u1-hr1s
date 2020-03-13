@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-building"></i> OB Entry
			{{-- <i class="fa fa-clock-o"></i> Witholding Tax --}}
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
									<col>
									<col>
									<col width="15%">
									<thead>
										<tr>
											<th>Code</th>
											<th>Name</th>
											<th>Date From</th>
											<th>Date To</th>
											<th>Remarks</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@isset($ob)
											@if(count($ob)>0)
												@foreach($ob as $pp)
												<tr data_id="{{$pp->obid}}" data-empid="{{$pp->empid}}" data-from="{{$pp->datefrom}}" data-to="{{$pp->dateto}}" data-remark="{{addslashes($pp->remark)}}">
													<td>{{$pp->obid}}</td>
													<td>{{$pp->firstname . ' ' . $pp->lastname}}</td>
													<td>{{$pp->datefrom}}</td>
													<td>{{$pp->dateto}}</td>
													<td>{{$pp->remark}}</td>
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
	<div class="modal fade" id="modal-pp" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
										<label>Employee:</label>
										<select name="empid" id="empid" required>
											<option value="">Please Select</option>
											@isset($employee)
											@foreach($employee as $e)
											<option value="{{$e->empid}}">{{$e->firstname . ' ' . $e->lastname}}</option>
											@endforeach
											@endisset
										</select>
									</div>
									<div class="form-group">
										<label>Date From:</label>
										<input type="date" name="datefrom" class="form-control" required>
									</div>
									<div class="form-group">
										<label>Date To:</label>
										<input type="date" name="dateto" class="form-control" required>
									</div>
									<div class="form-group">
										<label>Remark:</label>
										<textarea class="form-control" name="txt_limit" id="txt_limit" cols="30" rows="10"></textarea>
									</div>
									<input type="hidden" name="txt_code">
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Loan Type list?</p>
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
			$('#frm-pp').attr('action', '{{url('timekeeping/OB-Entry')}}');
			$('[name="empid"], [name="datefrom"], [name="dateto"]').attr('required',true);
			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('[name="empid"]').val('').trigger('change');
			$('[name="txt_limit"]').val('');
			$('[name="datefrom"]').val('');
			$('[name="dateto"]').val('');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		// $('#opt-update').on('click', function() {
		function row_update(obj) {
			selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('timekeeping/OB-Entry')}}/update');
		
			$('[name="txt_code"]').attr('readonly', '');
			$('[name="txt_code"]').val(selected_row.attr('data_id')).trigger('change');
			$('[name="empid"]').val(selected_row.attr('data-empid')).trigger('change');
			$('[name="txt_limit"]').val(selected_row.attr('data-remark'));
			$('[name="datefrom"]').val(selected_row.attr('data-from'));
			$('[name="dateto"]').val(selected_row.attr('data-to'));

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		// });
		}

		// $('#opt-delete').on('click', function() {
		function row_delete(obj) {
			selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('timekeeping/OB-Entry')}}/delete');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('#TOBEDELETED').text(selected_row.attr('data_name'));
			$('[name="empid"], [name="datefrom"], [name="dateto"]').removeAttr('required');

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		// });
		}
	</script>
@endsection