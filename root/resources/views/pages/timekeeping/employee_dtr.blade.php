@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-calendar"></i> Employee DTR
		</div>
		<div class="card-body mb-2">
			<form method="post" action="{{url('timekeeping/employee-dtr/load-dtr')}}" id="frm-loaddtr">
				<div class="form-inline">

					<div class="col-5">
						<div class="row">
							<div class="col-3">
								Office:
							</div>
							<div class="col">
								<select class="form-control mr-3 w-100" name="office" id="office" onchange="">
									<option disabled selected value="">Please select an office</option>
									@if(!empty($data[1]))
										@foreach($data[1] as $off)
											<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
										@endforeach
									@endif
								</select>
							</div>
							
						</div>

						<div class="row">
							<div class="col-3">
								Employee:
							</div>
							<div class="col">
								<select class="form-control w-100" name="tito_emp" id="tito_emp" required>
									<option disabled selected value="">---</option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-4">
						<div class="row">
							<div class="col-3">
								From:
							</div>
							<div class="col">
								<input type="text" name="date_from" id="date_from" class="form-control" value="{{date('Y-m-d')}}" required readonly>
							</div>
						</div>

						<div class="row">
							<div class="col-3">
								To:
							</div>
							<div class="col">
								<input type="text" name="date_to" id="date_to" class="form-control" value="{{date('Y-m-d')}}" required readonly>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary mr-2">Go</button>
					<button type="button" class="btn btn-primary" id="btn-print" data="#" onclick="PrintPage(this.getAttribute('data'))"><i class="fa fa-print"></i></button>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<thead>
						<tr>
							<th>Date</th>
							<th>Time In</th>
							<th>Time Out</th>
						</tr>
					</thead>
					<tbody class="text-center"></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript">
		$('#date_from').datepicker(date_option);
		$('#date_to').datepicker(date_option);

		$('#office').on('change', function() {

			while($('#tito_emp')[0].firstChild) {
				$('#tito_emp')[0].removeChild($('#tito_emp')[0].firstChild);
			}

			var hiddenChild = document.createElement('option');
				hiddenChild.setAttribute('selected', '');
				hiddenChild.setAttribute('disabled', '');
				hiddenChild.setAttribute('value', '');
				hiddenChild.innerText='Please select an office';

			$('#tito_emp')[0].appendChild(hiddenChild);

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

							$('#tito_emp')[0].appendChild(option);
						}
					}
				},
			});
		});
	</script>
	<script type="text/javascript">
		var table = $('#dataTable').DataTable();
		function LoadTable(data)
		{
			table.row.add([
				data.work_date,
				data.timein,
				data.timeout
			]).draw();
		}
		$('#frm-loaddtr').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type : $(this).attr('method'),
				url : $(this).attr('action'),
				data : $(this).serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data!="No record found.") {
							table.clear().draw();
							for(var i = 0 ; i < data.length; i++) {
								LoadTable(data[i]);
							}
							$('#btn-print').attr('data', '{{url('/timekeeping/employee-dtr/print-dtr')}}?tito_emp='+$('#tito_emp').val()+'&date_from='+$('#date_from').val()+'&date_to='+$('#date_to').val());
						} else {
							alert(data);
						}
					} else {
						alert('Error on loading data.');
					}
				}
			});
		})

		function ClearFld() {
			$('#btn-print').attr('data', '#');
		}
	</script>
	<script type="text/javascript">
		function PrintPage(page_location) {
			$("<iframe>")
	        .hide()
	        .attr("src", page_location)
	        .appendTo("body");   
		}
	</script>
@endsection