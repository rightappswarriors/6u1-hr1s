@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-clock-o"></i> Witholding Tax
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
										</tr>
									</thead>
									<tbody>
										@isset($wtax)
											@if(count($wtax)>0)
												@foreach($wtax as $pp)
												<tr data_id="{{$pp->code}}" data_name="{{$pp->description}}">
													<th>{{$pp->code}}</th>
													<th>{{$pp->description}}</th>
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
								<div class="col">
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 1:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 2:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 3:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 4:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 5:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 6:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 7:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 8:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 9:</label>&nbsp;
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Bracket 10:</label>
										<input type="text" name="brk[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
								</div>
								<div class="col">
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 1:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 2:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 3:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 4:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 5:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 6:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 7:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 8:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 9:</label>&nbsp;
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Factor 10:</label>
										<input type="text" name="fct[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
								</div>
								<div class="col">
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 1:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 2:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 3:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 4:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 5:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 6:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 7:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 8:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 9:</label>&nbsp;
										<input type="text" name="addon[]"class="form-control VX" placeholder="0.00" value="0.00">
									</div> &nbsp;
									<div class="form-inline">
										<label class="my-1 mr-5">Add-on 10:</label
>										<input type="text" name="addon[]"class="form-control VX" value="0.00" placeholder="0.00">
									</div> &nbsp;
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

		$('#opt-update').on('click', function() {
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
							$('input[name="brk[]"]').eq(0).val(d.bracket1);
							$('input[name="brk[]"]').eq(1).val(d.bracket2);
							$('input[name="brk[]"]').eq(2).val(d.bracket3);
							$('input[name="brk[]"]').eq(3).val(d.bracket4);
							$('input[name="brk[]"]').eq(4).val(d.bracket5);
							$('input[name="brk[]"]').eq(5).val(d.bracket6);
							$('input[name="brk[]"]').eq(6).val(d.bracket7);
							$('input[name="brk[]"]').eq(7).val(d.bracket8);
							$('input[name="brk[]"]').eq(8).val(d.bracket9);
							$('input[name="brk[]"]').eq(9).val(d.bracket10);
							$('input[name="fct[]"]').eq(0).val(d.factor1);
							$('input[name="fct[]"]').eq(1).val(d.factor2);
							$('input[name="fct[]"]').eq(2).val(d.factor3);
							$('input[name="fct[]"]').eq(3).val(d.factor4);
							$('input[name="fct[]"]').eq(4).val(d.factor5);
							$('input[name="fct[]"]').eq(5).val(d.factor6);
							$('input[name="fct[]"]').eq(6).val(d.factor7);
							$('input[name="fct[]"]').eq(7).val(d.factor8);
							$('input[name="fct[]"]').eq(8).val(d.factor9);
							$('input[name="fct[]"]').eq(9).val(d.factor10);
							$('input[name="addon[]"]').eq(0).val(d.add_on1);
							$('input[name="addon[]"]').eq(1).val(d.add_on2);
							$('input[name="addon[]"]').eq(2).val(d.add_on3);
							$('input[name="addon[]"]').eq(3).val(d.add_on4);
							$('input[name="addon[]"]').eq(4).val(d.add_on5);
							$('input[name="addon[]"]').eq(5).val(d.add_on6);
							$('input[name="addon[]"]').eq(6).val(d.add_on7);
							$('input[name="addon[]"]').eq(7).val(d.add_on8);
							$('input[name="addon[]"]').eq(8).val(d.add_on9);
							$('input[name="addon[]"]').eq(9).val(d.add_on10);
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
		});

		$('#opt-delete').on('click', function() {
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
		});
	</script>
@endsection