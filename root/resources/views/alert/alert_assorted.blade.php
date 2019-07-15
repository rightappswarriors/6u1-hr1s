@php
	$alertIndi_2 = array('Success', 'Error');
	$alertType_2 = array('success', 'danger');
@endphp
@for($i=0;$i<count($alertType_2);$i++)
<div  class="alert alert-{{$alertType_2[$i]}} alert-dismissible alert-topright" style="display: none" role="alert" id="hris-alert-{{$alertType_2[$i]}}">
	<strong>{{$alertIndi_2[$i]}}</strong> | <span id="alert-{{$alertType_2[$i]}}-caption">{{MyQueryBuilder::Default_Alert_Msg($alertType_2[$i])}}</span>
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
  	</button>
</div>
@endfor