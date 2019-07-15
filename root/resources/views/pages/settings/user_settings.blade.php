@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-wrench"></i> User Settings
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
											<th>User</th>
											<th>Name</th>
											{{-- <th>Restrictions</th> --}}
										</tr>
									</thead>
									<tbody>
										@isset($data[0])
											@if(count($data[0]) > 0)
												@foreach($data[0] as $key => $value)
													<tr uid="{{$value->uid}}"
														opr_name="{{$value->opr_name}}"
														pwd="{{$value->pwd}}"
														grp_id="{{$value->grp_id}}"
														d_code="{{$value->d_code}}"
														approve_disc="{{$value->approve_disc}}"
														restr="{{$value->restriction}}">
														<td>{{$value->uid}}</td>
														<td>{{$value->opr_name}}</td>
														{{-- <td>{{$value->d_code}}</td> --}}
														{{-- <td>
															<button type="button" class="btn btn-warning btn-block" onclick="openRestrictionsModal()">
																<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
																Edit Restrictions
															</button>
														</td> --}}
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
							{{-- <button type="button" class="btn btn-info btn-block" id="opt-print"><i class="fa fa-print"></i> Print List</button> --}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection


@section('to-modal')
	<!-- Add Modal -->
	<form method="post" action="#" id="frm-pp" data="#">
	@csrf
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
					{{-- <form method="post" action="#" id="frm-pp" data="#">
						@csrf --}}
						<span class="AddMode">
							<div class="row">
								<div class="col"> <!-- Column 1 -->
									<div class="form-group">
										<label>Restrictions:</label>
										{{-- <select name="cbo_grp" id="" style="text-transform: uppercase;" class="form-control" required>
											<option disabled hidden selected value="">---</option>
											@foreach(X07::Load_X07() as $key => $value)
												@if($value->grp_id != "001")
													<option value="{{$value->grp_id}}">{{$value->grp_desc}}</option>
												@endif	
											@endforeach
										</select> --}}
										<button type="button" class="btn btn-warning btn-block" id="btn_restr" onclick="openRestrictionsModal()">
											<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
											Edit Restrictions
										</button>
									</div>
									<div class="form-group">
										<label>Name:</label>
										<input type="text" name="txt_name" class="form-control" style="text-transform: uppercase;" maxlength="100" required>
									</div>
									<div class="form-group">
										<label>Username:</label>
										<input type="text" name="txt_user" class="form-control" maxlength="10" required>
									</div>
									<div class="form-group">
										<label>Password:</label>
										<input type="password" name="txt_pass" style="" class="form-control" minlength="8" maxlength="15" required>
									</div>
									<div class="form-group">
										<label>Repeat Password:</label>
										<input type="password" name="txt_pass_r" style="" class="form-control" minlength="8" maxlength="15" required>
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from User Settings?</p>
						</span>
					{{-- </form> --}}
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

	<!-- Restriction Modal -->
	<div class="modal fade" id="modal-pp-r" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
		<div class="modal-dialog" role="document" style="background-color: black; width: 20vw;">
			<div class="modal-content" style="background-color: rgb(248,248,248);">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel1">Restrictions</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<span class="bg-info pl-2 pr-2 text-white">
						Uncheck module/s to restrict
					</span>
					@isset($data[2])
						@foreach($data[2] as $k => $v)
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="{{$k}}" value="{{$v->id}}" name="restrictions[]">
								<label class="custom-control-label" for="{{$k}}">{{$v->mod_name}}</label>
							</div>
						@endforeach
					@endisset
				</div>
				<div class="modal-footer">
					<span>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</span>
				</div>
			</div>
		</div>
	</div>
	</form>
@endsection

@section('to-bottom')
	<script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script>

	<script>
		function openRestrictionsModal() {
			$('#modal-pp-r').modal('show');
		}
		// $('#btn_restr').on('click', function() {
		// 	$('#modal-pp-r').modal('show');
		// });
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
			$('#frm-pp').attr('action', '{{url('settings/user')}}');

			$('select[name="cbo_grp"]').removeAttr('readonly');
			$('select[name="cbo_grp"]').val('').trigger('change');
			$('input[name="txt_name"]').val('');
			$('input[name="txt_user"]').val('');
			$('input[name="txt_pass"]').val('');
			$('input[name="txt_pass_r"]').val('');

			@foreach($data[2] as $k => $v)
				$('#'+'{{$k}}')[0].setAttribute('checked', '');
			@endforeach
			
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-update').on('click', function() {
			$('#frm-pp').attr('action', '{{url('settings/user')}}/update');
		
			$('input[name="txt_code"]').attr('readonly', '');

			$('select[name="cbo_grp"]').removeAttr('readonly');
			$('select[name="cbo_grp"]').val(selected_row.attr('grp_id')).trigger('change');
			$('input[name="txt_name"]').val(selected_row.attr('opr_name'));
			$('input[name="txt_user"]').val(selected_row.attr('uid'));
			$('input[name="txt_pass"]').val(selected_row.attr('pwd'));
			$('input[name="txt_pass_r"]').val(selected_row.attr('pwd'));

			@foreach($data[2] as $k => $v)
				$('#'+'{{$k}}')[0].removeAttribute('checked');
			@endforeach


			@foreach($data[2] as $k => $v)
				if(selected_row.attr('restr').split(', ').includes('{{$v->id}}')) {
					// console.log('{{$k}} '+' {{$v->mod_name}}');
					$('#'+'{{$k}}')[0].setAttribute('checked', '');
				}
			@endforeach

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-delete').on('click', function() {
			$('#frm-pp').attr('action', '{{url('settings/user')}}/delete');
		
			$('input[name="txt_code"]').attr('readonly', '');
			// $('input[name="txt_code"]').val(selected_row.attr('data_id'));
			// $('input[name="txt_name"]').val(selected_row.attr('data_name'));

			$('select[name="cbo_grp"]').removeAttr('required');
			$('select[name="cbo_grp"]').removeAttr('required');
			$('input[name="txt_name"]').removeAttr('required');
			$('input[name="txt_user"]').removeAttr('required');
			$('input[name="txt_pass"]').removeAttr('required');
			$('input[name="txt_pass_r"]').removeAttr('required');

			$('select[name="cbo_grp"]').removeAttr('readonly');
			$('select[name="cbo_grp"]').val(selected_row.attr('grp_id')).trigger('change');
			$('input[name="txt_name"]').val(selected_row.attr('opr_name'));
			$('input[name="txt_user"]').val(selected_row.attr('uid'));
			$('input[name="txt_pass"]').val(selected_row.attr('pwd'));
			$('input[name="txt_pass_r"]').val(selected_row.attr('pwd'));

			$('#TOBEDELETED').text(selected_row.attr('opr_name'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		});
	</script>
@endsection