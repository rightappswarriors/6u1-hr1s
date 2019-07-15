@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-clock-o"></i> SSS
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
											<th>Bracket 1</th>
											<th>Bracket 2</th>
											<th>Salary Credit</th>
											<th>EmployER's Share</th>
											<th>E.C.</th>
											<th>EmployEE's Share</th>
											<th>Total Count</th>
										</tr>
									</thead>
									<tbody>
										@isset($sss)
											@if(count($sss)>0)
												@foreach($sss as $pp)
												<tr data_id="{{$pp->code}}">
													<th>{{$pp->code}}</th>
													<td>{!!Core::currSign()!!}{{number_format($pp->bracket1, 2, ".", ", ")}}</td>
													<td>{!!Core::currSign()!!}{{number_format($pp->bracket2, 2, ".", ", ")}}</td>
													<td>{!!Core::currSign()!!}{{number_format($pp->s_credit, 2, ".", ", ")}}</td>
													<td>{!!Core::currSign()!!}{{number_format($pp->empshare_sc, 2, ".", ", ")}}</td>
													<td>{!!Core::currSign()!!}{{number_format($pp->s_ec, 2, ".", ", ")}}</td>
													<td>{!!Core::currSign()!!}{{number_format($pp->empshare_ec, 2, ".", ", ")}}</td>
													<td>{!!Core::currSign()!!}{{number_format((floatval($pp->empshare_sc) + floatval($pp->empshare_ec) + floatval($pp->s_ec)), 2, ".", ", ")}}</td>
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
		<div id="TESTDOCU" class="modal-dialog modal-lg" role="document">
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
								<div class="col">
									<div class="form-group">
										<label>Code: <strong style="color:red">*</strong></label>
										<input type="text" maxlength="8" name="txt_code" style="text-transform: uppercase;" class="form-control RX" placeholder="XXXX" required>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label>Bracket 1: <strong style="color:red">*</strong></label>
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text">{!!Core::currSign()!!}</div>
											</div>
											<input type="number" class="form-control VX RX" step=".01" name="txt_br_1" value="0.00" required="">
										</div>
									</div>
									<div class="form-group">
										<label>Bracket 2: <strong style="color:red">*</strong></label>
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text">{!!Core::currSign()!!}</div>
											</div>
											<input type="number" class="form-control VX RX" step=".01" name="txt_br_2" value="0.00" required="">
										</div>
									</div>
									<div class="form-group">
										<label>Salary Credit: <strong style="color:red">*</strong></label>
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text">{!!Core::currSign()!!}</div>
											</div>
											<input type="number" class="form-control VX RX" step=".01" name="txt_sal_cre" value="0.00" required="">
										</div>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label>Employer's Share: <strong style="color:red">*</strong></label>
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text">{!!Core::currSign()!!}</div>
											</div>
											<input type="number" class="form-control VX RX" step=".01" name="txt_emp_sh" onchange="getTC()" value="0.00" required="">
										</div>
									</div>
									<div class="form-group">
										<label>E.C.: <strong style="color:red">*</strong></label>
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text">{!!Core::currSign()!!}</div>
											</div>
											<input type="number" class="form-control VX RX" step=".01" name="txt_ec" onchange="getTC()" value="0.00" required="">
										</div>
									</div>
									<div class="form-group">
										<label>Employee's Share: <strong style="color:red">*</strong></label>
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text">{!!Core::currSign()!!}</div>
											</div>
											<input type="number" class="form-control VX RX" step=".01" name="txt_eme_sh" onchange="getTC()" value="0.00" required="">
										</div>
									</div>
									<div class="form-group">
										<label>Total Contribution:</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text">{!!Core::currSign()!!}</div>
											</div>
											<input type="number" class="form-control" step=".01" name="txt_total" value="0.00" readonly="">
										</div>
									</div>
								</div>
							</div>
							{{-- <div class="form-group">
								<label>Code: <strong style="color:red">*</strong></label>
								<input type="text" name="txt_code" style="text-transform: uppercase;" class="form-control RX" placeholder="XXXX" required>
							</div>
							<div class="form-group">
								<label>Bracket 1: <strong style="color:red">*</strong></label>
								<input type="text" name="" class="form-control RX" value="0.00" required>
							</div>
							<div class="form-group">
								<label>Bracket 2: <strong style="color:red">*</strong></label>
								<input type="text" name="" class="form-control RX" value="0.00" required>
							</div>
							<div class="form-group">
								<label>Salary Credit: <strong style="color:red">*</strong></label>
								<input type="text" name="" class="form-control RX" value="0.00" required>
							</div>
							<div class="form-group">
								<label>Employer's Share: <strong style="color:red">*</strong></label>
								<input type="text" name="" class="form-control RX" value="0.00" required>
							</div>
							<div class="form-group">
								<label>E.C.: <strong style="color:red">*</strong></label>
								<input type="text" name="" class="form-control RX" value="0.00" required>
							</div>
							<div class="form-group">
								<label>Employee's Share: <strong style="color:red">*</strong></label>
								<input type="text" name="" class="form-control RX" value="0.00" required>
							</div>
							<div class="form-group">
								<label>Total Contribution: <strong style="color:red">*</strong></label>
								<input type="text" name="" class="form-control RX" value="0.00" required>
							</div> --}}
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from SSS list?</p>
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
		function getTC()
		{
			var er = ($('input[name="txt_emp_sh"]').val() != '') ? parseFloat($('input[name="txt_emp_sh"]').val()) : parseFloat(0),
				ee = ($('input[name="txt_eme_sh"]').val() != '') ? parseFloat($('input[name="txt_eme_sh"]').val()) : parseFloat(0),
				ec = ($('input[name="txt_ec"]').val() != '') ? parseFloat($('input[name="txt_ec"]').val()) : parseFloat(0);
			var total  = er + ee + ec;
			$('input[name="txt_total"]').val(parseFloat(total));
		}
		$('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/sss')}}');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			$('.VX').val('0.00');
			$('.RX').attr('required', '');
			getTC();
			$('#TESTDOCU').addClass('modal-lg');
			// $('#TESTDOCU').addClass('w-75');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-update').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/sss')}}/update');
			$('input[name="txt_code"]').attr('readonly', '');
			$.ajax({
				url : '{{ url('master-file/sss/getOne') }}',
				data : {_token:$('meta[name="csrf-token"]').attr('content'), id : selected_row.attr('data_id')},
				success : function(data){
					var d = data.data;
					// console.log(data);
					if(data.status == 'OK')
					{
						if(d){
							$('input[name="txt_code"]').val(d.code);
							$('input[name="txt_br_1"]').val(d.bracket1);
							$('input[name="txt_br_2"]').val(d.bracket2);
					// 		's_credit' => $r->txt_sal_cre,
					// 'empshare_sc' => $r->txt_emp_sh,
					// 's_ec' => $r->txt_ec,
					// 'empshare_ec' => $r->txt_eme_sh
							$('input[name="txt_sal_cre"]').val(d.s_credit);
							$('input[name="txt_emp_sh"]').val(d.empshare_sc).trigger('change');
							$('input[name="txt_ec"]').val(d.s_ec).trigger('change');
							$('input[name="txt_eme_sh"]').val(d.empshare_ec).trigger('change');
						}
					}
				},
				error : function(){}
			});
			$('#TESTDOCU').addClass('modal-lg');
			// $('#TESTDOCU').addClass('w-75');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-delete').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/sss')}}/delete');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_code"]').val(selected_row.attr('data_id'));
			$('#TESTDOCU').removeClass('modal-lg');
			// $('#TESTDOCU').removeClass('w-75');
			$('#TOBEDELETED').text(selected_row.attr('data_id'));

			$('.RX').removeAttr('required');
			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		});
	</script>
@endsection