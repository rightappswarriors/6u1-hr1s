@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-building"></i> Business Units
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
											<th>Code</th>
											<th>Description</th>
											<th>Disbursing Bank</th>
											<th>Bank Address</th>
											<th>Account #</th>
											<th>Contact Person</th>
											<th>Designation</th>
											<th>Letter Format</th>
											<th>Bank Letter Prepared By</th>
										</tr>
									</thead>
									<tbody>
										@isset($dept)
											@if(count($dept)>0)
												@foreach($dept as $pp)
												<tr data_id="{{$pp->bucode}}" data_name="{{$pp->bunit_desc}}">
													<td>{{$pp->bucode}}</td>
													<td>{{$pp->bunit_desc}}</td>
													<td>{{$pp->bank_disburse}}</td>
													<td>{{$pp->bank_addr}}</td>
													<td>{{$pp->accnt_no}}</td>
													<td>{{$pp->contact_person}}</td>
													<td>{{$pp->designation_cp}}</td>
													<td>{{$pp->letter_format}}</td>
													<td>{{$pp->bletter_prepared}}</td>
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
				<div class="col-3">
					<div class="card">
						<div class="card-body">
							<button type="button" class="btn btn-success btn-block" id="opt-add"><i class="fa fa-plus"></i> Add</button>
							<button type="button" class="btn btn-primary btn-block" id="opt-update"><i class="fa fa-edit"></i> Edit</button>
							<button type="button" class="btn btn-danger btn-block" id="opt-delete"><i class="fa fa-trash"></i> Delete</button>
							<button type="button" class="btn btn-info btn-block" id="opt-print"><i class="fa fa-print"></i> Print List</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Modal -->
	<div class="modal fade" id="modal-pp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div id="TESTMODAL" class="modal-dialog modal-lg" role="document">
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
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="8" placeholder="XXX" required>
									</div>
									<div class="form-group">
										<label>Description:</label>
										<input type="text" name="txt_name" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Disbursing Bank:</label>
										<input type="text" name="txt_ds_bnk" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Bank Address:</label>
										<input type="text" name="txt_bnk_add" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Account Number:</label>
										<input type="text" name="txt_acc_num" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Contact Person:</label>
										<input type="text" name="txt_ct_pr" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Designation:</label>
										<input type="text" name="txt_dsg" style="" class="form-control VX" placeholder="" required>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label>Letter Format:</label>
										<select type="text" name="txt_let_fm" style="" class="form-control VX" placeholder="" required>
											<option value="">Select Letter Format..</option>
											<option value="China Bank">China Bank</option>
											<option value="PS Bank">PS Bank</option>
										</select>
									</div>
									<div class="form-group">
										<label>Bank Letter Prepared By:</label>
										<input type="text" name="txt_bk_lt_p" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Designation:</label>
										<input type="text" name="txt_dsg1" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Bank Letter Noted By:</label>
										<input type="text" name="txt_bk_lt_n" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Designation:</label>
										<input type="text" name="txt_dsg2" style="" class="form-control VX" placeholder="" required>
									</div>
									<div class="form-group">
										<label>Accounting Data Folder:</label>
										<input type="text" name="txt_acc_dt_fl" style="" class="form-control VX" placeholder="" required>
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
		function LoadDatable()
		{
			data.row.add([
				data.pay_code,
				data.date_from,
				data.date_to
			]).draw();
		}
		$('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/business-units')}}');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('.VX').attr('required', '');
			$('#TESTMODAL').addClass('modal-lg');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-update').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/business-units')}}/update');
			$('input[name="txt_code"]').attr('readonly', '');
			// $('input[name="txt_code"]').val(selected_row.attr('data_id'));
			// $('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('.VX').attr('required', '');
			$.ajax({
				url : '{{ url('master-file/business-units/getOne') }}',
				data : {_token:$('meta[name="csrf-token"]').attr('content'), id : selected_row.attr('data_id')},
				success : function(data){
					var d = data.data;
					// console.log(data);
					if(data.status == 'OK')
					{
						if(d){
							$('input[name="txt_code"]').val(d.bucode);
							$('input[name="txt_name"]').val(d.bunit_desc);
							$('input[name="txt_ds_bnk"]').val(d.bank_disburse);
							$('input[name="txt_bnk_add"]').val(d.bank_addr);
							$('input[name="txt_acc_num"]').val(d.accnt_no).trigger('change');
							$('input[name="txt_ct_pr"]').val(d.contact_person).trigger('change');
							$('input[name="txt_dsg"]').val(d.designation_cp).trigger('change');
							$('select[name="txt_let_fm"]').val(d.letter_format).trigger('change');
							$('input[name="txt_bk_lt_p"]').val(d.bletter_prepared);
							$('input[name="txt_dsg1"]').val(d.designation_blp);
							$('input[name="txt_bk_lt_n"]').val(d.bletter_noted);
							$('input[name="txt_dsg2"]').val(d.designation_bln);
							$('input[name="txt_acc_dt_fl"]').val(d.accnt_data_folder);
						}
					}
				},
				error : function(){}
			});

			$('#TESTMODAL').addClass('modal-lg');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-delete').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/business-units')}}/delete');
			$('.VX').removeAttr('required');
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('#TOBEDELETED').text(selected_row.attr('data_name'));

			$('#TESTMODAL').removeClass('modal-lg');
			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		});
	</script>
@endsection