<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Account;
use Employee;
use EmployeeStatus;
use ErrorCode;
use JobTitle;
use Office;
use ServiceRecord;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->officeQuery = "SELECT * FROM rssys.m08 WHERE active = TRUE ORDER BY cc_desc ASC";
        $this->deptQuery = "SELECT * FROM hris.hr_department WHERE COALESCE(cancel, cancel, '')<>'Y' ORDER BY dept_name ASC";
        $this->positionQuery = "SELECT * FROM hris.hr_jobtitle WHERE COALESCE(cancel, cancel, '')<>'Y' ORDER BY jtitle_name ASC";
        $this->empStatusQuery = "SELECT * FROM hris.hr_emp_status WHERE COALESCE(cancel, cancel, '')<>'Y'";
        $this->rateQuery = "SELECT * FROM hris.hr_rate_type";
        $this->taxQuery = "SELECT * FROM hris.hr_wtax WHERE COALESCE(cancel, cancel, '')<>'Y'";
        $this->sssQuery = "SELECT code,s_credit FROM hris.hr_sss ORDER BY s_credit";
        $this->daysQuery = "SELECT * FROM hris.hr_days";
        $this->civilStatusQuery = "SELECT * FROM hris.hr_civil_status";

        $this->employeeQuery = "SELECT
                                emp.empid,
                                emp.increment, 
                                emp.positions, 
                                emp.department,
                                emp.firstname,
                                emp.lastname,
                                emp.empstatus,
                                emp.biometric,
                                emp.isheadoffacility,
                                emp.mi,

                                -- get employee's job title, limit 1 is needed because hris.hr_jobtitle's new pk jt_cn is not unique and has duplicate
                                (select 
                                    jt.jtitle_name 
                                 from hris.hr_jobtitle jt 
                                 where jt.jt_cn = emp.positions 
                                 limit 1) as jobtitle,

                                -- get employee's office
                                coalesce((select ofc.cc_desc 
                                          from rssys.m08 ofc 
                                          where ofc.cc_id = emp.department::integer), 
                                         'office-not-found') as office,

                                -- get employee's employment status  
                                coalesce((select est.description
                                          from hris.hr_emp_status est
                                          where est.status_id = emp.empstatus::integer), 
                                         'employee-status-not-found') as emp_status

                            from hris.hr_employee emp
                            WHERE coalesce(emp.cancel, emp.cancel, '')<>'Y'
                            order by increment desc";
    }
    public function view()
    {
        $this->office = DB::select($this->officeQuery);
        $this->employee = DB::select($this->employeeQuery);

        $data = [
            'employee'  => $this->employee,
            'office'    => $this->office,
        ];
        return view('pages.mfile.employee', $data);
    }
    public function new()
    {
        $this->office = DB::select($this->officeQuery);
        $this->dept = DB::select($this->deptQuery);
        $this->position = DB::select($this->positionQuery);
        $this->emp_status = DB::select($this->empStatusQuery);
        $this->rate_type = DB::select($this->rateQuery);
        $this->tax= DB::select($this->taxQuery);
        $this->sss= DB::select($this->sssQuery);
        $this->days= DB::select($this->daysQuery);
        $this->civil_stat = DB::select($this->civilStatusQuery);
        $this->employee = DB::select($this->employeeQuery);

        return view('pages.mfile.employee_new', ['dept' => $this->dept, 'position' => $this->position, 'emp_status' => $this->emp_status, 'tax' => $this->tax, 'rate' => $this->rate_type, 'sss' => $this->sss, 'day' => $this->days, 'civil_stat' => $this->civil_stat, 'employee' => $this->employee, 'office' => $this->office] );
    }
    public function new2()
    {
        $this->office = DB::select($this->officeQuery);
        $this->position = DB::select($this->positionQuery);
        $this->emp_status = DB::select($this->empStatusQuery);
        $this->rate_type = DB::select($this->rateQuery);
        $this->tax= DB::select($this->taxQuery);
        $this->civil_stat = DB::select($this->civilStatusQuery);

        return view('pages.mfile.employee_crud', 
            [
                'mode'          => 'new',
                'url'           => url('master-file/employee/add2'),
                'office'        => $this->office, 
                'position'      => $this->position,
                'emp_status'    => $this->emp_status,
                'rate'          => $this->rate_type,
                'tax'           => $this->tax,
                'civil_stat'    => $this->civil_stat
            ]);


    }
    public function add2(Request $r)
    {
        $increment = (empty(DB::table('hr_employee')->max('increment')) ? 1 : DB::table('hr_employee')->max('increment') + 1);
        $data = [
            'empid' => strtoupper($r->txt_id),
            'isheadoffacility' => (isset($r->isHeadOfFaci) ? true : false),
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
            'emptype' => $r->txt_emp_type,
            'contract_days' => ($r->txt_contract != '') ? (int)$r->txt_contract : 0,
            'prc' => ($r->txt_prc != '') ? $r->txt_prc : '',
            'ctc' => ($r->txt_ctc != '') ? $r->txt_ctc : '',
            'rate_type' => 'M',
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
            'accountnumber' => ($r->txt_accountnumber != '') ? $r->txt_accountnumber : '',
            'increment' => $increment,
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
            ErrorCode::Generate('controller', 'EmployeeController', '00001', $e->getMessage());
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
                'emptype' => $r->txt_emp_type,
                'contract_days' => ($r->txt_contract != '') ? (int)$r->txt_contract : 0,
                'prc' => ($r->txt_prc != '') ? $r->txt_prc : '',
                'ctc' => ($r->txt_ctc != '') ? $r->txt_ctc : '',
                'rate_type' => 'M',
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
                'accountnumber' => ($r->txt_accountnumber != '') ? $r->txt_accountnumber : '',
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
        $this->office = DB::select($this->officeQuery);
        $this->position = DB::select($this->positionQuery);
        $this->emp_status = DB::select($this->empStatusQuery);
        $this->rate_type = DB::select($this->rateQuery);
        $this->tax= DB::select($this->taxQuery);
        $this->civil_stat = DB::select($this->civilStatusQuery);

        $employee = Employee::GetEmployee($id);
        return view('pages.mfile.employee_crud', 
            [
                'mode' => 'edit', 
                'url' => url('master-file/employee/update'),
                'office' => $this->office,
                'position' => $this->position,
                'emp_status' => $this->emp_status,
                'rate' => $this->rate_type, 
                'tax' => $this->tax, 
                'civil_stat' => $this->civil_stat,
                'MYDATA' => $employee
            ]);
    }
    public function update(Request $r)
    {
        $increment = (empty(DB::table('hr_employee')->max('increment')) ? 1 : DB::table('hr_employee')->max('increment') + 1);
        $old_data = Employee::GetEmployee($r->txt_id);
        // dd($old_data);
        $data = [
            'empid' => strtoupper($r->txt_id),
            'isheadoffacility' => (isset($r->isHeadOfFaci) ? true : false),
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
            'emptype' => $r->txt_emp_type,
            'contract_days' => ($r->txt_contract != '') ? (int)$r->txt_contract : 0,
            'prc' => ($r->txt_prc != '') ? $r->txt_prc : '',
            'ctc' => ($r->txt_ctc != '') ? $r->txt_ctc : '',
            'rate_type' => $r->txt_rate_type,
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
            'accountnumber' => ($r->txt_accountnumber != '') ? $r->txt_accountnumber : '',
            'increment' => $increment
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
            // DB::table(Employee::$tbl_name)->where(Employee::$pk, $r->txt_id)->update($data);

            $fromdb = DB::table(Employee::$tbl_name)->where(Employee::$pk, $r->txt_id)->first();

            $data = [
                'empid' => strtoupper($fromdb->empid),
                'isheadoffacility' => (isset($fromdb->isHeadOfFaci) ? true : false),
                'lastname' => strtoupper($fromdb->lastname),
                'firstname' => strtoupper($fromdb->firstname),
                'mi' => $fromdb->mi,
                'department' => $fromdb->department,
                // 'section' => $fromdb->txt_deptsec,
                'positions' => $fromdb->positions,
                'date_hired' => $fromdb->date_hired,
                'contractual_date' => $fromdb->contractual_date,
                'date_resigned' => $fromdb->date_resigned,
                'date_terminated' => $fromdb->date_terminated,
                'prohibition_date' => $fromdb->prohibition_date,
                'date_regular' => $fromdb->date_regular,
                'empstatus' => $fromdb->empstatus,
                'emptype' => $fromdb->emptype,
                'contract_days' => $fromdb->contract_days,
                'prc' => $fromdb->prc,
                'ctc' => $fromdb->ctc,
                'rate_type' => 'M',
                // 'pay_rate' => floatval($fromdb->txt_py_rate),
                'pay_rate' => $fromdb->pay_rate,
                'biometric' => $fromdb->biometric,
                'sss' => $fromdb->sss,
                'pagibig' => $fromdb->pagibig,
                'philhealth' => $fromdb->philhealth,
                'payroll_account' => $fromdb->payroll_account,
                'tin' => $fromdb->tin,
                'tax_bracket' => $fromdb->tax_bracket,
                // 'shift_sched_from' => $fromdb->txt_sft_1,
                // 'dayoff1' => $fromdb->txt_day_off_1,
                // 'dayoff2' => $fromdb->txt_day_off_2,
                'sex' => $fromdb->sex,
                'birth' => $fromdb->birth,
                'civil_status' => $fromdb->civil_status,
                'religion' => $fromdb->religion,
                // 'height' => ($fromdb->txt_height != '') ? floatval($fromdb->txt_height) : floatval(0),
                'height' => $fromdb->height,
                // 'weight' => ($fromdb->txt_weight != '') ? floatval($fromdb->txt_weight) : floatval(0),
                'weight' => $fromdb->weight,
                'father' => $fromdb->father,
                'father_address' => $fromdb->father_address,
                'father_contact' => $fromdb->father_contact,
                'father_job' => $fromdb->father_job,
                'mother'=> $fromdb->mother,
                'mother_address' => $fromdb->mother_address,
                'mother_contact' => $fromdb->mother_contact,
                'mother_job' => $fromdb->mother_job,
                'emp_contact' => $fromdb->emp_contact,
                'home_tel' => $fromdb->home_tel,
                'email' => $fromdb->email,
                'home_address' => $fromdb->home_address,
                'emergency_name' => $fromdb->emergency_name,
                'emergency_contact' => $fromdb->emergency_contact,
                'em_home_address' => $fromdb->em_home_address,
                'relationship' => $fromdb->relationship,
                // 'shift_sched_sat_from' => $fromdb->txt_sat_sft_1,
                // 'shift_sched_to' => $fromdb->txt_sft_2,
                // 'shift_sched_sat_to' => $fromdb->txt_sat_sft_2,
                'fixed_rate' => $fromdb->fixed_rate,
                'primary_ed' => $fromdb->primary_ed,
                'secondary_ed' => $fromdb->secondary_ed,
                'tertiary_ed' => $fromdb->tertiary_ed,
                'graduate' => $fromdb->graduate,
                'post_graduate' => $fromdb->post_graduate,
                // 'sss_bracket' => $fromdb->txt_ss_brac,
                'fixed_sched' => $fromdb->fixed_sched,
                'accountnumber' => $fromdb->accountnumber,
                'increment' => $fromdb->increment,
                'deletedby' => Core::getSessionData()[0]->uid,
                't_date' => Date('Y-m-d'),
                't_time' => Date('H:i:s')
            ];
            DB::table('hr_employee_history')->insert($data);
            // DB::table(Employee::$tbl_name)->where(Employee::$pk, $r->txt_id)->delete();
            Core::Set_Alert('success', 'Successfully deleted an Employee.');
            DB::table(Employee::$tbl_name)->where(Employee::$pk, $r->txt_id)->delete();
            return back();
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

    public function get_employees(Request $r)
    {
        $ofc_emp = json_decode(Office::OfficeEmployees($r->id));
        if (count($ofc_emp) <= 0) {
            return "empty";
        } else {
            for ($i=0; $i < count($ofc_emp); $i++) { 
                $oe = $ofc_emp[$i];
                $flag = DB::table('hr_emp_flag')->where('empid', '=', $oe->empid)->first();
                if ($flag==null) {
                    $status = 0;
                } else {
                    $status = $flag->status;
                }
                $oe->flag = ($status==1) ? "checked" : "";
            }
        }
        return $ofc_emp;
    }

    public function updateFlag(Request $r)
    {
        // return dd($r->all());
        if ($r->id==null) {
            ErrorCode::Generate('controller', 'EmployeeController', '00002', "Missing Parameters (Employee).");
            return "missing";
        }

        if ($r->state==null) {
            ErrorCode::Generate('controller', 'EmployeeController', '00003', "Missing Parameters (State).");
            return "missing";
        }

        $flag = DB::table('hr_emp_flag')->where('empid', '=', $r->id)->first();
        $state = ($r->state == "true") ? 1 : 0;
        // return dd($flag, $state);

        if ($flag==null) {
            try {
                DB::table('hr_emp_flag')->insert([
                    'empid' => $r->id,
                    'status' => $state,
                    'generatedby' => Account::ID()
                ]);
                return "ok";
            } catch (\Exception $e) {
                ErrorCode::Generate('controller', 'EmployeeController', '00004', $e->getMessage());
                return "error";
            }
        }
        else {
            $id = DB::table('hr_emp_flag')->where('empid', $flag->empid)->first();
            if ($id!=null) {
                $id = $id->flag_id;
                DB::table('hr_emp_flag')->where('empid', $flag->empid)->update([
                    'status' => $state,
                    'generatedby' => Account::ID()
                ]);
                return "ok";
            } else {
                ErrorCode::Generate('controller', 'EmployeeController', '00005', "Employee not found.");
                return "not-found";
            }
        }
    }
    public function checkBiometric(Request $r)
    {
        $bio = $r->bio;
        $emp = $r->empid;
        if(isset($bio) && isset($emp)){
            return (DB::table('hr_employee')->where('biometric',$bio)->exists()  ? (DB::table('hr_employee')->where([['biometric',$bio],['empid',$emp]])->exists() ? 'unique' : 'not unique')  : 'unique');
        }
    }
}
