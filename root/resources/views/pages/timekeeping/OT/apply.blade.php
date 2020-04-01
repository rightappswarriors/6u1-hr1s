@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-suitcase"></i>
			@if($current_loggedin != '001')
			Apply for OT <button type="button" class="btn btn-success" id="opt-add"><i class="fa fa-plus"></i> Add</button>
			@endif
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover" id="dataTable">
									<thead>
										<tr>
											<th>Application Code</th>
											<th>Applicant</th>
											<th>Is Finalized?</th>
											<th>Finalized By</th>
											<th>Is Approved?</th>
											<th>Approved By</th>
											<th>Current Options</th>
											{{-- <th>Apply for OT</th> --}}
										</tr>
									</thead>
									<tbody>
										@isset($list)
											@if(count($list)>0)
												@foreach($list as $pp)
												<tr data_id="{{$pp->otid}}" data_date="{{$pp->apply_date}}" data_remark="{{addslashes(trim($pp->apply_remark))}}">
													<td>{{$pp->otid}}</td>
													<td>{{$pp->firstname . ' ' . $pp->lastname}}</td>
													<td>{{(($pp->finalize_decision == 1 ? 'Yes' : 'No') ?? 'No Finalization yet')}}</td>
													<td>{{$pp->finalize_opr ?? '-'}}</td>
													<td>{{(($pp->approval_decision == 1 ? 'Yes' : 'No') ?? 'No Actions yet')}}</td>
													<td>{{$pp->approval_opr ?? '-'}}</td>
													<td>
														@if(!($pp->finalize_decision !== null || $pp->approval_decision !== null) )
														<button type="button" class="btn btn-primary mr-1" onclick="row_update(this)"><i class="fa fa-edit"></i></button>
														<button type="button" class="btn btn-danger" onclick="row_delete(this)"><i class="fa fa-trash"></i></button>
														@else
														Application on Process. Cannot do actions
														@endif
													</td>
													{{-- <td>{{$pp->dept_name}}</td> --}}
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
										<label>Application Date:</label>
										<input type="hidden" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX" required>
										<input type="date" name="txt_date" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX" required>
										<span class="error-span" id="error-span" hidden=""></span>
										<input type="text" name="txt_temp" hidden="">
									</div>
									<div class="form-group">
										<label>Remarks:</label>
										<textarea class="form-control" name="txt_name"  cols="50" rows="10"></textarea>
										{{-- <input type="text" name="txt_name" style="text-transform: uppercase;" class="form-control" placeholder="DESCRIPTION" required> --}}
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Apply for OT list?</p>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="frm-pp" class="btn btn-success" id="btn-save">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" {{-- onclick="ClearFld()" --}}>Close</button>
					</span>
					<span class="DeleteMode">
						<button type="submit" form="frm-pp" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" {{-- onclick="ClearFld()" --}}>Cancel</button>
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
		var table = $('#dataTable').DataTable(dataTable_short_ordered);
	</script>
	{{-- <script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script> --}}
	<script type="text/javascript">
		$('#opt-add').on('click', function(){
			$('#frm-pp').attr('action', '{{url('timekeeping/Apply-For-OT')}}');

			// $('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('[name="txt_name"]').val('');
			$('input[name="txt_date"]').val('');
			// $('select[name="txt_dept"]').val('').trigger('change');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		function row_update(obj) {
			var selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('timekeeping/Apply-For-OT')}}/update');
		
			// $('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('input[name="txt_date"]').val(selected_row.attr('data_date'));
			$('[name="txt_name"]').val(selected_row.attr('data_remark'));
			// $('select[name="txt_dept"]').val(selected_row.attr('data_dept')).trigger('change');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		};

		function row_delete(obj) {
			var selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('timekeeping/Apply-For-OT')}}/delete');
		
			// $('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('input[name="txt_date"]').val(selected_row.attr('data_date'));
			$('[name="txt_name"]').val(selected_row.attr('data_remark'));
			// $('select[name="txt_dept"]').val(selected_row.attr('data_dept')).trigger('change');
			$('#TOBEDELETED').text(selected_row.attr('data_id'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		};
	</script>
@endsection