@extends('pages.frontend.main_view')

@section('to-body')
	<div style="padding: 10px;" class="container">
        <div class="card mx-auto big-card">
            <div class="card-header" align="center">
        		<button class="float-left btn btn-primary" name="back" onclick="window.location = '{{url('/')}}'">Back</button>
                <h2>HOLIDAY CALENDAR</h2>
            </div>
            <div class="card-body row">
            	<div class="col-sm-2">
					<div class="card mb-4 p-3">
						<div class="row p-3">
							<div class="col-sm-12 mb-5"><b onclick="deleted_beta()">Legend:</b></div>
							<div class="col-sm-12 bg-primary text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded">Date Today</div>
							<div class="col-sm-12 bg-success text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded" id="RH">Regular Holiday</div>
							<div class="col-sm-12 bg-danger text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded" id="SH">Special Holiday</div>
						</div>
					</div>
				</div>
            	<div class="col-sm-10">
					<div id="OEI-scheduler"></div>
				</div>
            </div>
        </div>
    </div>
@endsection

@section('to-bottom')
	<script type="text/JavaScript" src='{{asset('js/fullcalendar.js')}}'></script>
	<script>
		$('#OEI-scheduler').fullCalendar({
		    themeSystem: 'bootstrap4',
		    height: 'auto',
		    selectable: true,
		    selectHelper: false,
		    aspectRatio: 1.6,
		    defaultDate: new Date,
		    editable: false,
		    eventLimit: false,
		    showNonCurrentDates: false,
		    header: {
		        left: 'prev,next today',
		        center: 'title',
		        right: '',
		    },

		    events: [
		    	{
		    		id: 'today',
		    		title: 'Today',
		    		start: '{{date('Y-m-d')}}',
		    		color: '#08f',
		    	},
		    	@foreach($data as $k => $v)
		    		{
		    			id: '{{$v->id}}*{{explode("^", Holiday::Get_Holiday_Color($v->holiday_type))[1]}}',
		    			title: '{{$v->description}}', 
		    			start: '{{$v->date_holiday}}',
		    			color: '{{explode("^", Holiday::Get_Holiday_Color($v->holiday_type))[0]}}',
		    		},
		    	@endforeach
		    ], 
		});
	</script>
@endsection

{{-- <html>
	<head>
		<link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    	<link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
		<link href='{{asset('css/fullcalendar.css')}}' rel='stylesheet' />
    	<link href='{{asset('css/fullcalendar.print.css')}}' rel='stylesheet' media='print' />
		<link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    	<style>
    		body {
    			background: rgb(58, 52, 64);
    		}
    	</style>
	</head>
	<body>
		<div style="padding: 10px;" class="container">
	        <div class="card mx-auto mt-5 big-card">
	            <div class="card-header" align="center">
	            	<form action="{{url('user/calendar/back')}}" method="get">
	            		@csrf()
	            		<button class="float-left btn btn-primary" name="back">Back</button>
	            	</form>
	                <h2>CALENDAR</h2>
	            </div>
	            <div class="card-body row">
	            	<div class="col-sm-2">
						<div class="card mb-4 p-3">
							<div class="row p-3">
								<div class="col-sm-12 mb-5"><b onclick="deleted_beta()">Legend:</b></div>
								<div class="col-sm-12 bg-success text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded" id="SH">Special Holiday</div>
								<div class="col-sm-12 bg-primary text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded" id="RH">Regular Holiday</div>
								<div class="col-sm-12 bg-danger text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded">Date Today</div>
							</div>
						</div>
					</div>
	            	<div class="col-sm-10">
						<div id="OEI-scheduler"></div>
					</div>
	            </div>
	        </div>
	    </div>
	</body>
	
	<script type="text/JavaScript" src='{{asset('js/moment.min.js')}}'></script>
	<script type="text/JavaScript" src='{{asset('js/jquery-3.3.1.min.js')}}'></script>
	<script type="text/JavaScript" src='{{asset('js/fullcalendar.js')}}'></script>
	<script>
		$('#OEI-scheduler').fullCalendar({
		    themeSystem: 'bootstrap4',
		    height: 'auto',
		    selectable: true,
		    selectHelper: false,
		    aspectRatio: 1.6,
		    defaultDate: new Date,
		    navLinks: true,
		    editable: false,
		    eventLimit: false,
		    showNonCurrentDates: false,
		    header: {
		        left: 'prev,next today',
		        center: 'title',
		        right: '',
		    },

		    events: [
		    	{
		    		id: 'today',
		    		title: 'Today',
		    		start: '{{date('Y-m-d')}}',
		    		color: '#f00',
		    	},
		    	@foreach($data as $k => $v)
		    		{
		    			id: '{{$v->id}}*{{explode("^", Holiday::Get_Holiday_Color($v->holiday_type))[1]}}',
		    			title: '{{$v->description}}', 
		    			start: '{{$v->date_holiday}}',
		    			color: '{{explode("^", Holiday::Get_Holiday_Color($v->holiday_type))[0]}}',
		    		},
		    	@endforeach
		    ], 
		});
	</script>
</html> --}}