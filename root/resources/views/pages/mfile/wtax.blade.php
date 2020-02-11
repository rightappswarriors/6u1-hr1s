@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-clock-o"></i> Witholding Tax
			<button type="button" class="btn btn-success" id="opt-add">
				<i class="fa fa-plus"></i> Add
			</button>
			{{-- <button type="button" class="btn btn-info" id="opt-print">
				<i class="fa fa-print"></i> Print List
			</button> --}}
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
											<th>Description</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@isset($wtax)
											@if(count($wtax)>0)
												@foreach($wtax as $pp)
												<tr data_id="{{$pp->code}}" data_name="{{$pp->description}}">
													<th>{{$pp->code}}</th>
													<th>{{$pp->description}}</th>
													<th>
														<button type="button" class="btn btn-primary mr-1" id="opt-update" onclick="row_update(this)">
															<i class="fa fa-edit"></i>
														</button>
														<button type="button" class="btn btn-danger" id="opt-delete" onclick="row_delete(this)">
															<i class="fa fa-trash"></i>
														</button>
													</th>
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
		<div id="TESTDOCU" class="modal-dialog mw-100 w-75" role="document">
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
								<div class="col-sm-3">
									<div class="form-group">
										<label>Code: <strong style="color:red">*</strong></label>
										<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control RX" placeholder="XXXX" required>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label>Exemption: <strong style="color:red">*</strong></label>
										<input type="number" name="txt_exemp" style="text-transform: uppercase;" class="form-control RX" placeholder="0" required>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Description: <strong style="color:red">*</strong></label>
										<input type="text" name="txt_desc" style="text-transform: uppercase;" class="form-control RX" placeholder="Description" required>
									</div>
								</div>
							</div>
							<div class="row">
								@php
									$number_of_
								@endphp
								<div class="col">
									@for($i = 1; $i <= 10; $i++)
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket {{$i}}:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									@endfor
								</div>
								<div class="col">
									@for($i = 1; $i <= 10; $i++)
									<div class="form-inline">
										<label class="my-1 mr-5">Factor {{$i}}:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									@endfor
								</div>
								<div class="col">
									@for($i = 1; $i <= 10; $i++)
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 1:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									@endfor
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Witholding Tax list?</p>
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
			$('#frm-pp').attr('action', '{{url('master-file/witholding-tax')}}');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('.VX').val('0.00');
			$('.RX').attr('required', '');

			$('#TESTDOCU').addClass('mw-100');
			$('#TESTDOCU').addClass('w-75');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		//Accounting number format		

		// $('#opt-update').on('click', function() {
		function row_update(obj) {
			selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/witholding-tax')}}/update');
			$('input[name="txt_code"]').attr('readonly', '');
			$.ajax({
				url : '{{ url('master-file/witholding-tax/getOne') }}',
				data : {_token:$('meta[name="csrf-token"]').attr('content'), id : selected_row.attr('data_id')},
				success : function(data){
					var d = data.data;
					// console.log(data);
					if(data.status == 'OK')
					{
						if(d){
							$('input[name="txt_code"]').val(d.code);
							$('input[name="txt_exemp"]').val(d.exemption);
							$('input[name="txt_desc"]').val(d.description);
							$('input[name="txt_desc"]').val(d.description);
							
							$('input[name="brk[]"]').eq(0).val(accounting.formatMoney(d.bracket1, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(1).val(accounting.formatMoney(d.bracket2, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(2).val(accounting.formatMoney(d.bracket3, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(3).val(accounting.formatMoney(d.bracket4, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(4).val(accounting.formatMoney(d.bracket5, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(5).val(accounting.formatMoney(d.bracket6, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(6).val(accounting.formatMoney(d.bracket7, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(7).val(accounting.formatMoney(d.bracket8, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(8).val(accounting.formatMoney(d.bracket9, "", 2, ",", "."));
							$('input[name="brk[]"]').eq(9).val(accounting.formatMoney(d.bracket10, "", 2, ",", "."));

							$('input[name="fct[]"]').eq(0).val(accounting.formatMoney(d.factor1, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(1).val(accounting.formatMoney(d.factor2, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(2).val(accounting.formatMoney(d.factor3, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(3).val(accounting.formatMoney(d.factor4, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(4).val(accounting.formatMoney(d.factor5, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(5).val(accounting.formatMoney(d.factor6, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(6).val(accounting.formatMoney(d.factor7, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(7).val(accounting.formatMoney(d.factor8, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(8).val(accounting.formatMoney(d.factor9, "", 2, ",", "."));
							$('input[name="fct[]"]').eq(9).val(accounting.formatMoney(d.factor10, "", 2, ",", "."));

							$('input[name="addon[]"]').eq(0).val(accounting.formatMoney(d.add_on1, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(1).val(accounting.formatMoney(d.add_on2, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(2).val(accounting.formatMoney(d.add_on3, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(3).val(accounting.formatMoney(d.add_on4, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(4).val(accounting.formatMoney(d.add_on5, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(5).val(accounting.formatMoney(d.add_on6, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(6).val(accounting.formatMoney(d.add_on7, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(7).val(accounting.formatMoney(d.add_on8, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(8).val(accounting.formatMoney(d.add_on9, "", 2, ",", "."));
							$('input[name="addon[]"]').eq(9).val(accounting.formatMoney(d.add_on10, "", 2, ",", "."));
						}
					}
				},
				error : function(){}
			});
			$('#TESTDOCU').addClass('mw-100');
			$('#TESTDOCU').addClass('w-75');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		// });
		}
		// $('#opt-delete').on('click', function() {
		function row_delete(obj) {
			selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/witholding-tax')}}/delete');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('#TESTDOCU').removeClass('mw-100');
			$('#TESTDOCU').removeClass('w-75');
			$('#TOBEDELETED').text(selected_row.attr('data_name'));

			$('.RX').removeAttr('required');
			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		// });
		}
	</script>
@endsection