<?php
	/*
    |--------------------------------------------------------------------------
    | Read Me
    |--------------------------------------------------------------------------
    |
    | This file returns specific shortcode for your error type
    | that will be used for the error code.
    | 
    | This file is displayed like this:
    | controller_type => [
    |   'class_name' => 'shortcode'
    | ]
    |
    */
return [

    'controller' => [
        
        'AuthController' => 'AC',
        'BusinessUnitsController' => 'BUC',
        'CalendarMainController' => 'CMC',
        'ContributionRemittanceController' => 'CRC',
        'DepartmentController' => 'DC',
        'DepartmentSectionController' => 'DSC',
        'EmployeeController' => 'EC',
        'EmployeeDTRController' => 'EDTRC',
        'EmployeeShiftScheduleController' => 'ESSC',
        'EmployeeStatusController' => 'ESC',
        'GenerateDTRController' => 'GDTRC',
        'GeneratePayrollController' => 'GPC',
        'GroupRightsSettingsController' => 'GRSC',
        'HDMFController' => 'HDMFC',
        'HolidaysController' => 'HolC',
        'HomeController' => 'HomC',
        'JobTitleController' => 'JTC',
        'LeavesEntryController' => 'LeEC',
        'LeaveTypeController' => 'LTC',
        'LoanEntryController' => 'LoEC',
        'LoanHistoryController' => 'LHC',
        'NotificationSettingsController' => 'NSC',
        'OfficeController' => 'OC',
        'OtherDeductionsController' => 'ODC',
        'OtherEarningsController' => 'OEC',
        'OtherEarningsMainController' => 'OEMC',
        'PayrollPeriodController' => 'PPC',
        'PayrollRegisterController' => 'PRC',
        'PhilhealthController' => 'PC',
        'PrintDailyTimelogRecordController' => 'PDTRC',
        'PrintEmployeeDTRContrller' => 'PEDC',
        'PrintEmployeeDTRSummaryController' => 'PEDSC',
        'ShiftScheduleController' => 'SSC',
        'SSSController' => 'SSSC',
        'WtaxController' => 'WC',
        'TimeLogEntryController' => 'TLEC',
        'TiToController' => 'TTC',
        'ServiceRecordController' => 'SRC',
        'SystemDataUpdateController' => 'SDUC',
        'SystemSettingsController' => 'SSC',
        'UploadDTRController' => 'UDTRC',
        'UserSettingsController' => 'USC',
        'ViewGeneratedPayrollController' => 'VGC',

        // Not used
        'ForgotPasswordController' => 'FPC',
        'LoginController' => 'LC',
        'RegisterController' => 'RG',
        'ResetPasswordController' => 'RPC',
    ],

    'model' => [
        'Core' => 'C',
        'DTR' => 'D',
        'EmployeeLeaveCount' => 'ELC',
        'Leave' => 'L',
        'LeaveType' => 'LT',
    ],

    'middleware' => [
        'SyncSystemSettings' => 'SSS',
    ]
];