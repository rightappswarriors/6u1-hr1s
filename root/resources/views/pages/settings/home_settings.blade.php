@extends('layouts.user')

@section('to-body')
	<style>
		.darken {
		    display: inline-block;
		    background: black;
		    padding: 0;
		}

		.darken img {
		    display: block;

		    -webkit-transition: all 0.3s linear;
		       -moz-transition: all 0.3s linear;
		        -ms-transition: all 0.3s linear;
		         -o-transition: all 0.3s linear;
		            transition: all 0.3s linear;
		}

		.darken:hover img{
		    opacity: 0.6;
		    cursor: pointer;
		}
	</style>
	<div class="card">
		<div class="card-header" id="print_name_hide">
			<div class="form-inline">
				<i class="fa fa-fw fa-user"></i>Personal Settings<br>
			</div>
		</div>
		<div class="card-body">
			@php
				$url = ($data[0] == '')?Core::$default_img:'root/storage/app/public/profile_images/'.$data[0];
			@endphp
			<div class="row">
				<div class="darken card">
					<img src="{{ url($url) }}" style="width: 250px !important">
					<div class="card-footer" style="background-color: white !important">
						<center><b>{{Account::NAME()}}</b></center>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
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
					<form method="post" action="#" id="frm-pp" data="#" enctype="multipart/form-data">
						@csrf
						<span class="Image" hidden>
							<div class="bg-warning p-1 mb-2">
								<i class="fa fa-exclamation-circle" aria-hidden="true"></i> 
								Make sure the image width and height are the same!
								Recommended size: <b>600pixel</b> by <b>600pixel</b>.
							</div>
							<input type="file" class="form-control" name="image" accept="image/x-png,image/gif,image/jpeg">
						</span>
					</form>
				</div>

				<div class="modal-footer">
					<span class="Image" hidden>
						<button type="submit" class="btn btn-primary mt-3" form="frm-pp">Upload</button>
					</span>
				</div>
			</div>
		</div>
	</div>
		
@endsection

@section('to-bottom')

	<script>
		$('.darken').on('mouseup', function() {
			$('#frm-pp').attr('action', '{{url('home/settings/')}}/upload');
			$('#exampleModalLabel').text('Change User Avatar');
			$('.Image').removeAttr('hidden');

			$('#modal-pp').modal('toggle');
		});
	</script>
@endsection