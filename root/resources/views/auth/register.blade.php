@extends('layouts.authentication')

@php
  $form_dir = 'forms.pis_forms';
@endphp

@section('to-head')
  <style type="text/css">
    .fade {
      display: none;
    }
    .fade.show {
      display: block;
    }
  </style>
@endsection

@section('to-body')
<div style="padding: 50px;">
  <form autocomplete="off" id="ApplicationForm" method="post" action="{{route('module.newapplication')}}" enctype="multipart/form-data">
    @csrf
    <div class="card">
      <div class="card-header">
        <i class="fa fa-pencil"></i><i class="fa fa-globe"></i> <strong>Apply Online</strong>
      </div>
      <div class="card-body">
        <div class="mb-5 ml-5 mr-5{{(session()->has('msg')) ? ' hidden' : ' fade show'}}" id="review_card">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label><strong><i class="fa fa-user"></i> <span style="color:red;">*</span>Name:</strong></label>
                @if ($errors->has('ao_fname'))
                  <div class="error-span mt-0">
                    {{ $errors->first('ao_fname') }}
                  </div>
                @endif
                <input type="text" name="ao_fname" class="form-control mb-2" placeholder="Firstname" value="{{old('ao_fname')}}">
                @if ($errors->has('ao_mname'))
                  <div class="error-span mt-0">
                    {{ $errors->first('ao_mname') }}
                  </div>
                @endif
                <input type="text" name="ao_mname" class="form-control mb-2" placeholder="Middlename" value="{{old('ao_mname')}}">
                @if ($errors->has('ao_lname'))
                  <div class="error-span mt-0">
                    {{ $errors->first('ao_lname') }}
                  </div>
                @endif
                <input type="text" name="ao_lname" class="form-control mb-2" placeholder="Lastname"  value="{{old('ao_lname')}}">
                <input type="text" name="ao_namextension" class="form-control mb-2" placeholder="Name Extension(Optional)"  value="{{old('ao_namextension')}}">
              </div>
              <div class="form-group">
                <label><strong><i class="fa fa-hashtag"></i> <span style="color:red;">*</span>Age</strong></label>
                <input type="number" name="ao_age" class="form-control" min="0" placeholder="---" value="{{old('ao_age')}}">
                @if ($errors->has('ao_age'))
                  <div class="error-span">
                    {{ $errors->first('ao_age') }}
                  </div>
                @endif
              </div>
              <div class="form-group">
                <label><strong><i class="fa fa-certificate"></i> <span style="color:red;">*</span>Course</strong></label>
                <input type="text" name="age_course" class="form-control" placeholder="---" value="{{old('age_course')}}">
                @if ($errors->has('age_course'))
                  <div class="error-span">
                    {{ $errors->first('age_course') }}
                  </div>
                @endif
              </div>
              <div class="form-group">
                <label><strong><i class="fa fa-graduation-cap"></i> <span style="color:red;">*</span>Year Graduated</strong></label>
                <div class="form-inline">
                  <input type="text" name="ao_yeargrad" class="form-control" placeholder="---" onkeypress="return isNumber(event)" value="{{old('ao_yeargrad')}}">
                </div>
              </div>
              <div class="form-group">
                <label><strong><i class="fa fa-envelope"></i> <span style="color:red;">*</span>Email Address</strong></label>
                <input type="email" name="ao_email" class="form-control mb-2" placeholder="---"  value="{{old('ao_email')}}">
                @if ($errors->has('ao_email'))
                  <div class="error-span">
                    {{ $errors->first('ao_email') }}
                  </div>
                @endif
                <small class="form-text text-muted">We will send the results of your application through your email address. Make sure that the email entered above is correct.</small>
              </div>
              <div class="form-group">
                <label><strong><i class="fa fa-address-card"></i> <span style="color:red;">*</span> Upload Resume</strong></label>
                <input type='file' class="form-control-file file-array border" name="ao_resume" />
                @if ($errors->has('ao_resume'))
                  <div class="error-span">
                    {{ $errors->first('ao_resume') }}
                  </div>
                @endif
              </div>
              <div class="form-group">
                <label><strong><i class="fa fa-file-text"></i> <span style="color:red;">*</span> Upload Application Letter</strong></label>
                <input type='file' class="form-control-file file-array border" name="ao_application_letter" />
                @if ($errors->has('ao_application_letter'))
                  <div class="error-span">
                    {{ $errors->first('ao_application_letter') }}
                  </div>
                @endif
              </div>
            </div>
            <div class="col">
              @include($form_dir.".account_image")
            </div>
          </div>
          <span onclick="Form_Submit()"><button type="submit" class="btn btn-success btn-block btn-spin">Send Application</button></span>
          <h4 class="mb-2 text-center">(Assessing your application may take 2 to 3 days. Thank you for your patience.)</h4>
        </div>
        <div class="m-5{{(session()->has('msg')) ? ' hidden' : ' fade'}} text-center" id="send_card">
          <h3>Processing application.</h3>
          <h4><i class="fa fa-spinner fa-spin fa-5x mt-2"></i></h4>
          <h6>Please wait.</h6>
        </div>
        <div class="m-5{{(session()->has('msg')) ? '' : ' hidden'}} text-center">
          <h3 class="text-success">Application Submitted</h3>
          <h4><i class="fa fa-check-circle-o fa-5x mt-2 text-success"></i></h4>
          <h6 class="text-success">Thank you for sending your application!</h6>
          <h7><a href="{{route('login')}}">Return to login page!</a></h7>
        </div>
      </div>
      <div class="card-footer">
        <div class="text-center">
          <a href="{{route('login')}}"><strong>Already have an acccount? Click here to login</strong></a>
        </div>
      </div>
    </div>
    {{-- ######################################################### --}}
    {{-- <div class="fixed-bottom" style="left: 5%; bottom: 5%;">
      <a class="small mt-3 text-white" href="{{route('login')}}" style="text-decoration:none;">
        <strong class="rounded" style="padding: 20px;background-color: black;">Already have an account? Click here to log-in</strong>
      </a>
    </div> --}}
  </form>
</div>
@endsection