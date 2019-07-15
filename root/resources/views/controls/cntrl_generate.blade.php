{{--
	Include the following variables to the page where you put this tag:
	=> $cntrl_generate_class
	=> $cntrl_generate_txt
	=> $cntrl_generate_attr
--}}
{{-- <div class="btn-group mr-3" role="group"> --}}
	<button type="button" class="btn btn-primary btn-spin{{isset($cntrl_generate_class) ? ' '.$cntrl_generate_class : ''}} mr-1" {{isset($cntrl_generate_attr) ? " ".$cntrl_generate_attr : ""}}>Generate{{isset($cntrl_generate_txt) ? ' '.$cntrl_generate_txt : ''}}</button>
{{-- </div> --}}