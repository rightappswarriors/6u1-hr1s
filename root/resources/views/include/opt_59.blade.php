<option value="" disabled selected>--</option>
@for($i=0;$i<=59;$i++)
  @php
    if($i<10) {
      $i = '0'.$i;
    }
  @endphp
<option>{{$i}}</option>
@endfor