@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-file-excel-o"></i> Philhealth Contributions Summary
		</div>
		<div class="card-body">
			<form method="post" action="{{url('reports/sss/find-philhealth')}}" id="frm-gp">
				<div class="container">
					<div class="form-group row">
						<div class="col-sm-5">
							<select class="form-control mr-2 select2" id="ofc" name="ofc">
								<option value="" selected="" disabled="">-Select office to generate-</option>
								@foreach($data[0] as $office)
								<option value="{{$office->cc_id}}">{{$office->cc_desc}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-5">
							<select name="pp" class="form-control mr-2" id="pp">
								<option value="" selected="" disabled="">-Select Payroll Period-</option>
							</select>
						</div>
						<div class="col-sm-2">
							<button type="button" class="btn btn-primary " onclick="PrintAllPage();">Print <i class="fa fa-print"></i></button>
							<i class="fa fa-spin fa-spinner ml-3" id="loadAnimation"></i>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<thead>
						<tr>
							<th>Philhealth Number</th>
							<th>Employee Name</th>
							<th>Employee Contribution</th>
							<th>Employer Contribution</th>
							<th>ECC</th>
							<th>TOTAL</th> 
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection


@section('to-bottom')
	<script type="text/javascript" src="{{url('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript" src="{{url('js/print-me.js')}}"></script>

	<script>
		var table = $('#dataTable').DataTable({
			"paging": false
		});
		$('#loadAnimation').hide();	
		// onchange jquery script for <select id="office">
		$('#ofc').on('change', function() {
			$('#pp').empty();
			$('#pp').append('<option value="">'+'-Select Payroll Period-'+'</option>');
			var ofc_id = $('#ofc :selected').val();
			var data = { 
                          _token : $('meta[name="csrf-token"]').attr('content'),
                          ofc_id : $('#ofc :selected').val(),

                       };	
		$.ajax({
					type: "post",
					url: "{{url('reports/sss/find-sss-pp')}}",
					data: data,
					beforeSend: function(){
					    $('#loadAnimation').show();
					},
					success: function(data) 
					{
						if(data.length <= 0)
						{
							alert('No Payroll Period Found');
							$('#loadAnimation').hide();	
							return false;
						}
						for(let i=0; i < data.length; i++)
						{
							var date_from = data[i].date_from;
							var date_to = data[i].date_to;
							//sample array sample
							if(date_from != null && date_to != null )
							{
								$('#pp').append('<option value='+'"'+date_from+'|'+date_to+'"'+'>'+date_from+ ' - ' +date_to+ '</option>');
							}	
							 
						}
						
						$('#loadAnimation').hide();		
					},
				});

		});

		$('#pp').on('change', function() {
			table.clear();
			var pp_split = $('#pp :selected').val();
			var pp =  pp_split.split('|');
			var data = { 
                          _token : $('meta[name="csrf-token"]').attr('content'),
                          ofc_id : $('#ofc :selected').val(),
                          pp : pp,
                       };
			$.ajax({
				type: "post",
				url: "{{url('reports/sss/find-sss')}}",
				data: data,
				beforeSend: function(){
				    $('#loadAnimation').show();
				},
				success: function(data) {
					for(let i=0; i < data.length; i++){
						
						//data[i][0].civil_status // display user details
						//data[i][1][0].empshare_ec //payments
						var name = data[i].firstname + ' ' + data[i].mi + ' ' + data[i].lastname;
						var emp1 = parseFloat((typeof(data[i]) != 'undefined' ? data[i].philhealth_cont_b : 0.00));
						var emp2 = parseFloat((typeof(data[i]) != 'undefined' ? data[i].philhealth_cont_c : 0.00));
						var emp3 = parseFloat((typeof(data[i]) != 'undefined' ? data[i].philhealth_cont_d : 0.00));
						if(data[i].philhealth == ''){
							var philhealth = '0-0-0';
						}
						else{
							var philhealth = data[i].philhealth;	
						}

						var sums = emp1 + emp2;
						table.row.add([
							philhealth,
							name,
							emp1,
							emp2,
							emp3,
							sums, 
						]).draw();
						
						console.log(data[i]);
					}		

					$('#loadAnimation').hide();	
				},
			});
		});

		function PrintAllPage(obj)
		{
			var pp_split = $('#pp :selected').val();
			var pp =  pp_split.split('|');
			var ofc_id = parseInt($('#ofc :selected').val());
			PrintPage("{{url('reports/philhealth/print')}}?ofc_id="+ofc_id+'&pp[]='+pp[0]+'&pp[]='+pp[1]);
		}
	</script>

@endsection
