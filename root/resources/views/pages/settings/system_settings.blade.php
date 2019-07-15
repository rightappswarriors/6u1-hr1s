@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-wrench"></i> System Settings <br>

			{{-- <span class="float-right">
				<button class="btn btn-warning" onclick="edit_toggle(this)">Edit</button>
			</span> --}}
		</div>
		<div class="card-body mb-2">
			
			<span class="float-left" id="mod_panel" hidden>
				<div class="row">
					<div class="col-sm-12 mb-2" style="width: 50px">
						<button class="btn btn-primary w-100" id="add">Add</button>
					</div>
					<div class="col-sm-12 mb-2" style="width: 50px">
						<button class="btn btn-warning w-100" onclick="edit_toggle(this)">Edit</button>
					</div>
				</div>
			</span>
			<div class="row">
				@isset($data)
					@isset($size)
						@php 
							$i=0;
						@endphp
						<script>
							var last_key;
							var all_keys = new Array();
						</script>
						@foreach($data as $k => $v)
							<script>
								last_key = '{{$k}}';
								all_keys.push('{{$k}}');
							</script>
							<div class="col-sm-3">
								<div class="card mb-2">
									<div class="card-header">{{$k}}</div>
									<div class="card-body">
										<textarea id="{{$k}}" name="txt_val" class="form-control" placeholder="No data available" oninput="save_data(this)" maxlength="{{ ($size[$i]->character_maximum_length != null)?$size[$i]->character_maximum_length : $size[$i]->numeric_precision }}" readonly>{{$v}}</textarea>
									</div>
								</div>
							</div>
							@php
								$i++;
							@endphp
						@endforeach
					@endisset
				@endisset
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
					<h5 class="modal-title" id="exampleModalLabel"></h5>
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
										<label>Name: <red>*</red></label>
										<input type="text" name="txt_name" class="form-control" required>
									</div>
									<div class="form-group">
										<label>Max Length: <red>*</red></label>
										<input type="number" name="text_len" class="form-control"  required>
									</div>
									<div class="form-group">
										<label>Value: <red>*</red></label>
										<input type="text" name="txt_val" class="form-control"  required>
									</div>
									{{-- <div class="form-group">
										<label>SQL String:</label>
										<textarea type="text" name="txt_sql" class="form-control" rows="3" required></textarea>
									</div> --}}
								</div>
							</div>
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
	<script>
		$('#add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('settings/system/')}}/add');

			$('input[name="txt_name"]').val('');
			$('input[name="text_len"]').val('');
			$('input[name="txt_val"]').val('');

			$('#exampleModalLabel').text('Add New Setting');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});
	</script>

	<script>
		function save_data(textarea) {
			textarea.style.height = "1px";
			textarea.style.height = (25+textarea.scrollHeight)+"px";

			$.ajax({
				type: "post",
				url: "{{url('settings/system/')}}",
				data: {"col":textarea.id, "val":textarea.value,},
				success: function(response) {
					console.log(response);
				}
			});
		}
	</script>

	<script>
		function edit_toggle(button) {
			console.log(all_keys);
			if( $('#'+last_key).attr('readonly') ) {
				for(i=0; i<all_keys.length; i++) {
					$('#'+all_keys[i]).removeAttr('readonly');
				}

				button.removeAttribute('class');
				button.setAttribute('class', 'btn btn-success w-100');

				button.innerText = 'Done';
			} else {
				for(i=0; i<all_keys.length; i++) {
					$('#'+all_keys[i]).attr('readonly', '');
				}

				button.removeAttribute('class');
				button.setAttribute('class', 'btn btn-warning w-100');

				button.innerText = 'Edit';
			}
		}
	</script>

	<script>
		document.createElement('red');
	</script>

	<style>
		red {
			color: red;
		}
	</style>
@endsection