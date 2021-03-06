@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-building"></i> Office <button type="button" class="btn btn-success" id="opt-add"><i class="fa fa-plus"></i> Add</button>
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
									<col width="10%">
									<thead>
										<tr>
											<th>Office ID</th>
											<th>Name</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@isset($office)
											@if(count($office)>0)
												@foreach($office as $pp)
												<tr data_id="{{$pp->cc_id}}" data_code="{{$pp->cc_code}}" data_name="{{$pp->cc_desc}}" data_hp="{{$pp->hp_id}}|{{$pp->withpay}}">
													<td>{{$pp->cc_code}}</td>
													<td>{{$pp->cc_desc}}</td>
													<td>
														<button type="button" class="btn btn-primary mr-1" onclick="row_update(this)"><i class="fa fa-edit"></i></button>
														<button type="button" class="btn btn-danger" onclick="d_delete(this)"><i class="fa fa-trash"></i></button>
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
										<label>Office ID:</label>
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX" required>
										<input type="hidden" name="txt_id">
									</div>
									<div class="form-group">
										<label>Name:</label>
										<input type="text" name="txt_name" style="text-transform: uppercase;" class="form-control" placeholder="DESCRIPTION" required>
									</div>
									<div class="form-group form-inline">
										<label>With Hazard Pay:</label>
										<input type="checkbox" name="chk_hazrd" style="width: 7%; margin: 1%;" class="form-control">
										<input type="hidden" name="txt_hazrd">
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Department list?</p>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="frm-pp" class="btn btn-success">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</span>
					<span class="DeleteMode">
						<button type="submit" form="frm-pp" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
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
		var table = $('#dataTable').DataTable(dataTable_config3);
	</script>
	{{-- <script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script> --}}
	<script type="text/javascript">
		$('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/office')}}');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_id"]').attr('readonly');
			$('input[name="txt_code"]').val('');
			$('input[name="txt_id"]').val('');
			$('input[name="txt_name"]').val('');
			$('input[name="txt_hazrd"]').val('');
			$('input[name="chk_hazrd"]').prop( "checked", false);

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		function row_update(obj) {
			var selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/office')}}/update');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_id"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_code'));
			$('input[name="txt_id"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));

			var txt_hazrd = selected_row.attr('data_hp').split("|");
			$('input[name="txt_hazrd"]').val((txt_hazrd[0] == '') ? null : txt_hazrd[0]);
			if (txt_hazrd[1] == 1) {
				$('input[name="chk_hazrd"]').prop( "checked", true);
			} else {
				$('input[name="chk_hazrd"]').prop( "checked", false);
			}

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		};

		function d_delete(obj) {
			var selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/office')}}/delete');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_id"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_code'));
			$('input[name="txt_id"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('#TOBEDELETED').text(selected_row.attr('data_name'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		};
	</script>
@endsection