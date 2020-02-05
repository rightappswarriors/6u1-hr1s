<?php

namespace App\Http\Controllers\Records;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use ServiceRecord;
use Employee;
use ErrorCode;
use Office;
use JobTitle;

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

    /**
    * view
    * @param
    *
    * @return view
    */
    public function view()
    {
        $data = $this->ghistory;
        // Add the name of the employee
        foreach($data as $key => $value) {
            // dd(Employee::GetDepartment($value->branch));
            $employee = Employee::GetEmployee($value->empid);
           
            if(isset($employee)){
                $data[$key]->employee_name = $employee->firstname.' '.$employee->mi.(($employee->mi == null)?'':' ').$employee->lastname;
            }
        }

        $data = [$data, Office::get_all()];

        return view('pages.records.service_record', compact('data'));
    }

    /**
    * add remarks ajax 
    * @param Request
    *
    * @return
    */
    public function add_remark(Request $r)
    {
        // dd($r->all());
        try {
            $data = ['remarks'=>$r->remarks];

            $d = DB::table(ServiceRecord::$tbl_name)->where(ServiceRecord::$pk, '=', $r->sr_code)->update($data);
            // Core::Set_Alert('success', 'Successfully added new Loan Entry.');

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'ServiceRecordController', '00000', $e->getMessage());
            // return back();
        }
    }

    /**
    * find record ajax
    * @param Request
    *
    * @return array | null
    */
    public function find(Request $r)
    {
        $data = $this->ghistory;
        $final_data = array();
        
        foreach($data as $k => $v) {
            if(Employee::IfEmployeeInOffice($v->empid, $r->ofc_id)) {
                $employee = Employee::GetEmployee($v->empid);
                $data[$k]->employee_name = $employee->firstname.' '.$employee->mi.(($employee->mi == null)?'':' ').$employee->lastname;
                $data[$k]->date_from_readable = \Carbon\Carbon::parse($data[$k]->service_from)->format('M d, Y');
                $data[$k]->date_to_readable = \Carbon\Carbon::parse($data[$k]->service_to)->format('M d, Y');
                $data[$k]->designation_readable = JobTitle::Get_JobTitle(trim($data[$k]->designation));
                $data[$k]->branch_readable = Employee::GetDepartment($data[$k]->branch);
                $data[$k]->lwp_readable = trim($data[$k]->leave_wo_pay);
                $data[$k]->remarks_readable = trim($data[$k]->remarks);
                $final_data[] = $data[$k];
            }
        }
        return $final_data;
    }
}