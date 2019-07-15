<!DOCTYPE html>
<html>
<head>
	<title>Notification Test</title>
	<link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>
	<div class="p-3">
		<form action="{{url('notification/send')}}" method="post">
			@csrf
			<textarea name="subject" rows="1" placeholder="notification subject"></textarea> <br>
			<textarea name="content" rows="5" placeholder="notification content"></textarea> <br> <br>
			Send to: <br>
				<input type="checkbox" name="groups[]" value="001"> <span class="bg-warning">ADMINISTRATOR</span> <br>
			@foreach($data[0] as $k => $v)
				<input type="checkbox" name="groups[]" value="{{$v->grp_id}}"> {{$v->grp_desc}} <br>
			@endforeach
			<br>
			<button type="submit">SEND</button>
		</form>
	</div>
</body>
</html>