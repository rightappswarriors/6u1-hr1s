<?php

/* LOGIN */
	Route::get('/login', 'AuthController@view')->name('login');
	Route::post('/login', 'AuthController@login');
	Route::get('/logout', 'AuthController@logout')->name('logout');
/* LOGIN */

/* TIMEKEEPING */
	Route::get('/timekeeping', 'Timekeeping\TiToController@view')->name('tk.tito');
	Route::post('/timekeeping', 'Timekeeping\TiToController@TimeLog');
	Route::post('/timekeeping/x/in', 'TimeKeeping\TiToController@getLastestTimeIn');
	Route::post('/timekeeping/x/out', 'TimeKeeping\TiToController@getLastestTimeOut');
/* TIMEKEEPING */

/* HOME */
	Route::get('/', 'Frontend\IndexController@view')->name('real_home');
/* HOME */

/* FRONT-END */
	Route::prefix('/user')->group(function() {
		Route::get('/calendar', 'Frontend\CalendarViewController@view');
	});
	/* ONLINE APPLICATION */
		Route::prefix('online-application')->group(function() {
			Route::get('/', 'MFile\EmployeeController@application_view');
		});
	/* ONLINE APPLICATION */
/* FRONT-END */

/* AUTHENTICATED ROUTES */
	Route::group(['middleware'=>['authenticated']], function() {
		/* HOME */
		Route::prefix('/home')->group(function () {
			Route::get('/', 'HomeController@view')->name('home');
			Route::prefix('/settings')->group(function () {
				Route::get('/', 'HomeController@loadSetting')->name('home_settings');
				Route::post('/upload', 'HomeController@uploadImage')->name('home_settings_image	');
			});
		});
		
		/* HOME */
		/* MASTER FILE */

		// Route::group(['middleware'=>['rights':]]);

		Route::prefix('master-file')->group(function() {
			/* DEPARTMENT */
			// Route::prefix('department')->group(function() {
			// 	Route::get('/', 'MFile\DepartmentController@view');
			// 	Route::post('/', 'MFile\DepartmentController@add');
			// 	Route::post('/update', 'MFile\DepartmentController@update');
			// 	Route::post('/delete', 'MFile\DepartmentController@delete');
			// });
			/* DEPARTMENT */
			/* DEPARTMENT */
			Route::prefix('office')->group(function() {
				Route::get('/', 'MFile\OfficeController@view');
				Route::post('/', 'MFile\OfficeController@add');
				Route::post('/update', 'MFile\OfficeController@update');
				Route::post('/delete', 'MFile\OfficeController@delete');
			});
			/* DEPARTMENT */
			/* DEPARTMENT SECTION */
			Route::prefix('department-section')->group(function() {
				Route::get('/', 'MFile\DepartmentSectionController@view');
				Route::post('/', 'MFile\DepartmentSectionController@add');
				Route::post('/update', 'MFile\DepartmentSectionController@update');
				Route::post('/delete', 'MFile\DepartmentSectionController@delete');
				Route::get('/getOne', 'MFile\DepartmentSectionController@getOne');
			});
			/* DEPARTMENT SECTION */
			/* JOB TITLE */
			Route::prefix('job-title')->group(function() {
				Route::get('/', 'MFile\JobTitleController@view');
				Route::post('/', 'MFile\JobTitleController@add');
				Route::post('/update', 'MFile\JobTitleController@update');
				Route::post('/check-jt', 'MFile\JobTitleController@check');
				Route::post('/delete', 'MFile\JobTitleController@delete');
			});
			/* JOB TITLE */
			/* EMPLOYEE STATUS */
			Route::prefix('employee-status')->group(function() {
				Route::get('/', 'MFile\EmployeeStatusController@view');
				Route::post('/', 'MFile\EmployeeStatusController@add');
				Route::post('/update', 'MFile\EmployeeStatusController@update');
				Route::post('/delete', 'MFile\EmployeeStatusController@delete');
			});
			/* EMPLOYEE STATUS */
			/* EMPLOYEE */
			Route::prefix('employee')->group(function() {
				Route::get('/', 'MFile\EmployeeController@view');
				Route::get('/new', 'MFile\EmployeeController@new');
				Route::get('/edit/{id}', 'MFile\EmployeeController@edit');
				Route::post('/', 'MFile\EmployeeController@add');
				Route::post('/getOne', 'MFile\EmployeeController@getOneEmployee');
				Route::post('/update', 'MFile\EmployeeController@update');
				Route::post('/delete', 'MFile\EmployeeController@delete');
				//prototype
				Route::get('/new2', 'MFile\EmployeeController@new2');
				Route::post('/add2', 'MFile\EmployeeController@add2');
			});
			/* EMPLOYEE */
			/* SHIFT SCHEDULE */
			Route::prefix('shift-schedule')->group(function() {
				Route::get('/', 'MFile\ShiftScheduleController@view');
				Route::post('/', 'MFile\ShiftScheduleController@add');
				Route::post('/update', 'MFile\ShiftScheduleController@update');
				Route::post('/delete', 'MFile\ShiftScheduleController@delete');
			});
			/* SHIFT SCHEDULE */
			/* EMPLOYEE SHIFT SCHEDULE */
			Route::prefix('employee-shift-schedule')->group(function() {
				Route::get('/', 'MFile\EmployeeShiftScheduleController@view');
				Route::post('/', 'MFile\EmployeeShiftScheduleController@add');
				Route::post('/update', 'MFile\EmployeeShiftScheduleController@update');
				Route::post('/delete', 'MFile\EmployeeShiftScheduleController@delete');
			});
			/* EMPLOYEE SHIFT SCHEDULE */
			/* HOLIDAYS */
			Route::prefix('holidays')->group(function(){
				Route::get('/', 'MFile\HolidaysController@view');
				Route::post('/', 'MFile\HolidaysController@add');
				Route::post('/update', 'MFile\HolidaysController@update');
				Route::post('/delete', 'MFile\HolidaysController@delete');
			});
			/* HOLIDAYS */
			/* PAYROLL PERIOD */
			Route::prefix('payroll-period')->group(function(){
				Route::get('/', 'MFile\PayrollPeriodController@view');
				Route::post('/', 'MFile\PayrollPeriodController@add');
				Route::get('/getOne', 'MFile\PayrollPeriodController@getOne');
				Route::post('/update', 'MFile\PayrollPeriodController@update');
				Route::post('/delete', 'MFile\PayrollPeriodController@delete');
			});
			/* PAYROLL PERIOD */
			/* WITHOLDING TAX */
			Route::prefix('witholding-tax')->group(function(){
				Route::get('/', 'MFile\WtaxController@view');
				Route::post('/', 'MFile\WtaxController@add');
				Route::get('/getOne', 'MFile\WtaxController@getOne');
				Route::post('/update', 'MFile\WtaxController@update');
				Route::post('/delete', 'MFile\WtaxController@delete');
			});
			/* WITHOLDING TAX */
			/* SSS */
			Route::prefix('sss')->group(function(){
				Route::get('/', 'MFile\SSSController@view');
				Route::post('/', 'MFile\SSSController@add');
				Route::get('/getOne', 'MFile\SSSController@getOne');
				Route::post('/update', 'MFile\SSSController@update');
				Route::post('/delete', 'MFile\SSSController@delete');
			});
			/* SSS */
			/* PHILHEALTH */
			Route::prefix('philhealth')->group(function(){
				Route::get('', 'MFile\PhilhealthController@view');
				Route::post('', 'MFile\PhilhealthController@add');
				Route::get('getOne', 'MFile\PhilhealthController@getOne');
				Route::post('update', 'MFile\PhilhealthController@update');
				Route::post('delete', 'MFile\PhilhealthController@delete');
			});
			/* PHILHEALTH */
			/* HDMF */
			Route::prefix('hdmf')->group(function(){
				Route::get('', 'MFile\HDMFController@view');
				Route::post('', 'MFile\HDMFController@add');
				Route::get('getOne', 'MFile\HDMFController@getOne');
				Route::post('update', 'MFile\HDMFController@update');
				Route::post('delete', 'MFile\HDMFController@delete');
			});
			/* HDMF */
			/* LOAN TYPE */
			Route::prefix('loan-type')->group(function(){
				Route::get('', 'MFile\LoanTypeController@view');
				Route::post('', 'MFile\LoanTypeController@add');
				Route::post('update', 'MFile\LoanTypeController@update');
				Route::post('delete', 'MFile\LoanTypeController@delete');
			});
			/* LOAN TYPE */
			/* OTHER EARNINGS */
			Route::prefix('other-earnings')->group(function(){ //OtherEarningsController
				Route::get('', 'MFile\OtherEarningsController@view');
				Route::post('', 'MFile\OtherEarningsController@add');
				Route::post('update', 'MFile\OtherEarningsController@update');
				Route::post('delete', 'MFile\OtherEarningsController@delete');
			});
			/* OTHER EARNINGS */
			/* OTHER DEDUCTIONS */
			Route::prefix('other-deductions')->group(function(){ //OtherDeductionsController
				Route::get('', 'MFile\OtherDeductionsController@view');
				Route::post('', 'MFile\OtherDeductionsController@add');
				Route::post('update', 'MFile\OtherDeductionsController@update');
				Route::post('delete', 'MFile\OtherDeductionsController@delete');
			});
			/* OTHER DEDUCTIONS */
			/* LEAVE TYPES */
			Route::prefix('leave-types')->group(function(){ // LeaveTypeController
				Route::get('', 'MFile\LeaveTypeController@view');
				Route::post('', 'MFile\LeaveTypeController@add');
				Route::post('update', 'MFile\LeaveTypeController@update');
				Route::post('delete', 'MFile\LeaveTypeController@delete');
			});
			/* LEAVE TYPES */
			/* BUSINESS UNITS */
			Route::prefix('business-units')->group(function(){
				Route::get('', 'MFile\BusinessUnitsController@view');
				Route::post('', 'MFile\BusinessUnitsController@add');
				Route::get('getOne', 'MFile\BusinessUnitsController@getOne');
				Route::post('update', 'MFile\BusinessUnitsController@update');
				Route::post('delete', 'MFile\BusinessUnitsController@delete');
			});
			/* BUSINESS UNITS */

			/* CONTRIBUTION REMITTANCE */
			Route::prefix('contribution-remitance')->group(function(){
				Route::get('', 'MFile\ContributionRemittanceController@view');
				Route::get('getOne', 'MFile\ContributionRemittanceController@getOne');
				Route::post('', 'MFile\ContributionRemittanceController@add');
				Route::post('update', 'MFile\ContributionRemittanceController@update');
				Route::post('delete', 'MFile\ContributionRemittanceController@delete');
			});
			/* CONTRIBUTION REMITTANCE */
		});

		/* MASTER FILE */

		/* CALENDAR */
		Route::prefix('calendar')->group(function() {
			/* MAIN */
			Route::get('/', 'Calendar\CalendarMainController@view');
			Route::get('/get', 'Calendar\CalendarMainController@get_deleted');
			Route::post('/', 'Calendar\CalendarMainController@add');
			Route::post('/update', 'Calendar\CalendarMainController@update');
			Route::post('/delete', 'Calendar\CalendarMainController@delete');
			Route::get('/deleteA/{id}', 'Calendar\CalendarMainController@deleteA');
			Route::get('/restore/{id}', 'Calendar\CalendarMainController@restore');
			/* MAIN */
		});
		/* CALENDAR */

		/* TIMEKEEPING */
		Route::prefix('timekeeping')->group(function() {
			/* LOG BOX */
			Route::prefix('log-box')->group(function() {
				Route::get('/', 'TimeKeeping\LogBoxController@view');
				Route::post('/in', 'TimeKeeping\LogBoxController@getLastestTimeIn');
				Route::post('/out', 'TimeKeeping\LogBoxController@getLastestTimeOut');
			});
			/* LOG BOX */
			/* UPLOAD DTR */
			Route::prefix('upload-dtr')->group(function() {
				Route::get('/', 'Timekeeping\UploadDTRController@view');
				Route::post('/', 'Timekeeping\UploadDTRController@submit');
				Route::get('/read', 'Timekeeping\UploadDTRController@read2');
			});
			/* UPLOAD DTR */
			/* TIMELOG ENTRY */
			Route::prefix('timelog-entry')->group(function() {
				Route::get('/', 'Timekeeping\TimeLogEntryController@view');
				Route::post('/batch-time-log-info', 'Timekeeping\TimeLogEntryController@loadBatchTimeLogsInfo');
				Route::post('/add-log', 'Timekeeping\TimeLogEntryController@addLog');
				Route::post('/delete-log', 'Timekeeping\TimeLogEntryController@deleteLog');
				Route::post('/delete-all-log', 'Timekeeping\TimeLogEntryController@deleteLog_All');
				Route::post('/get-log', 'Timekeeping\TimeLogEntryController@GetLog');
				Route::post('/edit-log', 'Timekeeping\TimeLogEntryController@EditLog');
			});
			/* TIMELOG ENTRY */
			/* LEAVES ENTRY */
			Route::prefix('leaves-entry')->group(function() {
				Route::get('/', 'Timekeeping\LeavesEntryController@view');
				Route::post('/getType', 'Timekeeping\LeavesEntryController@getType');
				Route::post('/', 'Timekeeping\LeavesEntryController@add');
				Route::post('/update', 'Timekeeping\LeavesEntryController@update');
				Route::post('/delete', 'Timekeeping\LeavesEntryController@delete');
				Route::post('/find', 'Timekeeping\LeavesEntryController@find');
				Route::get('/get-entry', 'Timekeeping\LeavesEntryController@get_entry');
				Route::get('/new-entry-code', function() {
					return DB::table('m99')->select('lvcode')->first()->lvcode;
				});
			}); 
			/* LEAVES ENTRY */
			/* EMPLOYEE DTR */
			Route::prefix('employee-dtr')->group(function() {
				Route::get('/', 'Timekeeping\EmployeeDTRController@view');
				Route::post('/load-dtr', 'Timekeeping\EmployeeDTRController@LoadDTR');
				Route::get('/print-dtr', 'Timekeeping\EmployeeDTRController@PrintDTR');
			});
			/* EMPLOYEE DTR */
			/* GENERATE DTR */
			Route::prefix('generate-dtr')->group(function() {
				Route::get('/', 'Timekeeping\GenerateDTRController@view');
				Route::get('/partial-generation', 'Timekeeping\GenerateDTRController@GenerateDTR');
				Route::post('/generate-dtr', 'Timekeeping\GenerateDTRController@GenerateDTR');
				Route::post('/save-dtr', 'Timekeeping\GenerateDTRController@SaveDTR');
			});
			/* GENERATE DTR */
		});
		/* TIMEKEEPING */

		/* PAYROLL */
		Route::prefix('payroll')->group(function() {
			/* LOAN ENTRY */
			Route::prefix('loan-entry')->group(function() {
				Route::get('/', 'Payroll\Loan\LoanEntryController@view');
				Route::post('/', 'Payroll\Loan\LoanEntryController@add');
				Route::post('/update', 'Payroll\Loan\LoanEntryController@update');
				Route::post('/find', 'Payroll\Loan\LoanEntryController@find');
				Route::get('/get-entry', 'Payroll\Loan\LoanEntryController@get_entry');
				Route::post('/delete', 'Payroll\Loan\LoanEntryController@delete');
			});
			/* LOAN ENTRY */
			/* LOAN HISTORY*/
			Route::prefix('loan-history')->group(function() {
				Route::get('/', 'Payroll\Loan\LoanHistoryController@view');
				Route::post('/find', 'Payroll\Loan\LoanHistoryController@find');
			});
			/* LOAN HISTORY */
			/* OTHER EARNINGS */
			Route::prefix('other-earnings')->group(function() {
				Route::get('/', 'Payroll\OtherEarningsMainController@view')->name('oehome');
				Route::post('/find', 'Payroll\OtherEarningsMainController@find');
				Route::post('/employee', 'Payroll\OtherEarningsMainController@employee');
				Route::post('/generate', 'Payroll\OtherEarningsMainController@generate');
				Route::post('/get-total-deduction', 'Payroll\OtherEarningsMainController@get_total_deduction');
				Route::post('/get-net-amount', 'Payroll\OtherEarningsMainController@get_net_amount');

				// query entries
				Route::post('/monthlyra', 'Payroll\OtherEarningsMainController@monthlyra');
				Route::post('/monthlyta', 'Payroll\OtherEarningsMainController@monthlyta');
				Route::post('/deduc1', 'Payroll\OtherEarningsMainController@deduc1');
				Route::post('/deduc2', 'Payroll\OtherEarningsMainController@deduc2');
				Route::post('/amount-paid', 'Payroll\OtherEarningsMainController@amount_paid');
				Route::post('/absence-w-pay', 'Payroll\OtherEarningsMainController@absence_w_pay');

				// new page
				Route::prefix('print')->group(function() {
					Route::get('/{month}', 'Payroll\OtherEarningsMainController@print_view');
				});
			});
			/* OTHER EARNINGS */
			/* GENERATE PAYROLL */
			Route::prefix('generate-payroll')->group(function() {
				Route::get('/', 'Payroll\GeneratePayrollController@view');
				Route::post('/find-dtr', 'Payroll\GeneratePayrollController@find_dtr');
				Route::post('/generate', 'Payroll\GeneratePayrollController@generate_payroll');
			});
			/* GENERATE PAYROLL */
			/* VIEW GENERATE PAYROLL */
			Route::prefix('view-generated-payroll')->group(function() {
				Route::get('/', 'Payroll\ViewGeneratedPayrollController@view');
				Route::post('/info', 'Payroll\ViewGeneratedPayrollController@info');
			});
			/* VIEW GENERATE PAYROLL */
			/* PAYROLL REGISTER */
			Route::prefix('payroll-register')->group(function(){
				Route::get('', 'Payroll\PayrollRegisterController@view');
				Route::post('', 'Payroll\PayrollRegisterController@add');
				Route::get('getOne', 'Payroll\PayrollRegisterController@getOne');
				Route::post('update', 'Payroll\PayrollRegisterController@update');
				Route::post('delete', 'Payroll\PayrollRegisterController@delete');
			});
			/* PAYROLL REGISTER */
			/* PAYROLL PERIOD */
			Route::prefix('payroll-period')->group(function() {
				Route::post('/get-dates', 'MFile\PayrollPeriodController@getdates');
			});
			/* PAYROLL PERIOD */
		});
		/* PAYROLL */

		/* REPORTS */
		Route::prefix('reports')->group(function() {
			Route::prefix('timekeeping')->group(function() {
				Route::prefix('EmployeeDTR')->group(function() {
					Route::get('/', 'Reports\PrintEmployeeDTRController@view');
					Route::post('/find', 'Reports\PrintEmployeeDTRController@find');
				});
				Route::prefix('EmployeeDTRSummary')->group(function() {
					Route::get('/', 'Reports\PrintEmployeeDTRSummaryController@view');
					Route::post('/find', 'Reports\PrintEmployeeDTRSummaryController@find');
				});
				Route::prefix('DailyTimelogRecord')->group(function() {
					Route::get('/', 'Reports\PrintDailyTimelogRecordController@view');
					Route::post('/find', 'Reports\PrintDailyTimelogRecordController@find');
					Route::get('/print', 'Reports\PrintDailyTimelogRecordController@print');
				});
			});
			// Route::prefix('payroll')->group(function() {
				Route::prefix('payroll-summary-report')->group(function() {
					Route::get('/', 'Reports\Payroll\PayrollSummaryReportController@view');
					Route::get('/export', 'Reports\Payroll\PayrollSummaryReportController@export');
					Route::get('/print', 'Reports\Payroll\PayrollSummaryReportController@print');
				});
			// });
		});
		/* REPORTS */

		/* RECORDS */
		Route::prefix('records')->group(function() {
			/* SERVICE RECORD */
			Route::prefix('service-record')->group(function(){
				Route::get('/', 'Records\ServiceRecordController@view');
				Route::post('/', 'Records\ServiceRecordController@add_remark');
			});
			/* SERVICE RECORD */
		});
		/* RECORDS */

		/* SETTINGS */
			// Route::get('/maintenance-mode/{var}', 'WebsiteController@MaintenanceMode')->name('website.maintenancemode');
		Route::prefix('settings')->group(function() {
			/* SYSTEM DATA UPDATE */
			Route::prefix('payrollsettings')->group(function() {
				Route::get('', 'Settings\PayrollSettingsController@view');
			});
			/* SYSTEM DATA UPDATE */
			/* GROUP RIGHTS SETTINGS */
			Route::prefix('group-rights')->group(function() {
				Route::get('', 'Settings\GroupRightsSettingsController@viewUserGroup');
				Route::get('info', 'Settings\GroupRightsSettingsController@LoadLevel2');
				Route::post('add-rights', 'Settings\GroupRightsSettingsController@AddRights');
			});
			/* GROUP RIGHTS SETTINGS */
			/* USER SETTINGS */
			Route::prefix('user')->group(function() {
				Route::get('', 'Settings\UserSettingsController@view');
				Route::post('', 'Settings\UserSettingsController@add');
				Route::post('/update', 'Settings\UserSettingsController@update');
				Route::post('/delete', 'Settings\UserSettingsController@delete');
			});
			/* USER SETTINGS */
			/* NOTIFICATION SETTINGS */
			Route::prefix('notification')->group(function() {
				Route::get('', 'Settings\NotificationSettingsController@view');
			});
			/* NOTIFICATION SETTINGS */
			/* SYSTEM SETTINGS */
			Route::prefix('system')->group(function() {
				Route::get('', 'Settings\SystemSettingsController@view');
				Route::post('', 'Settings\SystemSettingsController@update');
				Route::post('/add', 'Settings\SystemSettingsController@add');
			});
			/* SYSTEM SETTINGS */
			/* SYSTEM DATA UPDATE */
			Route::prefix('system-data-update')->group(function() {
				Route::get('', 'Settings\SystemDataUpdateController@view');
			});
			/* SYSTEM DATA UPDATE */
			/* SYSTEM DATA UPDATE */
			Route::prefix('error-log')->group(function() {
				Route::get('', function() {
					$errorlogs = Core::READ_FILE('errorlog.txt', 'txt', $folder = 'logs/system');
					unset($errorlogs[0]); /*dd($errorlogs);*/
					$logs = [];

					if (count($errorlogs)!=0) {
						foreach($errorlogs as $log) {
							$a = explode(" | ", $log);
							$n = [];
							$n['date'] = $a[0];
							$n['module'] = $a[1];
							$n['msg'] = $a[2];
							array_push($logs, $n);
						}
					} /*dd($logs);*/
					
					return view('pages.settings.error-log', compact('logs'));
				});
			});
		});
		/* SETTINGS */

		/* NOTIFICATION */
		Route::prefix('notification')->group(function() {
			Route::get('/', 'Notification\NotificationController@view');
			Route::post('/send', 'Notification\NotificationController@send');
			Route::post('/find', 'Notification\NotificationController@find');
		});
		/* NOTIFICATION */
	});
/* AUTHENTICATED ROUTES */

/* OTHERS */
	/* CHECK DATABASE CONNECTION */
	Route::get('/check-connection', function () {
		try {
			// dd(Carbon\Carbon::now()->format('h:i'));
		    DB::connection()->getPdo();
		    return "Connected";
		} catch (\Exception $e) {
		    die("Could not connect to the database.  Please check your configuration. error:" . $e );
		}
	});
	/* VIEW SESSIONS */
	Route::get('/sessions', function () { dd(Session::all()); });
	Route::get('/sessions/check', function() {
		if (Session::exists('_user')) {
			return "ok"; 
		} else {
			return "no user"; 
		}
	});
	Route::get('/restricted', 'WebsiteController@Restricted');
	Route::get('/test/', function () {
		return Excel::download(new TestExport(), 'general-payroll-'.date('ymdhis').'.xlsx');
		// return url("storage/app/public/profile_images");
	});

	/* REDIRECT | Important Note: The following routes MUST ALWAYS BE AT THE BOTTOM OF THIS FILE. */
	Route::get('/error/{page}', 'MyRedirectController@redirect')->name('redirect'); // Interface needs to be fixed
/* OTHERS */