						@isset($data[0])
						@foreach($data[0] as $timelog)
						<tr>
							<td>{{$timelog->work_date}}</td>
							<td>{{$timelog->time_log}}</td>
							<td>{{Core::IO($timelog->status)}}</td>
							<td>{{Core::source($timelog->source)}}</td>
						</tr>
						@endforeach
						@endisset