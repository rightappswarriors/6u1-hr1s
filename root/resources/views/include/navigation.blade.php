@php
  $nav_notif_msg = array();
  $nav_notif_msg_long = array(/*
    '',
    '',
  */);
  $nav_notif_link = array(/*
    'exam.examintro',
    'home.takeassessment',
  */);
  $nav_notif_alert = array(/*
    '2',
    '1',
  */);
  $nav_notif_icon = array(/*
    '0',
    '0',
  */);
  $nav_icon = array(/*
    '',
    '',
  */);
  $nav_a = 0;
  // $account_position = ((((Auth::user()!=null) ? Auth::user()->id : null)!=null) ? MyQueryBuilder::GetUserPosition(Auth::user()->id) : 'None');
  $nav_date = array();
  $nav_url = array();


foreach(Notification_N::Get_Latest_Notification(Account::CURRENT()->uid) as $k => $v) {
  // dd($v);
  if(!Notification_N::Get_Notification_Status($v->uid, $v->ntf_id)) {
    $nav_notif_msg[] = Notification_N::Get_Notification_Subject($v->ntf_id);
    $nav_notif_msg_long[] = Notification_N::Get_Notification_Content($v->ntf_id);
    $nav_date[] = Notification_N::Get_Notification_Date($v->ntf_id);
    $nav_url = Notification_N::Get_Notification_Url($v->ntf_id);
  }
    
}
@endphp
<nav class="navbar navbar-expand navbar-dark bg-dark static-top" style="background-color: #4169e1 !important;">
  <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
    <i class="fa fa-bars"></i>
  </button>
  <a class="navbar-brand mr-1" href="#">{{ config('app.name', 'Laravel') }} | {{Account::UAG()}}</a>
  <div class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0"></div>
  <ul class="navbar-nav">
    <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" href="" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bell fa-fw"></i>
        {{-- @if(count($nav_notif_msg) > 0) --}}
        {{-- <span class="badge badge-danger" id="notif_count">{{(count($nav_notif_msg) > 10) ? '10+' : count($nav_notif_msg)}}</span> --}}
        {{-- @else --}}
        <span class="badge badge-danger" id="notif_count"></span>
        {{-- @endif --}}
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown" style="width: 25vw !important; overflow-y: scroll; max-height: 40vh;" id="main_parent_div">
        <h6 class="dropdown-header">Notifications:</h6>
        <div class="dropdown-divider"></div>
        {{-- @if(count($nav_notif_msg) > 0)
          @foreach($nav_notif_msg as $notif_msg)
          <a class="dropdown-item" href="{{$nav_url[$nav_a]}}">
            <span class="text-danger">
              <b>{{$nav_notif_msg[$nav_a]}}</b>
            </span>
            <span class="small float-right text-muted">
              {{substr(substr($nav_date[$nav_a], 11), 0, 5)}}
            </span>
            <div class="dropdown-message small">
              {{$nav_notif_msg_long[$nav_a]}}
            </div>
          </a>
          @php
            $nav_a++;
          @endphp
          @endforeach
        @else
          <a class="dropdown-item" href="#">
            <span class="text-info">
              <b>No new notification</b>
            </span>
            <div class="dropdown-message small">
              You are all up to date.
            </div>
          </a>
        @endif

        <div class="dropdown-divider"></div>
        <a class="dropdown-item small" href="#">View all alerts</a> --}}
      </div>
    </li>
    {{-- <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-envelope fa-fw"></i>
        <span class="badge badge-danger">0</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
        <h6 class="dropdown-header">Messages:</h6>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">Administration</a>
        <a class="dropdown-item" href="#">Reply : Leave Application</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item small" href="#">View all Messages</a>
      </div>
    </li> --}}
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-user-circle fa-fw"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="{{url('/home/settings')}}">Settings</a>
        {{-- @if($account_position!='ADMINISTRATOR') --}}
          <a class="dropdown-item" href="#">Activity Log</a>
        {{-- @endif --}}
        {{-- @if($account_position=='ADMINISTRATOR') --}}
          <a class="dropdown-item" href="#">Overall Activity Log</a>
        {{-- @endif --}}
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logout">Logout</a>
      </div>
    </li>
  </ul>
</nav>

<script>
  var notif_base_yoarel = {!! json_encode(url('/')) !!} ;
  var notif_yoarel = notif_base_yoarel + '/notification/find';

  $('#alertsDropdown').on('click', function() {
    notif_find(); // this function is found in "include.notification_handler" blade
  });
</script>