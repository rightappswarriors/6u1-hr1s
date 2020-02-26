@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-building"></i> Pending Leave
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
											<th>ID</th>
											<th>Employee Name</th>
											<th>Leave Type</th>
											<th>Leave From</th>
											<th>Leave To</th>
											<th>Number Of Days</th>
											<th>Leave with Pay</th>
											<th>Leave Reason</th>
											<th>Date Filed</th>
											<th>Option</th>
										</tr>
									</thead>
									<tbody>

										@isset($forApproval)
											@foreach($forApproval as $key => $value)
											<tr id="{{$value->approvalid}}">
												<td>{{$value->approvalid}}</td>
												<td>{{$value->firstname . ' ' .$value->lastname}}</td>
												<td>{{$value->description}}</td>
												<td>{{Date('F j, Y',strtotime($value->leave_from))}}</td>
												<td>{{Date('F j, Y',strtotime($value->leave_to))}}</td>
												<td>{{$value->no_of_days}}</td>
												<td>{{$value->leave_pay}}</td>
												<td>{{$value->leave_reason}}</td>
												<td>{{Date('F j, Y',strtotime($value->d_filed))}}</td>
												<td>
													<div class="row">
														<div class="col-5">
															<button onclick="processApproval(1,'{{$value->approvalid}}')" class="btn btn-success"><i class="fa fa-check"></i></button>
														</div>
														<div class="col-6">
															<button onclick="processApproval(2,'{{$value->approvalid}}')" class="btn btn-danger"><i class="fa fa-times"></i></button>
														</div>
													</div>
												</td>
											</tr>
											@endforeach
										@endisset
										
									</tbody>
								</table>
							</div>
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
						<div class="container">
							<div class="row">
								<div class="col">
									<label class="lead" for="remarks">Remarks</label>
									<textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10"></textarea>
									<input type="hidden" name="judge">
									<input type="hidden" name="id">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="frm-pp" class="btn btn-success">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</span>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{asset('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript">
		var table = $('#dataTable').DataTable();
	</script>
	<script type="text/javascript">
		function processApproval(judge,id){
			$('#exampleModalLabel').text((judge == '1' ? 'Approve' : 'Disapprove'));
			$('#modal-pp').modal('show');
			$("[name=judge]").val(judge);
			$("[name=id]").val(id);
		}

		$("#frm-pp").submit(function(event) {
			event.preventDefault();
			$.ajax({
				method: 'POST',
				data: {id : $("[name=id]").val(), remarks: $("#remarks").val(), judge: $("[name=judge]").val()},
				success: function(a){
					if(a == 'ok'){
						alert('Operation Successful');
						$('table').DataTable().row($('#'+$("[name=id]").val())).remove().draw();
						$('#modal-pp').modal('hide');
					} else {
						alert(a);
					}
				}
			})
		});
	</script>
@endsection