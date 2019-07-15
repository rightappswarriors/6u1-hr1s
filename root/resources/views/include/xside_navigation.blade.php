{{-- 

  READ ME

  When adding a new module in the sidebar
    • follow the format below to avoid confusion
      -> this also is important for the module highlighter

    • have the <li> tag carry an id with the url of the module without the slash "/",
      -> for example:
        <li id="master-fileoffice">
          <a href="{{url('master-file/office')}}">Office</a>
        </li>

    • as of now, there are only 3 levels, adding additional levels will be a problem.
      contact senior developer

 --}}

@php
  $NoPageRoute = route('redirect', ['page'=> '6']);
@endphp

<ul class="sidebar navbar-nav" style="background-color: #343a40;" id="sidebar-parent">
  <li class="nav-item">
    <div class="hris-img-profile nav-profile">
      @php
        $url = (Account::GET_IMAGE(Account::CURRENT()->uid) == '')?Core::$default_img:'root/storage/app/public/profile_images/'.Account::GET_IMAGE(Account::CURRENT()->uid);
      @endphp
      <img src="{{ url($url) }}" onclick="window.location = '{{url('/home/settings')}}'" id="dashboard_img">
      <style>
        #dashboard_img:hover { cursor: pointer; }
      </style> 
      <h6 class="hris-title-profile row mt-3 nav-link-text">
        <div class="col">
          {{Account::name()}}
        </div>
      </h6>
      <span class="hris-sub-profile nav-link-text">Present</span>
    </div>
  </li>
  {{-- NEUTRAL MENU --}}
  <li class="nav-item" id="home">
    <a class="nav-link" href="{{route('home')}}">
      <i class="fa fa-fw fa-dashboard"></i>
      <span>Dashboard</span>
    </a>
  </li>
  {{-- USER MENU --}}
  <li class="nav-item" id="masterfile">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#MF" aria-expanded="false">
      <i class="fa fa-fw fa-key"></i>
      <span>Master File</span>
    </a>
    <ul class="sidenav-second-level collapse-menu collapse" id="MF" data-parent="#sidebar-parent">
      <li id="master-fileoffice">
        <a href="{{url('master-file/office')}}">Office</a>
      </li>
      {{-- <li>
        <a href="{{url('master-file/department-section')}}">Department Section</a>
      </li> --}}
      <li id="master-filejob-title">
        <a href="{{url('master-file/job-title')}}">Job Title</a>
      </li>
      <li id="master-fileemployee-status">
        <a href="{{url('master-file/employee-status')}}">Employee Status</a>
      </li>
      <li id="master-fileemployee">
        <a href="{{url('master-file/employee')}}">Employee</a>
      </li>
{{--       <li id="master-fileshift-schedule">
        <a href="{{url('master-file/shift-schedule')}}">Shift Schedule</a>
      </li>
      <li id="master-fileemployee-shift-schedule">
        <a href="{{url('master-file/employee-shift-schedule')}}">Employee Shift Sched</a>
      </li> --}}
      {{-- <li>
        <a href="{{url('master-file/holidays')}}">Holidays</a>
      </li> --}}
      <li class="nav-separator"></li>
      {{-- <li>
        <a href="{{url('master-file/payroll-period')}}">Payroll Period</a>
      </li> --}}
      <li id="master-filewitholding-tax">
        <a href="{{url('master-file/witholding-tax')}}">Withholding Tax</a>
      </li>
      <li id="master-filesss">
        <a href="{{url('master-file/sss')}}">GSIS Table</a>
      </li>
      <li id="master-filephilhealth">
        <a href="{{url('master-file/philhealth')}}">PHILHEALTH</a>
      </li>
      <li id="master-filehdmf">
        <a href="{{url('master-file/hdmf')}}">HDMF</a>
      </li>
      <li id="master-fileloan-type">
        <a href="{{ url('master-file/loan-type') }}">Loan Types</a>
      </li>
      <li id="master-fileother-earnings">
        <a href="{{ url('master-file/other-earnings') }}">Other Earnings</a>
      </li>
      <li id="master-fileother-deductions">
        <a href="{{ url('master-file/other-deductions') }}">Other Deductions</a>
      </li>
      <li id="master-fileleave-types">
        <a href="{{ url('master-file/leave-types') }}">Leave Types</a>
      </li>
      {{-- <li class="nav-separator"></li>
      <li>
        <a href="{{ url('master-file/business-units') }}">Business Units</a>
      </li>
      <li>
        <a href="{{ url('master-file/contribution-remitance') }}">Contribution Remitance</a>
      </li> --}}
    </ul>
  </li>
  <li class="nav-item" id="calendar">
    <a class="nav-link" href="{{url('calendar/')}}">
      <i class="fa fa-calendar"></i>
      <span>Calendar</span>
    </a>
  </li>
  <li class="nav-item" id="timekeeping">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#TK" aria-expanded="false">
      <i class="fa fa-fw fa-clock-o"></i>
      <span>Timekeeping</span>
    </a>
    <ul class="sidenav-second-level collapse-menu collapse" id="TK" data-parent="#sidebar-parent">
      <li id="timekeepinglog-box">
        <a href="{{url('timekeeping/log-box')}}">Log Box</a>
      </li>
      <li class="nav-separator"></li>
      <li id="timekeepingupload-dtr">
        <a href="{{url('timekeeping/upload-dtr')}}">Upload DTR</a>
      </li>
      <li id="timekeepingtimelog-entry">
        <a href="{{url('timekeeping/timelog-entry')}}">Time Log Entry</a>
      </li>
      <li class="nav-separator"></li>
      <li id="timekeepingleaves-entry">
        <a href="{{url('timekeeping/leaves-entry')}}">Leaves Entry</a>
      </li>
      <li id="timekeepingemployee-dtr">
        <a href="{{url('timekeeping/employee-dtr')}}">Employee DTR</a>
      </li>
      <li class="nav-separator"></li>
      <li id="timekeepinggenerate-dtr">
        <a href="{{url('timekeeping/generate-dtr')}}">Generate DTR Summary</a>
      </li>
    </ul>
  </li>
  <li class="nav-item" id="payroll">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#PR" aria-expanded="false">
      <i class="fa fa-fw fa-money"></i>
      <span>Payroll</span>
    </a>
    <ul class="sidenav-second-level collapse-menu collapse" id="PR" data-parent="#sidebar-parent">
      <li id="payrollloan-entry">
        {{-- <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#PR_Loan" aria-expanded="false">Loan</a>
        <ul class="sidenav-third-level collapse" id="PR_Loan" data-parent="#PR">
          <li>
            <a href="{{url('payroll/loan-entry')}}">Loan Entry</a>
          </li>
          <li>
            <a href="{{url('payroll/loan-history')}}">Loan History</a>
          </li>
        </ul> --}}
        <a href="{{url('payroll/loan-entry')}}">Loan</a>
      </li>
      <li id="payrollother-earnings">
        <a href="{{url('payroll/other-earnings')}}">Other Earnings</a>
      </li>
      <li id="">
        <a href="#">Other Deductions Entry</a>
      </li>
      <li class="nav-separator"></li>
      <li id="payrollgenerate-payroll">
        <a href="{{url('payroll/generate-payroll')}}">Generate Payroll</a>
      </li>
      {{-- <li>
        <a href="{{url('payroll/view-generated-payroll')}}">View Generated Payroll(Merged w/ Generated Payroll)</a>
      </li> --}}
      {{-- <li id="payrollpayroll-register">
        <a href="{{ url('payroll/payroll-register') }}">Payroll Register</a>
      </li> --}}
      {{-- <li>
        <a href="#md-payslip" data-toggle="modal">Payslip(Merged w/ Payroll Register)</a>
      </li> --}}
    </ul>
  </li>
  <li class="nav-item" id="reps">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#Reports" aria-expanded="false">
      <i class="fa fa-fw fa-file"></i>
      <span>Reports</span>
    </a>
    <ul class="sidenav-second-level collapse-menu collapse" id="Reports" data-parent="#sidebar-parent">
      <li id="reportstimekeeping">
        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#Reports_TK" aria-expanded="false">Timekeeping</a>
        <ul class="sidenav-third-level collapse" id="Reports_TK" data-parent="#Reports">
          <li id="reportstimekeepingEmployeeDTR">
            <a href="{{url('reports/timekeeping/EmployeeDTR')}}">Print Employee DTR</a>
          </li>
          <li id="reportstimekeepingEmployeeDTRSummary">
            <a href="{{url('reports/timekeeping/EmployeeDTRSummary')}}">Print Employee DTR Summary</a>
          </li>
          <li id="reportstimekeepingDailyTimelogRecord">
            <a href="{{url('reports/timekeeping/DailyTimelogRecord')}}">Daily Timelog Records</a>
          </li>
          {{-- <li>
            <a href="#">Abscences, Late, and Undertime</a>
          </li> --}}
        </ul>
      </li>
      {{-- <li>
        <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#Reports_PR" aria-expanded="false">Payroll</a>
        <ul class="sidenav-third-level collapse" id="Reports_PR" data-parent="#Reports">
          <li>
            <a href="#">Payroll Summary Report</a>
          </li>
          <li>
            <a href="#">Witholding Tax Summary</a>
          </li>
          <li>
            <a href="#">SSS Contribution Summary</a>
          </li>
          <li>
            <a href="#">Philhealth Contribution Summary</a>
          </li>
          <li>
            <a href="#">PAG-IBIG Contribution Summary</a>
          </li>
          <li>
            <a href="#">13 Month Pay Summary</a>
          </li>
          <li>
            <a href="#">Leave Status Report</a>
          </li>
          <li>
            <a href="#">Commulative Payroll Report</a>
          </li>
          <li>
            <a href="#">Leave Balances Report</a>
          </li>
        </ul>
      </li> --}}
      <li id="reportspayroll-summary-report">
        <a href="{{url('reports/payroll-summary-report')}}">Payroll Summary Report</a>
        <!--<a href="{{url('reports/payroll/payroll-summary-report')}}">Payroll Summary Report</a>--> {{-- Separated from Reports -> Payroll --}}
      </li>
      {{-- <li>
        <a href="#">Other Deductions Entry</a>
      </li>
      <li>
        <a href="#">Generate Payroll</a>
      </li>
      <li>
        <a href="#">Payroll Register</a>
      </li> --}}
      <li id="">
        <a href="#">Payslip</a>
      </li>
    </ul>
  </li>
  <li class="nav-item" id="recs">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#Records" aria-expanded="false">
      <i class="fa fa-fw fa-book"></i>
      <span>Records</span>
    </a>
    <ul class="sidenav-second-level collapse-menu collapse" id="Records" data-parent="#sidebar-parent">
      <li id="recordsservice-record">
        <a href="{{url('records/service-record')}}">Service Record</a>
      </li>
      {{-- <li>
        <a href="{{ url('master-file/leave-types') }}">Leave Count</a>
      </li> --}}
    </ul>
  </li>
  <li class="nav-item" id="setts">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#Settings" aria-expanded="false">
      <i class="fa fa-fw fa-wrench"></i>
      <span>Settings</span>
    </a>
    <ul class="sidenav-second-level collapse-menu collapse" id="Settings" data-parent="#sidebar-parent">
      <li id="">
        <a href="#">Timekeeping Settings</a>
      </li>
      <li id="settingspayrollsettings">
        <a href="{{url('settings/payrollsettings')}}">Payroll Settings</a>
      </li>
      <li class="nav-separator"></li>
      <li id="settingsgroup-rights">
        <a href="{{url('settings/group-rights')}}">Group Rights Settings</a>
      </li>
      <li id="settingsuser">
        <a href="{{url('settings/user')}}">User Settings</a>
      </li>
      <li class="nav-separator"></li>
      <li id="settingsnotification">
        <a href="{{url('settings/notification')}}">Notification Settings</a>
      </li>
      <li id="settingssystem">
        <a href="{{url('settings/system')}}">System Settings</a>
      </li>
      <li id="settingserror-log">
        <a href="{{url('settings/error-log')}}">Error Log</a>
      </li>
      {{-- <li class="nav-separator"></li>
      <li>
        <a href="{{url('settings/system-data-update')}}">System Data Update</a>
      </li> --}}
    </ul>
  </li>
  <li class="nav-item" id="abts">
    <a class="nav-link" href="{{route('home')}}">
      <i class="fa fa-fw fa-question"></i>
      <span>About</span>
    </a>
  </li>
  {{-- Distance from the bottom page --}}
  <li class="nav-item mb-5"></li>
