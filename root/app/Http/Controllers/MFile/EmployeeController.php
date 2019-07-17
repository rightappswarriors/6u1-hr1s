<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Employee;
use ServiceRecord;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $SQLOffice = "SELECT oid,* FROM rssys.m08 WHERE active = TRUE ORDER BY cc_desc ASC";
        $this->office= DB::select($SQLOffice);
        $SQLDept = "SELECT * FROM hris.hr_department WHERE COALESCE(cancel, cancel, '')<>'Y' ORDER BY dept_name ASC";
        $this->dept = DB::select($SQLDept);
        $SQLPosition = "SELECT * FROM hris.hr_jobtitle WHERE COALESCE(cancel, cancel, '')<>'Y' ORDER BY jtitle_name ASC";
        $this->position = DB::select($SQLPosition);
        $SQLEmpStatus = "SELECT oid,* FROM hris.hr_emp_status WHERE COALESCE(cancel, cancel, '')<>'Y'";
        $this->emp_status = DB::select($SQLEmpStatus);
        $SQLRate = "SELECT * FROM hris.hr_rate_type";
        $this->rate_type = DB::select($SQLRate);
        $SQLTax = "SELECT * FROM hris.hr_wtax WHERE COALESCE(cancel, cancel, '')<>'Y'";
        $this->tax= DB::select($SQLTax);
        $SQLSSS = "SELECT code,s_credit FROM hris.hr_sss ORDER BY s_credit";
        $this->sss= DB::select($SQLSSS);
        $SQLDays = "SELECT * FROM hris.hr_days";
        $this->days= DB::select($SQLDays);
        $SQLCivilStatus = "SELECT * FROM hris.hr_civil_status";
        $this->civil_stat = DB::select($SQLCivilStatus);
        $SQLEmployee = "SELECT empid,lastname,firstname,mi,positions,department,section,date_hired,contractual_date,date_resigned,date_terminated,prohibition_date,date_regular,empstatus,contract_days,prc,ctc,rate_type,pay_rate,biometric,sss,pagibig,philhealth,payroll_account,tin,tax_bracket,shift_sched_from,dayoff1,dayoff2,sex,birth,civil_status,religion,height,weight,father,father_address,father_contact,father_job,mother,mother_address,mother_contact,mother_job,emp_contact,home_tel,email,home_address,emergency_name,emergency_contact,em_home_address,relationship,shift_sched_sat_from,shift_sched_to,shift_sched_sat_to,fixed_rate,primary_ed,secondary_ed,tertiary_ed,graduate,post_graduate, sss_bracket,fixed_sched FROM hris.hr_employee WHERE COALESCE(cancel, cancel, '')<>'Y'";
        $this->employee = DB::select($SQLEmployee);
    }
    public function view()
    {
        // return dd($this->employee);
        return view('pages.mfile.employee', ['dept' => $this->dept, 'position' => $this->position, 'emp_status' => $this->emp_status, 'tax' => $this->tax, 'rate' => $this->rate_type, 'sss' => $this->sss, 'day' => $this->days, 'civil_stat' => $this->civil_stat, 'employee' => $this->employee] );
    }
    public function new()
    {
        // return dd($this->office);
        return view('pages.mfile.employee_new', ['dept' => $this->dept, 'position' => $this->position, 'emp_status' => $this->emp_status, 'tax' => $this->tax, 'rate' => $this->rate_type, 'sss' => $this->sss, 'day' => $this->days, 'civil_stat' => $this->civil_stat, 'employee' => $this->employee, 'office' => $this->office] );
    }
    public function new2()
    {
        return view('pages.mfile.employee_crud', ['mode' => 'new', 'url'=>url('master-file/employee/add2'), 'office' => $this->office, 'position' => $this->position, 'emp_status' => $this->emp_status, 'rate' => $this->rate_type, 'tax' => $this->tax, 'civil_stat' => $this->civil_stat, 'MYDATA' => null]);
    }
    public function add2(Request $r)
    {
        // return dd($r->all());
        $data = [
            'empid' => strtoupper($r->txt_id),
            'lastname' => strtoupper($r->txt_lname),
            'firstname' => strtoupper($r->txt_fname),
            'mi' => ($r->txt_mname != '') ? strtoupper($r->txt_mname).'.' : null,
            'department' => $r->txt_dept,
            // 'section' => $r->txt_deptsec,
            'positions' => $r->txt_jobdesc,
            'date_hired' => $r->txt_hired,
            'contractual_date' => (isset($r->txt_con_chk)) ? $r->txt_con_dt : null,
            'date_resigned' => (isset($r->txt_resign_chk)) ? $r->txt_resign_dt : null,
            'date_terminated' => (isset($r->txt_termi_chk)) ? $r->txt_termi_dt : null,
            'prohibition_date' => (isset($r->txt_prob_chk)) ? $r->txt_prob_dt : null,
            'date_regular' => (isset($r->txt_reg_chk)) ? $r->txt_reg_dt : null,
            'empstatus' => $r->txt_emp_stat,
            'contract_days' => ($r->txt_contract != '') ? (int)$r->txt_contract : 0,
            'prc' => ($r->txt_prc != '') ? $r->txt_prc : '',
            'ctc' => ($r->txt_ctc != '') ? $r->txt_ctc : '',
            'rate_type' => $r->txt_rate_typ,
            // 'pay_rate' => floatval($r->txt_py_rate),
            'pay_rate' => Core::ToFloat($r->txt_py_rate),
            'biometric' => ($r->txt_biometric != '') ? $r->txt_biometric : '',
            'sss' => ($r->txt_sss != '') ? $r->txt_sss : '',
            'pagibig' => ($r->txt_pagibig != '') ? $r->txt_pagibig : '',
            'philhealth' => ($r->txt_philhealth != '') ? $r->txt_philhealth : '',
            'payroll_account' => ($r->txt_payrol != '') ? $r->txt_payrol : '',
            'tin' => ($r->txt_tin != '') ? $r->txt_tin : '',
            'tax_bracket' => $r->txt_tax_brac,
            // 'shift_sched_from' => $r->txt_sft_1,
            // 'dayoff1' => $r->txt_day_off_1,
            // 'dayoff2' => $r->txt_day_off_2,
            'sex' => $r->txt_gen,
            'birth' => $r->txt_dt_birth,
            'civil_status' => $r->txt_civ_stat,
            'religion' => ($r->txt_reli != '') ? $r->txt_reli : '',
            // 'height' => ($r->txt_height != '') ? floatval($r->txt_height) : floatval(0),
            'height' => ($r->txt_height != '') ? Core::ToFloat($r->txt_height) : floatval(0),
            // 'weight' => ($r->txt_weight != '') ? floatval($r->txt_weight) : floatval(0),
            'weight' => ($r->txt_weight != '') ? Core::ToFloat($r->txt_weight) : floatval(0),
            'father' => ($r->txt_fath_name != '') ? $r->txt_fath_name : '',
            'father_address' => ($r->txt_fath_add != '') ? $r->txt_fath_add : '',
            'father_contact' => ($r->txt_fath_contact != '') ? $r->txt_fath_contact : '',
            'father_job' => ($r->txt_fath_occu != '') ? $r->txt_fath_occu : '',
            'mother'=> ($r->txt_moth_name != '') ? $r->txt_moth_name : '',
            'mother_address' => ($r->txt_moth_add != '') ? $r->txt_moth_add : '',
            'mother_contact' => ($r->txt_moth_contact != '') ? $r->txt_moth_contact : '',
            'mother_job' => ($r->txt_moth_occu != '') ? $r->txt_moth_occu : '',
            'emp_contact' => ($r->txt_contact_num != '') ? $r->txt_contact_num : '',
            'home_tel' => ($r->txt_home_tel != '') ? $r->txt_home_tel : '',
            'email' => ($r->txt_email != '') ? $r->txt_email : '',
            'home_address' => ($r->txt_home_add != '') ? $r->txt_home_add : '',
            'emergency_name' => ($r->txt_emerg_name != '') ? $r->txt_emerg_name : '',
            'emergency_contact' => ($r->txt_emerg_cont != '') ? $r->txt_emerg_cont : '',
            'em_home_address' => ($r->txt_emerg_add != '') ? $r->txt_emerg_add : '',
            'relationship' => ($r->txt_emerg_rel != '') ? $r->txt_emerg_rel : '',
            // 'shift_sched_sat_from' => $r->txt_sat_sft_1,
            // 'shift_sched_to' => $r->txt_sft_2,
            // 'shift_sched_sat_to' => $r->txt_sat_sft_2,
            'fixed_rate' => ($r->txt_fx_rate) ? 1 : '',
            'primary_ed' => ($r->txt_edu_pri != '') ? $r->txt_edu_pri : '',
            'secondary_ed' => ($r->txt_edu_sec != '') ? $r->txt_edu_sec : '',
            'tertiary_ed' => ($r->txt_edu_ter != '') ? $r->txt_edu_ter : '',
            'graduate' => ($r->txt_edu_grad != '') ? $r->txt_edu_grad : '',
            'post_graduate' => $r->txt_edu_post_grad,
            // 'sss_bracket' => $r->txt_ss_brac,
            'fixed_sched' => ($r->txt_fx_sched == 'Yes') ? "Y": "N",
        ];
        $status = 'JO';
        $service_record_data = [
            'sr_code'=>Core::get_nextincrementlimitchar(Core::getm99One('sr_code')->sr_code, 8),
            'empid'=>$data['empid'], 
            'service_from'=>$data['date_hired'], 
            'designation'=>$data['positions'], 
            'status'=>$status,
            'salary'=>($data['rate_type'] == 'D')?$data['pay_rate']*365:$data['pay_rate']*12,  
            'branch'=>$data['department'], 
            'leave_wo_pay'=>'', 
            'remarks'=>'', 
        ];
        try {

            if(DB::table(Employee::$tbl_name)->insert($data)){
                Core::Set_Alert('success', 'Successfully added new Employee.');

                // Lloyd - Service Record
                DB::table(ServiceRecord::$tbl_name)->insert($service_record_data);
                Core::updatem99('sr_code', $service_record_data['sr_code']);

                // Core::updatem99('empid', Core::get_nextincrementlimitchar($empid->empid, 8));
                // return redirect('master-file/employee');
            }
            return redirect('master-file/employee');

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
    public function add(Request $r)
    {
        // return dd($r->all());
        // $empid = Core::getm99One('empid');
        // return dd($empid->empid);
        $data = [
                'empid' => strtoupper($r->txt_id),
                'lastname' => strtoupper($r->txt_lname),
                'firstname' => strtoupper($r->txt_fname),
                'mi' => ($r->txt_mname != '') ? strtoupper($r->txt_mname).'.' : null,
                'department' => $r->txt_dept,
                'section' => $r->txt_deptsec,
                'positions' => $r->txt_jobdesc,
                'date_hired' => $r->txt_hired,
                'contractual_date' => (isset($r->txt_con_chk)) ? $r->txt_con_dt : null,
                'date_resigned' => (isset($r->txt_resign_chk)) ? $r->txt_resign_dt : null,
                'date_terminated' => (isset($r->txt_termi_chk)) ? $r->txt_termi_dt : null,
                'prohibition_date' => (isset($r->txt_prob_chk)) ? $r->txt_prob_dt : null,
                'date_regular' => (isset($r->txt_reg_chk)) ? $r->txt_reg_dt : null,
                'empstatus' => $r->txt_emp_stat,
                'contract_days' => ($r->txt_contract != '') ? (int)$r->txt_contract : 0,
                'prc' => ($r->txt_prc != '') ? $r->txt_prc : '',
                'ctc' => ($r->txt_ctc != '') ? $r->txt_ctc : '',
                'rate_type' => $r->txt_rate_typ,
                // 'pay_rate' => floatval($r->txt_py_rate),
                'pay_rate' => Core::ToFloat($r->txt_py_rate),
                'biometric' => ($r->txt_biometric != '') ? $r->txt_biometric : '',
                'sss' => ($r->txt_sss != '') ? $r->txt_sss : '',
                'pagibig' => ($r->txt_pagibig != '') ? $r->txt_pagibig : '',
                'philhealth' => ($r->txt_philhealth != '') ? $r->txt_philhealth : '',
                'payroll_account' => ($r->txt_payrol != '') ? $r->txt_payrol : '',
                'tin' => ($r->txt_tin != '') ? $r->txt_tin : '',
                'tax_bracket' => $r->txt_tax_brac,
                'shift_sched_from' => $r->txt_sft_1,
                'dayoff1' => $r->txt_day_off_1,
                'dayoff2' => $r->txt_day_off_2,
                'sex' => $r->txt_gen,
                'birth' => $r->txt_dt_birth,
                'civil_status' => $r->txt_civ_stat,
                'religion' => ($r->txt_reli != '') ? $r->txt_reli : '',
                // 'height' => ($r->txt_height != '') ? floatval($r->txt_height) : floatval(0),
                'height' => ($r->txt_height != '') ? Core::ToFloat($r->txt_height) : floatval(0),
                // 'weight' => ($r->txt_weight != '') ? floatval($r->txt_weight) : floatval(0),
                'weight' => ($r->txt_weight != '') ? Core::ToFloat($r->txt_weight) : floatval(0),
                'father' => ($r->txt_fath_name != '') ? $r->txt_fath_name : '',
                'father_address' => ($r->txt_fath_add != '') ? $r->txt_fath_add : '',
                'father_contact' => ($r->txt_fath_contact != '') ? $r->txt_fath_contact : '',
                'father_job' => ($r->txt_fath_occu != '') ? $r->txt_fath_occu : '',
                'mother'=> ($r->txt_moth_name != '') ? $r->txt_moth_name : '',
                'mother_address' => ($r->txt_moth_add != '') ? $r->txt_moth_add : '',
                'mother_contact' => ($r->txt_moth_contact != '') ? $r->txt_moth_contact : '',
                'mother_job' => ($r->txt_moth_occu != '') ? $r->txt_moth_occu : '',
                'emp_contact' => ($r->txt_contact_num != '') ? $r->txt_contact_num : '',
                'home_tel' => ($r->txt_home_tel != '') ? $r->txt_home_tel : '',
                'email' => ($r->txt_email != '') ? $r->txt_email : '',
                'home_address' => ($r->txt_home_add != '') ? $r->txt_home_add : '',
                'emergency_name' => ($r->txt_emerg_name != '') ? $r->txt_emerg_name : '',
                'emergency_contact' => ($r->txt_emerg_cont != '') ? $r->txt_emerg_cont : '',
                'em_home_address' => ($r->txt_emerg_add != '') ? $r->txt_emerg_add : '',
                'relationship' => ($r->txt_emerg_rel != '') ? $r->txt_emerg_rel : '',
                'shift_sched_sat_from' => $r->txt_sat_sft_1,
                'shift_sched_to' => $r->txt_sft_2,
                'shift_sched_sat_to' => $r->txt_sat_sft_2,
                'fixed_rate' => ($r->txt_fx_rate) ? 1 : '',
                'primary_ed' => ($r->txt_edu_pri != '') ? $r->txt_edu_pri : '',
                'secondary_ed' => ($r->txt_edu_sec != '') ? $r->txt_edu_sec : '',
                'tertiary_ed' => ($r->txt_edu_ter != '') ? $r->txt_edu_ter : '',
                'graduate' => ($r->txt_edu_grad != '') ? $r->txt_edu_grad : '',
                'post_graduate' => $r->txt_edu_post_grad,
                'sss_bracket' => $r->txt_ss_brac,
                'fixed_sched' => ($r->txt_fx_sched == 'Yes') ? "Y": "N",
            ];
        // return dd($data);

        $status = 'JO';
        $service_record_data = [
                'sr_code'=>Core::get_nextincrementlimitchar(Core::getm99One('sr_code')->sr_code, 8),
                'empid'=>$data['empid'], 
                'service_from'=>$data['date_hired'], 
                'designation'=>$data['positions'], 
                'status'=>$status,
                'salary'=>($data['rate_type'] == 'D')?$data['pay_rate']*365:$data['pay_rate']*12,  
                'branch'=>$data['department'], 
                'leave_wo_pay'=>'', 
                'remarks'=>'', 
            ];

        try {

            if(DB::table(Employee::$tbl_name)->insert($data)){
                Core::Set_Alert('success', 'Successfully added new Employee.');

                // Lloyd - Service Record
                DB::table(ServiceRecord::$tbl_name)->insert($service_record_data);
                Core::updatem99('sr_code', $service_record_data['sr_code']);

                // Core::updatem99('empid', Core::get_nextincrementlimitchar($empid->empid, 8));
                return redirect('master-file/employee');
            }

            //

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
    public function getOneEmployee(Request $r)
    {
        // return $r->all();
        return json_encode(Employee::GetEmployee($r->id));
    }
    public function edit($id)
    {
        // return dd($id);
        $Employee = Employee::GetEmployee($id);
        // return dd($Employee);
        // return view('pages.mfile.employee_new_update', ['dept' => $this->dept, 'position' => $this->position, 'emp_status' => $this->emp_status, 'tax' => $this->tax, 'rate' => $this->rate_type, 'sss' => $this->sss, 'day' => $this->days, 'civil_stat' => $this->civil_stat, 'employee' => $this->employee, 'office' => $this->office, 'MYDATA' => $Employee] );
        return view('pages.mfile.employee_crud', ['mode' => 'edit', 'url' => url('master-file/employee/update'), 'office' => $this->office, 'position' => $this->position, 'emp_status' => $this->emp_status, 'rate' => $this->rate_type, 'tax' => $this->tax, 'civil_stat' => $this->civil_stat, 'MYDATA' => $Employee] );
    }
    public function update(Request $r)
    {
        $old_data = Employee::GetEmployee($r->txt_id);
        // dd($old_data);
        $data = [
            'empid' => strtoupper($r->txt_id),
            'lastname' => strtoupper($r->txt_lname),
            'firstname' => strtoupper($r->txt_fname),
            'mi' => ($r->txt_mname != '') ? strtoupper($r->txt_mname).'.' : null,
            'department' => $r->txt_dept,
            // 'section' => $r->txt_deptsec,
            'positions' => $r->txt_jobdesc,
            'date_hired' => $r->txt_hired,
            'contractual_date' => (isset($r->txt_con_chk)) ? $r->txt_con_dt : null,
            'date_resigned' => (isset($r->txt_resign_chk)) ? $r->txt_resign_dt : null,
            'date_terminated' => (isset($r->txt_termi_chk)) ? $r->txt_termi_dt : null,
            'prohibition_date' => (isset($r->txt_prob_chk)) ? $r->txt_prob_dt : null,
            'date_regular' => (isset($r->txt_reg_chk)) ? $r->txt_reg_dt : null,
            'empstatus' => $r->txt_emp_stat,
            'contract_days' => ($r->txt_contract != '') ? (int)$r->txt_contract : 0,
            'prc' => ($r->txt_prc != '') ? $r->txt_prc : '',
            'ctc' => ($r->txt_ctc != '') ? $r->txt_ctc : '',
            'rate_type' => $r->txt_rate_typ,
            // 'pay_rate' => floatval($r->txt_py_rate),
            'pay_rate' => Core::ToFloat($r->txt_py_rate),
            'biometric' => ($r->txt_biometric != '') ? $r->txt_biometric : '',
            'sss' => ($r->txt_sss != '') ? $r->txt_sss : '',
            'pagibig' => ($r->txt_pagibig != '') ? $r->txt_pagibig : '',
            'philhealth' => ($r->txt_philhealth != '') ? $r->txt_philhealth : '',
            'payroll_account' => ($r->txt_payrol != '') ? $r->txt_payrol : '',
            'tin' => ($r->txt_tin != '') ? $r->txt_tin : '',
            'tax_bracket' => $r->txt_tax_brac,
            // 'shift_sched_from' => $r->txt_sft_1,
            // 'dayoff1' => $r->txt_day_off_1,
            // 'dayoff2' => $r->txt_day_off_2,
            'sex' => $r->txt_gen,
            'birth' => $r->txt_dt_birth,
            'civil_status' => $r->txt_civ_stat,
            'religion' => ($r->txt_reli != '') ? $r->txt_reli : '',
            // 'height' => ($r->txt_height != '') ? floatval($r->txt_height) : floatval(0),
            'height' => ($r->txt_height != '') ? Core::ToFloat($r->txt_height) : floatval(0),
            // 'weight' => ($r->txt_weight != '') ? floatval($r->txt_weight) : floatval(0),
            'weight' => ($r->txt_weight != '') ? Core::ToFloat($r->txt_weight) : floatval(0),
            'father' => ($r->txt_fath_name != '') ? $r->txt_fath_name : '',
            'father_address' => ($r->txt_fath_add != '') ? $r->txt_fath_add : '',
            'father_contact' => ($r->txt_fath_contact != '') ? $r->txt_fath_contact : '',
            'father_job' => ($r->txt_fath_occu != '') ? $r->txt_fath_occu : '',
            'mother'=> ($r->txt_moth_name != '') ? $r->txt_moth_name : '',
            'mother_address' => ($r->txt_moth_add != '') ? $r->txt_moth_add : '',
            'mother_contact' => ($r->txt_moth_contact != '') ? $r->txt_moth_contact : '',
            'mother_job' => ($r->txt_moth_occu != '') ? $r->txt_moth_occu : '',
            'emp_contact' => ($r->txt_contact_num != '') ? $r->txt_contact_num : '',
            'home_tel' => ($r->txt_home_tel != '') ? $r->txt_home_tel : '',
            'email' => ($r->txt_email != '') ? $r->txt_email : '',
            'home_address' => ($r->txt_home_add != '') ? $r->txt_home_add : '',
            'emergency_name' => ($r->txt_emerg_name != '') ? $r->txt_emerg_name : '',
            'emergency_contact' => ($r->txt_emerg_cont != '') ? $r->txt_emerg_cont : '',
            'em_home_address' => ($r->txt_emerg_add != '') ? $r->txt_emerg_add : '',
            'relationship' => ($r->txt_emerg_rel != '') ? $r->txt_emerg_rel : '',
            // 'shift_sched_sat_from' => $r->txt_sat_sft_1,
            // 'shift_sched_to' => $r->txt_sft_2,
            // 'shift_sched_sat_to' => $r->txt_sat_sft_2,
            'fixed_rate' => ($r->txt_fx_rate) ? 1 : '',
            'primary_ed' => ($r->txt_edu_pri != '') ? $r->txt_edu_pri : '',
            'secondary_ed' => ($r->txt_edu_sec != '') ? $r->txt_edu_sec : '',
            'tertiary_ed' => ($r->txt_edu_ter != '') ? $r->txt_edu_ter : '',
            'graduate' => ($r->txt_edu_grad != '') ? $r->txt_edu_grad : '',
            'post_graduate' => $r->txt_edu_post_grad,
            // 'sss_bracket' => $r->txt_ss_brac,
            'fixed_sched' => ($r->txt_fx_sched == 'Yes') ? "Y": "N",
        ];
        // dd(Core::get_nextincrementlimitchar(Core::getm99One('sr_code')->sr_code, 8));
        // dd($data);

        if($old_data->positions != $data['positions'] ||
            $old_data->department != $data['department'] ||
            $old_data->empstatus != $data['empstatus'] ||
            $old_data->pay_rate != $data['pay_rate']) {
            
            $service_record_data = [
                'sr_code'=>Core::get_nextincrementlimitchar(Core::getm99One('sr_code')->sr_code, 8),
                'empid'=>$r->txt_id, 
                'service_from'=>date('Y-m-d'), 
                'designation'=>$data['positions'], 
                'status'=>$data['empstatus'],
                'salary'=>($data['rate_type'] == 'D')?$data['pay_rate']*365:$data['pay_rate']*12, 
                'branch'=>$data['department'], 
                'leave_wo_pay'=>'', 
                'remarks'=>'', 
            ];

            // dd(ServiceRecord::Get_Latest_ServiceRecord($service_record_data['empid'])->sr_code);
            try {

                // Lloyd - Service Record
                DB::table(ServiceRecord::$tbl_name)->where(ServiceRecord::$pk, ServiceRecord::Get_Latest_ServiceRecord($service_record_data['empid'])->sr_code)->update(['service_to'=>date('Y-m-d')]);
                DB::table(ServiceRecord::$tbl_name)->insert($service_record_data);

                Core::updatem99('sr_code', $service_record_data['sr_code']);
            } catch (\Illuminate\Database\QueryException $e) {
                Core::Set_Alert('danger', $e->getMessage());
                return back();
            }
        }

        /*
        |------------------------
        | Code  |  Description  |
        |------------------------
        |   C   |   Casual      |
        |  JO   |   Job Order   |
        |   P   |   Permanent   |
        |------------------------
        
        $status = 'P';
        */

        try {

            DB::table(Employee::$tbl_name)->where(Employee::$pk, $r->txt_id)->update($data);

            Core::Set_Alert('success', 'Successfully modified an Employee.');
            return redirect('master-file/employee');

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
    public function delete(Request $r)
    {
        // Core::Set_Alert('danger', 'Unable to use this process.');
        try {
            $data = ['cancel' => 'Y'];
            DB::table(Employee::$tbl_name)->where(Employee::$pk, $r->txt_id)->update($data);
            Core::Set_Alert('success', 'Successfully deleted an Employee.');
             return back();
        //  DB::table(Employee::$tbl_name)->where(Employee::$pk, $r->txt_id)->delete();
        //  Core::Set_Alert('success', 'Successfully removed an Employee.');
        //  return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }

    /* ONLINE APPLICATION */
    public function application_view() 
    {
        $data = [];
        return view('pages.frontend.employee_online_application', compact('data'));
    }
    /* ONLINE APPLICATION */
}
