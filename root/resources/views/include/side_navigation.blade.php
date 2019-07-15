@php
  $NoPageRoute = route('redirect', ['page'=> '6']);
@endphp
<ul class="sidebar navbar-nav" style="background-color: #343a40;" id="sidebar-parent">
  <li class="nav-item">
    <div class="hris-img-profile nav-profile">
      <img src="{{asset('/images/profile-imgs/profile_user2.jpg')}}">
      <h6 class="hris-title-profile row mt-3 nav-link-text">
        <div class="col">
          {{Account::name()}}
        </div>
      </h6>
      <span class="hris-sub-profile nav-link-text">Present</span>
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{route('home')}}">
      <i class="fa fa-fw fa-dashboard"></i>
      <span>Dashboard</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{route('home')}}">
      <i class="fa fa-fw fa-question"></i>
      <span>About</span>
    </a>
  </li>
  {{-- Distance from the bottom page --}}
  <li class="nav-item mb-5"></li>
</ul> 