@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-clock-o"></i> Contribution Remitance
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
											<th>Month</th>
											<th>SBR</th>
											<th>SBR Date</th>
											<th>PBR</th>
											<th>PBR Date</th>
											<th>PR</th>
											<th>PR Date</th>
										</tr>
									</thead>
									<tbody>
										@isset($cr)
											@if(count($cr)>0)
												@foreach($cr as $pp)
												<tr data_id="{{$pp->crcode}}">
													<th>{{$pp->crcode}}</th>
													<td>{{$pp->month}}</td>
													<td>{{$pp->sbr}}</td>
													<td>{{date("M d, Y", strtotime($pp->sbr_date))}}</td>
													<td>{{$pp->pbr}}</td>
													<td>{{date("M d, Y", strtotime($pp->pbr_date))}}</td>
													<td>{{$pp->pr}}</td>
													<td>{{date("M d, Y", strtotime($pp->pr_date))}}</td>
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
								<div class="col">
									<div class="form-group">
										<label>Month: <strong style="color:red">*</strong></label>
										<div class="input-group">
											<select name="txt_mo" id="" class="form-control">
												<option value="">Select Month..</option>
												<option value="January">January</option>
												<option value="February">February</option>
												<option value="March">March</option>
												<option value="April">April</option>
												<option value="May">May</option>
												<option value="June">June</option>
												<option value="July">July</option>
												<option value="August">August</option>
												<option value="September">September</option>
												<option value="October">October</option>
												<option value="November">November</option>
												<option value="December">December</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label>SBR:</label>
										<div class="input-group">
											<input type="txt" class="form-control VX" step=".01" name="txt_sbr">
										</div>
									</div>
									<div class="form-group">
										<label>SBR Date: </label>
										<div class="input-group">
											<input type="date" class="form-control VX" step=".01" name="txt_sbr_dt" >
										</div>
									</div>
									<div class="form-group">
										<label>PBR:</label>
										<div class="input-group">
											<input type="text" class="form-control VX" step=".01" name="txt_pbr">
										</div>
									</div>
									<div class="form-group">
										<label>PBR Date:</label>
										<div class="input-group">
											<input type="date" class="form-control VX" step=".01" name="txt_pbr_dt">
										</div>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label>PR:</label>
										<div class="input-group">
											<input type="text" class="form-control VX" step=".01" name="txt_pr">
										</div>
									</div>
									<div class="form-group">
										<label>PR Date:</label>
										<div class="input-group">
											<input type="date" class="form-control VX" step=".01" name="txt_pr_dt">
										</div>
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Contribution Remitance list?</p>
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
			$('#frm-pp').attr('action', '{{url('master-file/contribution-remitance')}}');

			$('input[name="txt_code"]').removeAttr('readonly');
			$('input[name="txt_code"]').val('');
			// $('.VX').val('0.00');
			$('.VX2').val('0');
			$('.RX').attr('required', '');
			// getTC();
			$('#TESTDOCU').addClass('modal-lg');
			// $('#TESTDOCU').addClass('w-75');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-update').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/contribution-remitance')}}/update');
			$('input[name="txt_code"]').attr('readonly', '');
			$.ajax({
				url : '{{ url('master-file/contribution-remitance/getOne') }}',
				data : {_token:$('meta[name="csrf-token"]').attr('content'), id : selected_row.attr('data_id')},
				success : function(data){
					var d = data.data;
					// console.log(data);
					if(data.status == 'OK')
					{
						if(d){
							$('input[name="txt_code"]').val(d.crcode);
							$('select[name="txt_mo"]').val(d.month);
							$('input[name="txt_sbr"]').val(d.sbr);
							$('input[name="txt_sbr_dt"]').val(d.sbr_date);
							$('input[name="txt_pbr"]').val(d.pbr);
							$('input[name="txt_pbr_dt"]').val(d.pbr_date);
							$('input[name="txt_pr"]').val(d.pr);
							$('input[name="txt_pr_dt"]').val(d.pr_date);
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
			$('#frm-pp').attr('action', '{{url('master-file/contribution-remitance')}}/delete');
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