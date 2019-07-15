  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background-color: #4169e1 !important;">
    <a class="navbar-brand" href="{{route('enduser.index')}}">HRIS</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion" style="overflow-y: hidden;font-size:13px;">
        <li class="nav-item">
          <div class="hris-img-profile">
            <img src="{{asset('/images/anonymous-person-icon-18.jpg')}}">
            <h6 class="hris-title-profile">Welcome NAME_OF_USER</h6>
          </div>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="{{route('enduser.index')}}">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Dashboard</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Payroll Options">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#PDS" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-file"></i>
            <span class="nav-link-text">PDS</span>
          </a>
          <ul class="sidenav-second-level collapse" id="PDS">
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Page 1</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Page 2</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Page 3</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Page 4</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Generate PDS</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Payroll Options">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#Payroll_Options" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-dollar"></i>
            <span class="nav-link-text">Payroll Options</span>
          </a>
          <ul class="sidenav-second-level collapse" id="Payroll_Options">
            <li>
              <a href="{{route('payroll.viewpayroll')}}">View Payroll</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Payroll Summary Report</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Generate Payrolls</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Employee Loan</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Other Earnings</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="DTR">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#DTR_Options" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-calendar"></i>
            <span class="nav-link-text">Daily Track Record</span>
          </a>
          <ul class="sidenav-second-level collapse" id="DTR_Options">
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Upload DTR File</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Employee Time Logs</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Employee DTR</a>
            </li>
            <li>
              <a href="{{route('redirect.maintenance',['page'=>'0'])}}">Generate DTR</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Request for Leave">
          <a class="nav-link" href="{{route('redirect.maintenance',['page'=>'0'])}}">
            <i class="fa fa-fw fa-sign-out"></i>
            <span class="nav-link-text">Request for Leave</span>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown dropright" onclick="haha()">
          <a class="nav-link dropdown-toggle mr-lg-2" id="moreOptions" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-user"></i>
            <span class="d-lg-none">More Options
              <span class="badge badge-pill badge-primary">Click to View</span>
            </span>
            <span class="indicator text-danger d-none d-lg-block">
              <i class="fa fa-fw fa-circle" id="blah"></i>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="moreOptions">
            <h6 class="dropdown-header">More Options</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{route('redirect.maintenance',['page'=>'0'])}}">
              <strong>Account</strong>
              <span class="small float-right text-muted"><i class="fa fa-user"></i></span>
              <div class="dropdown-message small">Manage your account</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <strong>Reports</strong>
              <span class="small float-right text-muted"><i class="fa fa-th-list"></i></span>
              <div class="dropdown-message small">Online Personal Data Sheet, SALN, Employment<br> Record</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{route('redirect.maintenance',['page'=>'0'])}}">
              <strong>Online Competency Assesment</strong>
              <span class="small float-right text-muted"><i class="fa fa-window-restore"></i></span>
              <div class="dropdown-message small">Training Evaluation</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#logout" data-toggle="modal">
              <strong>Logout</strong>
              <span class="small float-right text-muted"><i class="fa fa-sign-out"></i></span>
              <div class="dropdown-message small">End Session</div>
            </a>
          </div>
        </li>
        <li class="nav-item dropdown dropright" onclick="haha2()">
          <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
            <span class="d-lg-none">Notifications
              <span class="badge badge-pill badge-warning">6 New</span>
            </span>
            <span class="indicator text-warning d-none d-lg-block">
              <i class="fa fa-fw fa-circle" id="blah2"></i>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">Notifications:</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">Sample Interview Schedule</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-danger">
                <strong>
                  <i class="fa fa-long-arrow-down fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">Sample Exam Schedule</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item small" href="#">View all alerts</a>
          </div>
        </li>
        <li class="nav-item">
          <form class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Search for...">
              <span class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </form>
        </li>
      </ul>
    </div>
  </nav>