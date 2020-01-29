@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col-2">
					<i class="fa fa-fw fa-lock"></i>Log Box<br>
				</div>
				<div class="col">
					<div class="float-right">
						<a href="{{url('/timekeeping/')}}"><i class="fa fa-fw fa-clock-o"></i></a>
					</div>
				</div>
			</div>
		</div>

		<div class="row m-2">
			<div class="col-3 mr-3">
				<div class="row">
					Office:
				</div>
				<div class="row">
					<select class="form-control w-100" name="office" id="office" onchange="">
						<option disabled selected value="">Please select an office</option>
						@if(!empty($data[1]))
							@foreach($data[1] as $off)
								<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>

			<div class="col-3">
				<div class="row">
					Employee:
				</div>
				<div class="row">
					<select name="" id="employee" class="form-control mr-3">
						<option value="" disabled selected>SELECT EMPLOYEE</option>
					</select>
				</div>
			</div>

			<div class="col-3 mr-3">
				<div class="row">
					&nbsp;
				</div>
				<div class="row">
					<button class="btn btn-primary" title="Refresh" id="btn_refresh" style="transform: rotateY(180deg)"><i class="fa fa-repeat" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
		<div class="card-body mb-2 main-card-body bg-success">

			<div class="row mb-3">
				<div class="col">
					<button class="btn btn-white w-100 h-100 exclusive_filter_button" id="in">TIME IN</button>
				</div>

				<div class="col">
					<button class="btn btn-white w-100 h-100 exclusive_filter_button" id="out">TIME OUT</button>
				</div>
			</div>

			<div class="row" id="data_row">
				@isset($data[0])
					@foreach($data[0] as $k => $v)
						<div class="col-sm-4">
							<div class="card mb-4">
								<div class="card-header">
									<center><b>{{Employee::Name($v->empid)}}</b></center>
									<center>{{Employee::GetJobTitle($v->empid)}}</center>
								</div>
								<div class="card-body">
									<div class="nav-profile">
										@if(Employee::GetEmployee($v->empid)->picture != "")
											<center>
												<img src="{{asset('images/profile-imgs/'.Employee::GetEmployee($v->empid)->picture.'.jpg')}}" style="width: 50% !important">
											</center>
										@else
											<center>
												<img src="{{asset('images/profile-imgs/profile_user2.jpg')}}" style="width: 50% !important">
											</center>
										@endif
										
									</div>
								</div>
								<div class="card-footer">
									@if($v->status == "1")<b style="color: #3a4">TIME IN</b> @else <b style="color: #e34">TIME OUT</b>  @endif
									
									<b>: {{date('h:ia', strtotime($v->time_log))}} {{\Carbon\Carbon::parse($v->work_date)->format('M d, Y')}}</b>
								</div>
							</div>
						</div>
					@endforeach
				@endisset
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script>
		// local version - fixed
		var asset1 = {!! json_encode(url('/')) !!} + '/images/profile-imgs/';
		var asset2 = {!! json_encode(url('/')) !!} + '/images/profile-imgs/profile_user2.jpg';
		var yoarel ='{{url('timekeeping/log-box/in')}}';
		var time_in_seconds = 10;

		let office = "";
		let employee = "";
		// online version - obsolete
		// var asset1 = window.location.origin + '/images/profile-imgs/';
		// var asset2 = window.location.origin + '/images/profile-imgs/profile_user2.jpg';

		$('#btn_refresh').on('click', function() {
			$('#office').val('').trigger('change');
			$('#employee').val('').trigger('change');
			addCards();
		});

		$('#office').on('change', function() {
			employee = $(this).val();
			addCards();
		});

		$('#office').on('change', function() {

			while($('#employee')[0].firstChild) {
				$('#employee')[0].removeChild($('#employee')[0].firstChild);
			}

			var hiddenChild = document.createElement('option');
				hiddenChild.setAttribute('selected', '');
				hiddenChild.setAttribute('disabled', '');
				hiddenChild.setAttribute('value', '');
				hiddenChild.innerText='SELECT EMPLOYEE';

			$('#employee')[0].appendChild(hiddenChild);

			$.ajax({
				type: 'post',
				url: '{{url('timekeeping/timelog-entry/find-emp-office')}}',
				data: {ofc_id: $(this).val()},
				success: function(data) {
					// console.log(typeof(data));
					if(data.length > 0) {
						for(i=0; i<data.length; i++) {
							var option = document.createElement('option');
								option.setAttribute('value', data[i].empid);
								option.innerText=data[i].name;

							$('#employee')[0].appendChild(option);
						}
					}
				},
			});

			office = $(this).val();
			employee = "";
		});

		$('.exclusive_filter_button').on('click', function() {
			yoarel = '{{url('timekeeping/log-box/')}}/'+$(this).attr('id');
			addCards();
		});


		function addCards() {
			$.ajax({
				type: "post",
				url: yoarel,
				data: {"office": office, "employee": employee},
				success: function(response) {
					if (yoarel.split('/')[yoarel.split('/').length-1] == 'in') {
						$('.main-card-body').removeClass('bg-danger');
						$('.main-card-body').addClass('bg-success')	;
					} else {
						$('.main-card-body').removeClass('bg-success');
						$('.main-card-body').addClass('bg-danger');
					}

					var dataRow = document.getElementById('data_row');
					while(dataRow.firstChild) {
						dataRow.removeChild(dataRow.firstChild);
					}
					for(i = 0; i < response.length; i++) {
						var col = document.createElement('div');
							col.setAttribute('class', 'col-sm-4');
							var card = document.createElement('div');
								card.setAttribute('class', 'card mb-4');
								var cardHeader = document.createElement('div');
									cardHeader.setAttribute('class', 'card-header ');
									var center1 = document.createElement('center');
										center1.setAttribute('style', 'font-weight: bold');
										center1.innerHTML = response[i].name;
									var center2 = document.createElement('center');
										center2.innerHTML = response[i].position_readable;
									cardHeader.appendChild(center1);
									cardHeader.appendChild(center2);
								var cardBody = document.createElement('div');
									cardBody.setAttribute('class', 'card-body ');
									var navProfile = document.createElement('div');
										navProfile.setAttribute('class', 'nav-profile text-center');
										if(response[i].picture_readable != null) {
											var img1 = document.createElement('img');
												img1.setAttribute('src', asset1+response[i].picture_readable+'.jpg');
												img1.setAttribute('style', 'width: 50% !important');
											navProfile.appendChild(img1);
										} else {
											var img2 = document.createElement('img');
												img2.setAttribute('src', asset2);
												img2.setAttribute('style', 'width: 50% !important');
											navProfile.appendChild(img2);
										}
									cardBody.appendChild(navProfile);
								var cardFooter = document.createElement('div');
									cardFooter.setAttribute('class', 'card-footer');
									if(response[i].status == "1") {
										var bold1 = document.createElement('b');
											bold1.setAttribute('style', 'color: #3a4;');
											bold1.innerHTML = "TIME IN";
										cardFooter.appendChild(bold1);
									} else {
										var bold2 = document.createElement('b');
											bold2.setAttribute('style', 'color: #e34;');
											bold2.innerHTML = "TIME OUT";
										cardFooter.appendChild(bold2);
									}
									var bold3 = document.createElement('b');
										bold3.innerHTML = " : "+response[i].time_log_readable+" "+response[i].work_date_readable
									cardFooter.appendChild(bold3);
								card.appendChild(cardHeader);
								card.appendChild(cardBody);
								card.appendChild(cardFooter);
							col.appendChild(card);
						dataRow.appendChild(col);
					}
				}
			});
		}

	</script>

	<script>
		setInterval(function() {
			addCards();
		}, time_in_seconds * 1000);
	</script>
@endsection