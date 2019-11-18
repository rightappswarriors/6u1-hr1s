<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">

    <!-- CSRF Token-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <?php define("token", Session::token());?>
    <link rel="icon" type="image/png" href="{{asset('img/guihulngan.png')}}" sizes="32x32" />


    <title>{{ config('app.name') }}</title>

    <!-- Bootstrap core CSS-->
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    {{-- <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css"> --}}
    <!-- Jquery UI CSS-->
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">

    <!-- Custom styles-->
    <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom-login.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom-ui.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/buttons.dataTables.min.css')}}">

    <!-- Full Calendar -->
    <link href='{{asset('css/fullcalendar.css')}}' rel='stylesheet' />
    <link href='{{asset('css/fullcalendar.print.css')}}' rel='stylesheet' media='print' />
    
    <!-- Croppic -->
    <link rel="stylesheet" href="{{asset('css/croppic.css')}}">

    <!-- Custome Loader -->
    <link rel="stylesheet" href="{{asset('css/custom-loader.css')}}">

    <!-- Custome Multiple Select -->
    <link rel="stylesheet" href="{{asset('css/multiple-select.css')}}">

    <!-- Custom Time Picker -->
    <link rel="stylesheet" href="{{asset('css/bootstrap-timepicker.css')}}">

    <style>
        .parsley-errors-list {
            color:red;
            list-style: none;
        }

        .overlay-body {
            background-color: rgba(1, 1, 1, 0);
            bottom: 0;
            left: 0;
            position: fixed;
            right: 0;
            top: 0;
        }
        .checkbox-solo {
            outline: none;
            border-color: $fff !important;
            box-shadow: none !important;
        }
    </style>
    <style type="text/css">
        @media print
        {    
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>

    <!-- Head Scripts-->
    <script type="text/JavaScript">
    function haha(){
        document.getElementById('blah').style.display="none";
    }
    function haha2(){
        document.getElementById('blah2').style.display="none";
    }
    </script>
    @yield('to-head')

    <!-- Jquery Core JS-->
    <script type="text/JavaScript" src='{{asset('js/jquery-3.3.1.min.js')}}'></script>

</head>
<body id="page-top">
   
    <div id='preloader'>
        <div class='spinner'>
            <span class='loader'><span class='loader-inner'></span></span>
        </div>
    </div> 

    <div id="wrapper">
        @include('include.xside_navigation')
        <div id="content-wrapper" style="padding-top: 0!important;">
            @include('include.navigation')
            <div class="container-fluid">
                <div style="z-index: 1;" id="overlay-body"></div> {{-- Used to overlay if the session _user is missing --}}
                <div class="mt-3">
                    <!-- Breadcrumbs-->
                    {{-- <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                        <a href="#">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Overview</li>
                    </ol> --}}
                    @yield('to-body')
                </div>
            </div>
            {{-- @include('include.footer') --}}
            <!-- Scroll to Top Button-->
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="logoutLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                    {{-- <a class="btn btn-primary" href="{{route('home.personnel-locator.login')}}">Logout</a> --}}
                </div>
            </div>
        </div>
    </div>
    @yield('to-modal')
    @include('include.modal_payslip')

    <!-- Alert-->
    @include('alert.alert_simple')

    <!-- Notification Handler -->
    @include('include.notification_handler')

    <!-- Scripts-->
    <script type="text/JavaScript" src='{{asset('js/moment.min.js')}}'></script>
    {{-- <script type="text/JavaScript" src='{{asset('js/jquery-3.3.1.min.js')}}'></script> --}}
    <script type="text/JavaScript" src='{{asset('js/fullcalendar.js')}}'></script>
    {{-- <script type="text/JavaScript" src="{{asset('vendor/jquery/jquery.min.js')}}"></script> --}}
    <script type="text/JavaScript" src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/JavaScript" src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/datatables/jquery.dataTables.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.bootstrap4.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/sb-admin.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/sb-admin-datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/custom-user.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/custom-btn.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/dataTables.buttons.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/parsley.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/simple-math.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/croppic.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/exif.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/multiple-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap-timepicker.js')}}"></script>
    
    @include('include.js_dateoption_config')
    <script type="text/javascript">
        let preloader_timeinseconds_before_gone = .85; // in seconds

        $(document).ready(function(){
            // Update the current year in copyright
            $('.is-current-year').text(new Date().getFullYear());
            // Hide the preloader once the page is loaded
            setTimeout(function() {
                $('#preloader').hide();
            }, preloader_timeinseconds_before_gone * 1000);
        });
        

        $("#hris-alert").fadeTo(10000, 500).slideUp(500, function(){
            $("#hris-alert").slideUp(10000);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script type="text/javascript">
        function CheckAuthJS()
        {
            $.get('{{url('sessions/check')}}', function(data) {
                if (data!="ok") {
                    $('#page-top').on('click', function() {
                        alert("Session Expired. Please reload the page.");
                    });
                    $('#overlay-body').addClass('overlay-body');
                    // window.location.reload(true);
                } else {
                    setTimeout(CheckAuthJS, 10000);
                }
            });
        }
        setTimeout(CheckAuthJS, 10000);
    </script>
    <script type="text/javascript">
        function NoSelectedRow()
        {
            alert("Please select a row.");
        }
    </script>
    <script type="text/javascript">
        function togglePreloader() //for toggling the preloader for ajax and other javascripts
        {
            if ($('#preloader').is(":hidden")) {
                $('#preloader').show();
            } else {
                $('#preloader').hide();
            }
        }
    </script>
    @yield('to-bottom')
</body>
</html>
