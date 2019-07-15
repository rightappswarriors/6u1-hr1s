<?php

namespace App\Http\Controllers\Records;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use ServiceRecord;
use Employee;
use ErrorCode;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - SRC
|
| 00000 - add_remark
| 
|--------------------------------------------------------------------------
*/

class ServiceRecordController extends Controller
{

    public function __construct()
    {
        $this->ghistory = ServiceRecord::Load_ServiceRecords();
    }

    public function view()
    {
        $data = $this->ghistory;
        
        // Add the name of the employee
        foreach($data as $key => $value) {
            // dd(Employee::GetDepartment($value->branch));
            $employee = Employee::GetEmployee($value->empid);
            $data[$key]->employee_name = $employee->firstname.' '.$employee->mi.(($employee->mi == null)?'':' ').$employee->lastname;
        }

        return view('pages.records.service_record', compact('data'));
    }

    public function add_remark(Request $r)
    {
        // dd($r->all());
        try {
            $data = ['remarks'=>$r->remarks];

            DB::table(ServiceRecord::$tbl_name)->where(ServiceRecord::$pk, '=', $r->sr_code)->update($data);
            // Core::Set_Alert('success', 'Successfully added new Loan Entry.');

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'ServiceRecordController', '00000', $e->getMessage());
            // return back();
        }
    }
}