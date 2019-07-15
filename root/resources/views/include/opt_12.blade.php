<option value="" disabled selected>--</option>
@for($i=1;$i<=12;$i++)
  @php
    if($i<10) {
      $i = '0'.$i;
    }
  @endphp
<option>{{$i}}</option>
@endfor