</ul>

<script>
  var side_par_id = [
    'MF',
    'TK',
    'PR',
    'Reports',
    'Records',
    'Settings',
  ];

  var sidebar_parents = [
    'master-file',
    'timekeeping',
    'payroll',
    'reports',
    'records',
    'settings',
  ];

  var sidebar_reports_parents = [
    ''
  ];


  var the_side_bar_link = window.location.href.split('/');

  var side_mod_name = the_side_bar_link[4];
  var side_mod_child_name = the_side_bar_link[4]+the_side_bar_link[5];
  var module_id = side_par_id[sidebar_parents.indexOf(side_mod_name)];

  $(document).ready(function () {
    $('#'+module_id).parent().children('a').removeClass('collapsed');
    $('#'+module_id).addClass('show');

    if(the_side_bar_link.length < 6) {
      $('#'+the_side_bar_link[4]).addClass('bg-info');
    } else if(the_side_bar_link.length > 6) {
      $('#'+the_side_bar_link[4]+the_side_bar_link[5]+the_side_bar_link[6]).parent().addClass('show');
      $('#'+the_side_bar_link[4]+the_side_bar_link[5]+the_side_bar_link[6]).parent().parent().children('a').removeClass('collapsed');
      $('#'+the_side_bar_link[4]+the_side_bar_link[5]+the_side_bar_link[6]).addClass('bg-info');
    } else {
      $('#'+side_mod_child_name).addClass('bg-info');
    }

    @isset(Account::CURRENT()->restriction)
      @foreach(X05S::Load_All() as $k => $v)
        $('#'+'{{$v->id}}')[0].setAttribute('hidden', '');
      @endforeach

      @foreach(X05S::Load_All() as $k => $v)
        if('{{Account::CURRENT()->restriction}}'.split(', ').includes('{{$v->id}}')) {
          $('#'+'{{$v->id}}')[0].removeAttribute('hidden');
        }
      @endforeach
    @endisset
  });
</script>