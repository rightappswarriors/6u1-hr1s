@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-wrench"></i> Group Right Actions
		</div>
		<div class="card-body">
			<div class="card mb-3">
				<div class="card-body">
					<div class="form-inline">
						<div class="form-inline">
							<div class="form-group">
								<a class="btn btn-primary mr-2 text-white" id="btn-add"><i class="fa fa-plus"></i> Add New</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="table-responsive" id="div-table">
				<table class="table table-bordered table-hover" id="dataTable">
					<thead>
						<tr>
							<th>Module ID</th>
							<th>Module Description</th>
							<th>Module URL</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($data))
							@foreach($data as $row)
								<tr>
									<td>{{$row->mod_id}}</td>
									<td>{{$row->grp_desc}}</td>
									<td>{{url('') . '/' .$row->path}}</td>
									<td>
										<button class="btn btn-warning btn-edit exclusive-edit-btn" dataid="{{$row->mod_id}}" data-description="{{addslashes(trim($row->grp_desc))}}" data-url="{{addslashes(trim($row->path))}}"><i class="fa fa-pencil"></i></button>
										<button class="btn btn-danger exclusive-delete-btn" data="{{$row->grp_desc}}" restid="{{$row->mod_id}}"><i class="fa fa-close"></i></button>
									</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>

			<div class="hidden" id="div-info">
				<div class="card mb-3">
					<div class="card-body">
						Group Description
						<div class="float-right">
							<button class="btn btn-danger" id="btn-back"><i class="fa fa-arrow-left"></i> Back</button>
						</div>
					</div>
				</div>
				<div class="table-responsive" id="div-info">
					<table class="table table-bordered table-hover" id="dataTable1">
						<col width="10%">
						<col width="30%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<thead>
							<tr>
								<th>Module ID</th>
								<th>Module Description</th>
								<th>Allow</th>
								{{-- <th>Add</th>
								<th>Update</th>
								<th>Delete</th>
								<th>Print</th>
								<th>Action</th> --}}
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<div class="modal fade" id="md-grp" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel111"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="add-frm" method="POST" action="{{url('settings/group-rights/add-group-rights-new')}}">
						<span class="AddMode">
							{{csrf_field()}}
							<div class="form-group">
								<label>Module ID:</label>
								<input type="text" name="txt_grp" class="form-control" placeholder="ID" style="text-transform: uppercase;">
								<label class="mt-1">Module Description:</label>
								<input type="text" name="id_grp" class="form-control" placeholder="Description">
								<label class="mt-1">Module URL:</label>
								<div class="input-group mt-1">
								  <div class="input-group-prepend">
								    <span class="input-group-text" id="basic-addon1">{{url('') . '/'}}</span>
								  </div>
								  <input type="text" name="url_grp" class="form-control" placeholder="URL">
								</div>
								<span class="text-danger">Warning: Please remove trailing slashes(/) on URL and make sure the URL is correct for it may not work if the URL is incorrect</span>
							</div>
						</span>
						<span class="DeleteMode">
							<input type="hidden" name="hidden_txt_id">
							Are you sure you want to delete <b><span class="text-danger" id="del-msg"></span></b>?
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="add-frm" class="btn btn-success" id="btn-add-sub">Add</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</span>
					<span class="DeleteMode">
						<button type="button" class="btn btn-danger" id="btn-del-sub">Delete</button>
						<button type="button" class="btn" data-dismiss="modal">Close</button>
					</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Restriction Modal -->
	<div class="modal fade" id="modal-pp-r" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel11" aria-hidden="true">
		<div class="modal-dialog" role="document" style="background-color: black; width: 20vw;">
			<div class="modal-content" style="background-color: rgb(248,248,248);">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel1">Restrictions</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="edit-restr-form">
					<input type="hidden" name="restriction_grpid" value="">
					<div class="modal-body">
						{{-- <span class="bg-info pl-2 pr-2 text-white">
							Uncheck module/s to restrict
						</span>
						@isset($data[2])
							@foreach($data[2] as $k => $v)
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="{{$k}}" value="{{$v->mod_id}}" name="restrictions[]">
									<label class="custom-control-label" for="{{$k}}">{{$v->grp_desc}}</label>
								</div>
							@endforeach
						@endisset --}}
					</div>
					<div class="modal-footer">
						<span>
							<button type="button" class="btn btn-success" id="restrictions-save-btn">Save</button>
						</span>
						<span>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript">
		var table = $('#dataTable').DataTable();
		var table1 = $('#dataTable1').DataTable();
	</script>
	<script type="text/javascript">
		$('#btn-add').on('click', function() {
			$("[name=txt_grp], [name=id_grp], [name=url_grp]").val('');
			$("[name=txt_grp]").removeAttr('readonly');
			$('#exampleModalLabel111').text("Add new");
			$('input[name="txt_grp"]').val('');

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$("#add-frm").attr('action','{{url('settings/group-rights/add-group-rights-new')}}');

			$('#md-grp').modal('show');
		});

		// $('#btn-add-sub').on('click', function() {
		// 	$.ajax({
		// 		type: 'post',
		// 		url: '{{url('settings/group-rights/add-group-rights-new')}}',
		// 		data: $('#add-frm').serialize(),
		// 		success: function(data) {
		// 			if(data == 'okay'){
		// 				alert('Success');
		// 				location.reload();
		// 			} else {
		// 				alert(data);
		// 			}
		// 		},
		// 	});
		// });

		$('#dataTable').on('click', '.exclusive-delete-btn', function(event) {
			$('#del-msg').text($(this).attr('data'));
			$('#exampleModalLabel111').text('Delete');
			$('input[name="hidden_txt_id"]').val($(this).attr('restid'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$("#add-frm").attr('action','{{url('settings/group-rights/delete-group-rights-new')}}');
			$('#md-grp').modal('show');
		});

		$('#btn-del-sub').on('click', function() {
			$('#preloader').show();
			$.ajax({
				type: 'post',
				url: '{{url('settings/group-rights/delete-group-rights-new')}}',
				data: $('#add-frm').serialize(),
				success: function(data) {
					if(data == "okay" || data == "1" || data == 1) {
						alert('Deleted Successfully');
						location.reload();
					} else {
						alert(data);
					}
				}
			});

			setTimeout(function() {
				$('#preloader').hide();
			}, 600);
		});

		$('#restrictions-save-btn').on('click', function() {
			$('#preloader').show();
			$.ajax({
				type: 'post',
				url: '{{url('settings/group-rights/edit-rights')}}',
				data: $('#edit-restr-form').serialize(),
				success: function(data) {
					$('#preloader').hide();
					if(data > 0) {
						window.location = "{{url('settings/group-rights')}}";
					} else {
						alert(data);
					}
				},
			});
		});

		$(function(){
			$('#dataTable').on('click', '.btn-edit', function(event) {
				var that = this;
				$('#exampleModalLabel111').text("Edit Group right");
				$("[name=txt_grp]").val($(that).attr('dataid'));
				$("[name=txt_grp]").attr('readonly',true);
				$("[name=id_grp]").val($(that).attr('data-description'));
				$("[name=url_grp]").val($(that).attr('data-url'));
				$("#add-frm").attr('action','{{url('settings/group-rights/edit-group-rights-new')}}');
				$('.AddMode').show();
				$('.DeleteMode').hide();
				$('#md-grp').modal('show');
			});
		})

	</script>
	<script type="text/javascript">
		$('#btn-back').on('click', function()
		{
			showTable();
			table1.clear().draw();
		});
	</script>
	<script type="text/javascript">
		function showTable()
		{
			$('#div-info').addClass('hidden');
			$('#div-table').removeClass('hidden');
		}

		function showTable1()
		{
			$('#div-info').removeClass('hidden');
			$('#div-table').addClass('hidden');
		}
	</script>
@endsection