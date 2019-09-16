@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-wrench"></i> Notification Settings 
		</div>
		<div class="card-body mb-2">

			<ul class="nav nav-tabs mb-3">
				<li class="nav-item active">
			     	<a class="nav-link active" href="#home1" data-toggle="tab">Announcement Generator</a>
			  	</li>
			  	{{-- <li>
			    	<a class="nav-link" href="#menu1" data-toggle="tab">Menu 2</a>
			  	</li>
			  	<li>
			    	<a class="nav-link" href="#menu2" data-toggle="tab">Menu 3</a>
			  	</li> --}}
			</ul>

			<div class="tab-content">

				<div id="home1" class="tab-pane fade in active show">
			      	<div class="row">
						<div class="col-4">
							<div class="card">
								<div class="card-header">
									<center><b>Announcement Generator</b></center>
								</div>
								<div class="card-body">
									<form action="" id="announcement_form" method="post">
										<div class="form-group">
											<label>
												Groups:
												{{-- <i class="fa fa-fw fa-exclamation-circle text-danger" data-toggle="tooltip" data-placement="top" title="No groups selected will send it to all groups"></i> --}}
											</label>
											<select multiple id="awasd" name="cbo" placeholder="Select groups to include">
												<option value="001">ADMIN</option>
												@foreach (X07::Load_X07() as $k => $v)
													<option value="{{$v->grp_id}}">{{$v->grp_desc}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label>Announcement Title:</label>
											<input type="text" class="form-control" name="txt_title" placeholder="Announcement Title">
										</div>
										<div class="form-group">
											<label>Announcement Content:</label>
											<textarea type="text" class="form-control" name="txt_content" placeholder="Announcement Title" rows="6"></textarea>
										</div>
										<div class="row h-100">
											<div class="col-9">
												<div class="row">
													<div class="col-12">
														<label>Schedule Announcement:</label>
													</div>
												</div>
												<div class="row">
												<div class="col-7">
														<input type="text" class="form-control" name="txt_sched_date" id="txt_sched_date" placeholder="Set specific date" value="{{date('Y-m-d')}}" readonly> 
													</div>
													<div class="col-5">
														<input type="text" class="form-control" name="txt_sched_time" id="txt_sched_time" placeholder="Set specific time" value="{{date('H:i:s')}}" readonly> 
													</div>
												</div>
											</div>
											<div class="col-3">
												<div class="row">
													<div class="col-12">
														<label>&nbsp;</label>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<button class="btn btn-primary w-100" type="button" id="btn-send">Send</button>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
			    </div>

				{{-- <div id="menu1" class="tab-pane fade">
			      	<h3>Menu 1</h3>
			     	<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
			    </div>

			    <div id="menu2" class="tab-pane fade">
			      	<h3>Menu 2</h3>
			      	<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
			    </div> --}}
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<style>
		#awasd {
			position: relative;
			width: 85%;
		}
	</style>
	
	<script>
		var select = $('select');
		select.multipleSelect({
			selectAll: false,
			minimumCountSelected: 3,
			openOnHover: false,
		});
		$('.fa-exclamation-circle').tooltip();
		$('#txt_sched_date').datepicker(date_option2);
		$('#txt_sched_time').timepicker({
			showMeridian: true,
			showInputs: true,
			defaultTime: "default",
			minuteStep: 1,
			secondStep: 1,
		});
	</script>

	<script>
		$('#btn-send').on('click', function() {
			if(select.multipleSelect('getSelects').length < 1) {
				alert('Please select at least one group to send.');
			} else {
				if($('input[name="txt_title"]').val() == null || $('input[name="txt_title"]').val() == "") {
					alert('Please put a title.');
				} else if ($('textarea[name="txt_content"]').val() == null || $('textarea[name="txt_content"]').val() == "") {
					alert('Please put a content.');
				} else {
					$.ajax({
						type: $('#announcement_form').attr('method'),
						url: $('#announcement_form').attr('action'),
						data: {"cbo":select.multipleSelect('getSelects'), "title":$('input[name="txt_title"]').val(), "content":$('textarea[name="txt_content"]').val(), "sched":$('input[name="txt_sched"]').val(), "date":$('#txt_sched_date').val(), "time":$('#txt_sched_time').val()},
						success: function(data) {
							if(data == "Okay") {
								location.reload();
							}
						},
					});
				}
			}
		});
	</script>


@endsection