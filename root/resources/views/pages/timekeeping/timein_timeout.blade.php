@extends('layouts.authentication')
{{-- @extends('pages.frontend.main_view') --}}

@section('to-head')
    <style>
        .email {
        display: block;
        padding: 15px 10px;
        margin-bottom: 10px;
        width: 100%;
        border: 1px solid #ddd;
        transition: border-width 0.2s ease;
        border-radius: 2px;
        color: #ccc;
        }
        .email:focus {
        outline: none;
        color: #444;
        border-color: #2196F3;
        border-left-width: 35px;
        }
        .password {
        display: block;
        padding: 15px 10px;
        margin-bottom: 10px;
        width: 100%;
        border: 1px solid #ddd;
        transition: border-width 0.2s ease;
        border-radius: 2px;
        color: #ccc;
        }
        .password:focus {
        outline: none;
        color: #444;
        border-color: #2196F3;
        border-left-width: 35px;
        }

        body {
            overflow-x: hidden !important;
        }
    </style>
@endsection
@php
    // dd($errors);
@endphp
@section('to-body')
    <div class="row p-3">
        <div class="col-3 my-auto">
            <div class="card card-login">
                <div class="card-header" align="center">
                    @include('extra.dev_msg3')
                    <h2>HRIS</h2>
                    <h7>TIME KEEPING</h7>
                    {{-- @include('extra.dev_msg2') --}}
                </div>
                <div class="card-body">
                    <!-- Alert-->
                    @include('alert.alert_simple')
                    <form method="POST" action="{{route('tk.tito')}}" autocomplete="off">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="acc_id"><strong>Employee ID</strong></label>
                            <input class="form-control email mb-1" name="acc_id" id="acc_id" type="text" placeholder="Enter Employee ID" required>
                            <label for="acc_id"><strong>Password</strong></label>
                            <input class="form-control email mb-1" name="acc_pwd" id="acc_pwd" type="password" placeholder="Enter Password" required>
                            @if ($errors->has('acc_id'))
                            <div class="error-span text-center">
                                {{ strtoupper($errors->first('acc_id')) }}
                            </div>
                            @endif
                        </div>
                        @if ($errors->has('acc_mode'))
                        <div class="error-span">
                            {{ ucfirst(strtolower($errors->first('acc_mode'))) }}
                        </div>
                        @endif
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-info btn-block" name="acc_mode" value="timein"><i class="fa fa-check-circle-o"></i> TIME IN</button>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-danger btn-block" name="acc_mode" value="timeout"><i class="fa fa-times-circle-o"></i> TIME OUT</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer" align="center">
                    <a class="d-block small" href="#" style="text-decoration:none;"><strong>Click Here to Apply Online</strong></a>
                </div>
            </div>
        </div>

        <div class="col-9">
            <div class="container">
                {{-- <div class="card"> --}}
                    {{-- <div class="card-header"></div> --}}
                    <div class="mb-2 main-card-body bg-success" style="min-height: 94vh;">

                        {{-- <div class="row mb-3">
                            <div class="col">
                                <button class="btn btn-white w-100 h-100 exclusive_filter_button" id="in">TIME IN</button>
                            </div>

                            <div class="col">
                                <button class="btn btn-white w-100 h-100 exclusive_filter_button" id="out">TIME OUT</button>
                            </div>
                        </div> --}}

                        <div class="row" id="data_row">
                            @isset($data[0])
                                @foreach($data[0] as $k => $v)
                                    <div class="col-sm-4">
                                        <div class="card m-2">
                                            @if(Employee::GetEmployee($v->empid)!=null)
                                            <div class="card-header">
                                                <center><b>{{Employee::Name($v->empid)}}</b></center>
                                                <center>{{Employee::GetJobTitle($v->empid)}}</center>
                                            </div>
                                            <div class="card-body">
                                                <div class="nav-profile">
                                                    @if(Employee::GetEmployee($v->empid)->picture != "")
                                                        <center>
                                                            <img src="{{asset('images/profile-imgs/'.Employee::GetEmployee($v->empid)->picture.'.jpg')}}" style="width: 50% !important">
                                                        </center>
                                                    @else
                                                        <center>
                                                            <img src="{{asset('images/profile-imgs/profile_user2.jpg')}}" style="width: 50% !important">
                                                        </center>
                                                    @endif
                                                    
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                @if($v->status == "1")<b style="color: #3a4">TIME IN</b> @else <b style="color: #e34">TIME OUT</b>  @endif
                                                
                                                <b>: {{date('h:ia', strtotime($v->time_log))}} {{\Carbon\Carbon::parse($v->work_date)->format('M d, Y')}}</b>
                                            </div>
                                            @else
                                            <div class="card-header">
                                                <center>USER NOT FOUND</center>
                                            </div>
                                            <div class="card-body">
                                                <div class="nav-profile">
                                                    <center>
                                                        <img src="{{asset('images/profile-imgs/user-error.png')}}" style="width: 50% !important">
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <center>ERROR LOADING USER INFO</center>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    {{-- </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>

    
@endsection

{{-- @section('to-bottom')
    <script type="text/javascript" src="{{asset('js/disable-enter-key.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/reload-on-idle.js')}}"></script>

    <script>
        var asset1 = {!! json_encode(url('/')) !!} + '/images/profile-imgs/';
        var asset2 = {!! json_encode(url('/')) !!} + '/images/profile-imgs/profile_user2.jpg';
        var yoarel ='{{url('timekeeping/x/in')}}';
        var time_in_seconds = 10;

        $('.exclusive_filter_button').on('click', function() {
            yoarel = '{{url('timekeeping')}}/x/'+$(this).attr('id');
            addCards();
        });

        function addCards() {
            $.ajax({
                type: "post",
                url: yoarel,
                data: {'_token':'{{csrf_token()}}'},
                success: function(response) {
                    if (yoarel.split('/')[yoarel.split('/').length-1] == 'in') {
                        $('.main-card-body').removeClass('bg-danger');
                        $('.main-card-body').addClass('bg-success') ;
                    } else {
                        $('.main-card-body').removeClass('bg-success');
                        $('.main-card-body').addClass('bg-danger');
                    }

                    var dataRow = document.getElementById('data_row');
                    while(dataRow.firstChild) {
                        dataRow.removeChild(dataRow.firstChild);
                    }
                    for(i = 0; i < response.length; i++) {
                        var col = document.createElement('div');
                            col.setAttribute('class', 'col-sm-4');
                            var card = document.createElement('div');
                                card.setAttribute('class', 'card m-2');
                                var cardHeader = document.createElement('div');
                                    cardHeader.setAttribute('class', 'card-header ');
                                    var center1 = document.createElement('center');
                                        center1.setAttribute('style', 'font-weight: bold');
                                        center1.innerHTML = response[i].name;
                                    var center2 = document.createElement('center');
                                        center2.innerHTML = response[i].position_readable;
                                    cardHeader.appendChild(center1);
                                    cardHeader.appendChild(center2);
                                var cardBody = document.createElement('div');
                                    cardBody.setAttribute('class', 'card-body ');
                                    var navProfile = document.createElement('div');
                                        navProfile.setAttribute('class', 'nav-profile text-center');
                                        if(response[i].picture_readable != null) {
                                            var img1 = document.createElement('img');
                                                img1.setAttribute('src', asset1+response[i].picture_readable+'.jpg');
                                                img1.setAttribute('style', 'width: 50% !important');
                                            navProfile.appendChild(img1);
                                        } else {
                                            var img2 = document.createElement('img');
                                                img2.setAttribute('src', asset2);
                                                img2.setAttribute('style', 'width: 50% !important');
                                            navProfile.appendChild(img2);
                                        }
                                    cardBody.appendChild(navProfile);
                                var cardFooter = document.createElement('div');
                                    cardFooter.setAttribute('class', 'card-footer');
                                    if(response[i].status == "1") {
                                        var bold1 = document.createElement('b');
                                            bold1.setAttribute('style', 'color: #3a4;');
                                            bold1.innerHTML = "TIME IN";
                                        cardFooter.appendChild(bold1);
                                    } else {
                                        var bold2 = document.createElement('b');
                                            bold2.setAttribute('style', 'color: #e34;');
                                            bold2.innerHTML = "TIME OUT";
                                        cardFooter.appendChild(bold2);
                                    }
                                    var bold3 = document.createElement('b');
                                        bold3.innerHTML = " : "+response[i].time_log_readable+" "+response[i].work_date_readable
                                    cardFooter.appendChild(bold3);
                                card.appendChild(cardHeader);
                                card.appendChild(cardBody);
                                card.appendChild(cardFooter);
                            col.appendChild(card);
                        dataRow.appendChild(col);
                    }
                }
            });
        }
    </script>

    <script>
        setInterval(function() {
            addCards();
        }, time_in_seconds * 500);
    </script>
@endsection --}}