@php 
// dd(Session::get('alert-type')) ;
@endphp
@if(Session::has('alert-type'))
	<div class="alert alert-{{Session::get('alert-type')}} alert-dismissible alert-topright" role="alert" id="hris-alert">
	  <strong>{{Session::get('alert-indi')}}</strong> | {{Session::get('alert-msg')}}
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>
@endif