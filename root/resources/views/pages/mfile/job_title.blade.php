@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-suitcase"></i> Job Title <button type="button" class="btn btn-success" id="opt-add"><i class="fa fa-plus"></i> Add</button>
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
											<th>Job Code</th>
											<th>Description</th>
											<th></th>
											{{-- <th>Job Title</th> --}}
										</tr>
									</thead>
									<tbody>
										@isset($jobtitle)
											@if(count($jobtitle)>0)
												@foreach($jobtitle as $pp)
												<tr data_id="{{$pp->jt_cn}}" data_code="{{$pp->jtid}}" data_name="{{$pp->jtitle_name}}">
													<td>{{$pp->jtid}}</td>
													<td>{{$pp->jtitle_name}}</td>
													<td>
														<button type="button" class="btn btn-primary mr-1" onclick="row_update(this)"><i class="fa fa-edit"></i></button>
														<button type="button" class="btn btn-danger" onclick="row_delete(this)"><i class="fa fa-trash"></i></button>
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
										<label>Job Code:</label>
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX" required>
										<span class="error-span" id="error-span" hidden=""></span>
										<input type="text" name="txt_temp" hidden="">
									</div>
									<div class="form-group">
										<label>Name:</label>
										<input type="text" name="txt_name" class="form-control" placeholder="Description" required>
									</div>
									<div>
										<div class="d-flex justify-content-center">
											<div class="col-1 d-none" id="modal-loader-container">
												<div class="loader-circle"></div>
											</div>
										</div>
										<div>
											<div class="row">
												<div class="col-9">
													<span class="error-span" id="error-message"></span>
												</div>
												<div class="col">
													<button type="button" class="btn btn-success d-none" id="restore-jobtitle">Restore</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Job Title list?</p>
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
	<script type="text/javascript" src="/root/resources/assets/js/utils.js"></script>
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
		var FRM_STATE = {
			ADD: 0,
			UPDATE: 1,
			DELETE: 2
		}

		var $frmPP = $('#frm-pp');
		var $modalLoaderContainer = $('#modal-loader-container');
		var $errorMessage = $('#error-message');
		var $restoreJobtitle = $('#restore-jobtitle');
		var $saveButton = $('#btn-save');
		var $modalpp = $('#modal-pp');

		var frmState = '';

		$frmPP.on('submit', function(e) {
			$errorMessage.text('');
			$restoreJobtitle.addClass('d-none');

			switch (frmState) {
				case FRM_STATE.ADD:
					e.preventDefault();

					$modalLoaderContainer.removeClass('d-none');

					$.ajax({
						type: $(this).attr('method'),
						url: $(this).attr('action'),
						data: $(this).serialize(),
						dataType: 'json',
						success: function(response) {
							$modalLoaderContainer.addClass('d-none');
						},
						error: function(response) {
							$modalLoaderContainer.addClass('d-none');

							var error = response.responseJSON.error;

							$errorMessage.text(error.message);
							if (error.code == ErrorCodes.CODE_DELETED) {
								$restoreJobtitle.removeClass('d-none');
							}
						}
					});
					break;
			}
		});

		$restoreJobtitle.on('click', function() {
			$modalLoaderContainer.removeClass('d-none');

			$.ajax({
				type: 'post',
				url: '{{url('master-file/job-title')}}/restore',
				data: $frmPP.serialize(),
				dataType: 'json',
				success: function(response) {
					$modalLoaderContainer.addClass('d-none');
					location.reload();
				}, 
				error: function(response) {
					console.log('error', response);
				}
			});
		});

		$modalpp.on('hidden.bs.modal', function() {
			$restoreJobtitle.addClass('d-none');
			$modalLoaderContainer.addClass('d-none');
			$errorMessage.text('');
		});

		$('#opt-add').on('click', function(){
			$frmPP.attr('action', '{{url('master-file/job-title')}}');
			frmState = FRM_STATE.ADD;

			// $('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('input[name="txt_name"]').val('');
			$('input[name="txt_temp"]').val('');
			// $('select[name="txt_dept"]').val('').trigger('change');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$modalpp.modal('show');
		});

		function row_update(obj) {
			var selected_row = $($(obj).parents()[1]);
			$frmPP.attr('action', '{{url('master-file/job-title')}}/update');
			frmState = FRM_STATE.UPDATE;
		
			// $('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_temp"]').val(selected_row.attr('data_id'));
			$('input[name="txt_code"]').val(selected_row.attr('data_code'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			// $('select[name="txt_dept"]').val(selected_row.attr('data_dept')).trigger('change');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$modalpp.modal('show');
		};

		function row_delete(obj) {
			var selected_row = $($(obj).parents()[1]);
			$frmPP.attr('action', '{{url('master-file/job-title')}}/delete');
			frmState = FRM_STATE.DELETE;
		
			// $('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_temp"]').val(selected_row.attr('data_id'));
			$('input[name="txt_code"]').val(selected_row.attr('data_code'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			// $('select[name="txt_dept"]').val(selected_row.attr('data_dept')).trigger('change');
			$('#TOBEDELETED').text(selected_row.attr('data_name'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$modalpp.modal('show');
		};

		// $('input[name="txt_code"]').on('input', function() {
		// 	$.ajax({
		// 		type : "post",
		// 		url : "{{url('master-file/job-title')}}/check-jt",
		// 		data : {code : $(this).val(), id : $('input[name="txt_temp"]').val()},
		// 		dataTy : 'json',
		// 		success : function(data) {
		// 			if (data=="true") {
		// 				$('#error-span').removeAttr('hidden');
		// 				$('#error-span').text('Code already exists. Please try again.');
		// 				$('#btn-save').attr('disabled' ,true);
		// 			}
		// 		}
		// 	});
		// 	$('#btn-save').removeAttr('disabled');
		// 	$('#error-span').attr('hidden', true);
		// });
	</script>
@endsection