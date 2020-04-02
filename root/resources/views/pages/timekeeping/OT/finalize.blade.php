@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-suitcase"></i> {{$title ?? ''}}
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
											<th>Application Date</th>
											<th>Remark</th>
											<th>Action</th>
											{{-- <th>Apply for OT</th> --}}
										</tr>
									</thead>
									<tbody>
										@isset($list)
											@if(count($list)>0)
												@foreach($list as $pp)
												<tr data_id="{{$pp->otid}}">
													<td>{{$pp->otid}}</td>
													<td>{{$pp->firstname . ' ' . $pp->lastname}}</td>
													<td>{{$pp->apply_date}}</td>
													<td>{{addslashes(trim($pp->apply_remark))}}</td>
													{{-- <td>{{$pp->finalize_opr ?? '-'}}</td>
													<td>{{(($pp->approval_decision == 1 ? 'Yes' : 'No') ?? 'No Actions yet')}}</td>
													<td>{{$pp->approval_opr ?? '-'}}</td> --}}
													<td>
														<button title="Approve Application" type="button" class="btn btn-primary mr-1" onclick="row_delete([this,1])"><i class="fa fa-check"></i></button>
														<button type="button" title="Disapprove Application" class="btn btn-danger" onclick="row_delete([this,0])"><i class="fa fa-times"></i></button>
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
										<input type="hidden" name="actionrequired" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX" required>
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
							<p>Are you sure you want to <span id="action" class="font-weight-bold"></span> <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Apply for OT list?</p>
							<label class="font-weight-bold">Remarks:</label>
							<textarea name="remark" class="form-control" cols="30" rows="10"></textarea>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="frm-pp" class="btn btn-success" id="btn-save">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" {{-- onclick="ClearFld()" --}}>Close</button>
					</span>
					<span class="DeleteMode">
						<button type="submit" form="frm-pp" class="btn btn-success">Update</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" {{-- onclick="ClearFld()" --}}>Cancel</button>
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
		function row_delete(obj) {
			var selected_row = $($(obj[0]).parents()[1]);
			// $('#frm-pp').attr('action', '{{url('timekeeping/Finalize-OT')}}');
			$('#action').html((obj[1] ? 'Approve' : 'Disapprove'));
			$('[name=actionrequired]').val(obj[1]);
			// $('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			// $('input[name="txt_date"]').val(selected_row.attr('data_date'));
			// $('[name="txt_name"]').val(selected_row.attr('data_remark'));
			// $('select[name="txt_dept"]').val(selected_row.attr('data_dept')).trigger('change');
			// $('#TOBEDELETED').text(selected_row.attr('data_id'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		};
	</script>
@endsection