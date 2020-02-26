@extends('layouts.user')

@section('to-head')
    {{-- <link href='{{asset('css/fullcalendar.css')}}' rel='stylesheet' />
    <link href='{{asset('css/fullcalendar.print.css')}}' rel='stylesheet' media='print' /> --}}
    <style type="text/css">
      a:hover {
        text-decoration: none!important;
      }
      .show {
        display: block;
      }
      .hide {
        display: none;
      }
      .my-custom-scrollbar {
      position: relative;
      height: 200px;
      overflow: auto;
      }
      .table-wrapper-scroll-y {
      display: block;
      }
      #nav-tab a{
        color: #fff;
        font-weight: bold;
        border-bottom: 0px;
      }
      #nav-tab .active{
        background-color: transparent;
      }
    </style>
@endsection

@section('to-body')
            @php
              $bg_color = array(
                'primary',
                'warning',
                'success',
                'danger',
                'info',
                'secondary',
                'light',
                'dark',
                'white',

              );
              $leave_color = array(
                "#dc3545",
                "#28a745",
                "#ffc107",
                "#0033E9",
              );

              if($data[5] < 0)
              {
                $curr_timein = 0;
              }
              else
              {
                $curr_timein = $data[5];
              }
              $leave_amount = array(
                \Carbon\Carbon::parse(date("Y-m-d"))->format('M d, Y'),
                ($data[1]==null)?"None":$data[1]->description,
                $curr_timein,
                ($data[3]==null)?"None":count($data[3]),
              );
              $leave_icon = array(
                "calendar",
                "gift",
                "clock-o",
                "plane",
              );
              $leave_text = array(
                "date today",
                "upcoming holiday",
                "currently timed-in",
                "employees on leave",
              );
              $leave_link = array(
                url('/calendar'),
                url('/calendar'),
                url('/timekeeping/log-box'),
                url('/timekeeping/leaves-entry'),
              );
              $dtr_table_id = 'dashboard-dtr-table';
              $news_alert_color = array(
                "success",
                "warning",
                "danger",
              );
              $news_qty = rand(0,9);
              // $news_qty = 0;
              if ($news_qty <= 0) {
                $notif_card = "hide";
                $notif_empty = "show";
              }
              else {
                $notif_card = "show";
                $notif_empty = "hide";
              }
              $dummy_desc_for_alerts  = array(
                'New announcement from the administration',
                'Your leave application is reviewed. Click to check its status.',
                'You need to take the Competency Assessment this year.',
              );
              $temp_leavetype = array(
                'Regular Duty',
                'Sick Leave',
                'Vacation Leave',
                'Maternity Leave',
              );
              $temp_status = array(
                'Approved',
                'Pending',
                'Dissapproved',
              );
              $currentMonth = date('m');
              $currentYear = date('Y');
              $numberOfDays = 5;
            @endphp

            <div class="row">
              <div class="col">
                {{-- <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">New Updates are Available!</h4>
                    <hr>
                    <marquee>
                      <p>There are new updates on Timekeeping, Payroll, and Reports. If there are any errors/bugs, please let us know so that we can fix it as soon as possible. Thank you.</p>
                    </marquee>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div class="row mb-3">
                  @for($a = 0; $a <=3; $a++)
                  <div class="col">
                    <div class="card text-white bg-{{$bg_color[$a]}} o-hidden h-100 dashboard-leave-menu">
                      <div class="card-body">
                        <div class="card-body-icon" style="opacity: 0.4;">
                          <i class="fa fa-fw fa-{{$leave_icon[$a]}}"></i>
                        </div>
                        <div class="text-uppercase" style="font-size: 27px;"><strong>{{$leave_amount[$a]}}</strong></div>
                        <div class="text-uppercase small">{{--$leave_text[$a]--}}</div>
                      </div>
                      <a class="card-footer text-white clearfix small z-1" href="{{$leave_link[$a]}}">
                        <span class="float-left text-uppercase">{{$leave_text[$a]}}</span>
                        <span class="float-right">
                          <i class="fa fa-angle-right"></i>
                        </span>
                      </a>
                    </div>
                  </div>
                  @endfor
                </div>
                <div class="row">
                  <div class="col mb-3">
                    <div class="dashboard-menu db-bg-boxColor" style="height: 100%;">
                      <div class="dashboard-menu-header">
                        <div class="form-row">
                          <div class="col-sm-6">
                            <h6 class="mt-3"><strong>Daily Time Record</strong></h6>
                          </div>
                          <div class="col-sm-6">
                            <nav>
                              <div class="nav nav-tabs btn-sm" id="nav-tab" role="tablist" style="width: 100%;">
                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Time In</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Time Out</a>
                              </div>
                            </nav>
                          </div>
                        </div>   
                      </div>
                      <div class="dashboard-menu-body">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar">
                          <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                              <table class="table table-bordered table-sm">
                                @if(count($data[0])!=0)
                                <thead>
                                  <tr>
                                    <th>Date</th>
                                    <th>Employee Name</th>
                                    <th>Time In</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($data[0] as $dtr)
                                  <tr>
                                    <td>{{$dtr->work_date}}</td>
                                    <td>{{Employee::Name($dtr->empid)}}</td>
                                    <td>{{$dtr->time_log}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                @else
                                <tbody>
                                  <tr>
                                    <td class="text-center">No record.</td>
                                  </tr>
                                </tbody>
                                @endif
                              </table>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                              <table class="table table-bordered table-sm">
                                @if(count($data[4])!=0)
                                <thead>
                                  <tr>
                                    <th>Date</th>
                                    <th>Employee Name</th>
                                    <th>Time Out</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($data[4] as $dtr)
                                  <tr>
                                    <td>{{$dtr->work_date}}</td>
                                    <td>{{Employee::Name($dtr->empid)}}</td>
                                    <td>{{$dtr->time_log}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                @else
                                <tbody>
                                  <tr>
                                    <td class="text-center">No record.</td>
                                  </tr>
                                </tbody>
                                @endif
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="dashboard-menu-icon">
                        <i class="fa fa-clock-o fa-5x"></i>
                      </div>
                      <div class="dashboard-menu-footer">
                        <a href="#" style="color:white;">View Monthly Daily Time Record</a>
                      </div>
                    </div>
                  </div>
                  <div class="col mb-3">
                    <div class="dashboard-menu db-bg-boxColor" style="height: 100%;">
                      <div class="dashboard-menu-header">
                        <h6 class="mt-3"><strong>Status Of Leave Application</strong></h6>
                      </div>
                      <div class="dashboard-menu-body">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar">
                          <table class="table table-bordered table-sm">
                            <thead>
                              <th>Name</th>
                              <th>Leave Type</th>
                              <th>Leave Dates</th>
                              <th>Days</th>
                              <th>With Pay</th>
                              <th>Status</th>
                            </thead>
                            <tbody>
                                @isset($data[6])
                                @foreach($data[6] as $key=>$value)
                                <tr>
                                  <td>{{$value->firstname .' '. $value->lastname}}</td>
                                  <td>{{$value->description}}</td>
                                  <td>{{Date('F jS, Y',strtotime($value->leave_from)) .'-'. Date('F jS, Y',strtotime($value->leave_to))}}</td>
                                  <td>{{$value->no_of_days}}</td>
                                  <td>{{$value->leave_pay}}</td>
                                  <td>{{($value->status == 0 ? 'Pending' : ($value->status == 1 ? 'Approved' : 'Disapproved'))}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                  <td class="text-center">No application.</td>
                                </tr>
                                @endisset
                              </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="dashboard-menu-icon">
                        <i class="fa fa-fw fa-file-text fa-5x"></i>
                      </div>
                      <div class="dashboard-menu-footer">
                        <a href="#" style="color:white;">View Leave Application</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col">
                    <div class="dashboard-menu db-bg-boxColor">
                      <div class="dashboard-menu-header"><strong>Action Items / Reminders</strong></div>
                      <div class="dashboard-menu-body" style="overflow-y: scroll; height: 200px;">
                        <div class="table-responsive">
                          <table class="table table-sm table-hover">
                            <tbody>
                              <tr>
                                <td class="text-center">No announcements.</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="dashboard-menu-icon">
                        <i class="fa fa-question-circle fa-5x"></i>
                      </div>
                      <div class="dashboard-menu-footer">
                        <a href="#" style="color:white;">View All Actions/Reminders</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="dashboard-menu db-bg-boxColor" style="height: 100%">
                  <div class="dashboard-menu-header">
                    <div class="dropdown dropleft float-right">
                      <span data-toggle="dropdown"><i class="fa fa-fw fa-exclamation-circle" data-toggle="tooltip" title="More Info" style="cursor: pointer;"></i></span>
                      <div class="dropdown-menu">
                        <div class="text-center small"><h6 class="">Select a color to filter</h6></div>
                        <div class="dropdown-divider"></div>
                        <table class="table table-bordered" style="cursor: pointer;">
                          <tr class="btn-primary" onclick="filterNews('all')">
                            <td colspan="2">Show All</td>
                          </tr>
                          <tr class="btn-success" onclick="filterNews('success')">
                            <td>Green</td>
                            <td>News</td>
                          </tr>
                          <tr class="btn-warning" onclick="filterNews('warning')">
                            <td>Yellow</td>
                            <td>Notification</td>
                          </tr>
                          <tr class="btn-danger" onclick="filterNews('danger')">
                            <td>Red</td>
                            <td>Important</td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <h6><strong>NEWS AND ANNOUNCEMENTS</strong></h6>
                  </div>
                  <div class="dashboard-menu-body">
                    <div class="notif-card show" name="notif-card-none">
                      <div class="text-center">
                        <label class="font-italic">No notification</label>
                      </div>
                    </div>
                  </div>
                  <div class="dashboard-menu-icon">
                    <i class="fa fa-fw fa-newspaper-o fa-5x"></i>
                  </div>
                  <div class="dashboard-menu-footer">
                    <a href="#" style="color:white;">View All News and Announcements</a>
                  </div>
                </div>
              </div>
            </div>
@endsection

@section('to-bottom')
        <script type="text/javascript">
          $('#dtr-table').DataTable(dataTable_config);
          function filterNews(cardName) {
            if (cardName == 'all') {
              var selected_card = document.getElementsByClassName('notif-card');
              var not_card = selected_card;
            }
            else {
              var selected_card = document.getElementsByName('notif-card-'+cardName);
              var not_card = document.getElementsByClassName('notif-card');
            }

            if(selected_card.length <= 0) {
              var selected_card = document.getElementsByName('notif-card-none');
              var not_card = document.getElementsByClassName('notif-card');
            }

            for(var i = 0; i < not_card.length; i++) {
              not_card[i].classList.remove('show');
              not_card[i].classList.add('hide');
            }
            for(var i = 0; i < selected_card.length; i++) {
              selected_card[i].classList.add('show');
              selected_card[i].classList.remove('hide');
            }
          }
          $(document).ready(function () {
            
            $('#dtBasicExample').dataTable({bFilter: false, pageLength: 5});
            $('.dataTables_length').css('display', 'none');
          });
        </script>
@endsection
