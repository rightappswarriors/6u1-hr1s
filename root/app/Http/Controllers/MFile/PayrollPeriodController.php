<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Payroll;
use PayrollPeriod;

class PayrollPeriodController extends Controller
{
    // protected $payrollperiod;
    
    public function __construct()
    {
        $this->payrollperiod = DB::table('hr_payrollpariod')->where('cancel', '=', null)->orderBy('date_from', 'DESC')->get();
    }

    public function view()
    {
        // $data = [];
        // return dd($this->payrollperiod);
        return view('pages.mfile.payrollperiod', ['payroll' => $this->payrollperiod]);
    }

    public function add(Request $r)
    {
        // return dd($r->all());
        $data = [
                'pay_code'=>$r->txt_code ,
                'date_from' => $r->txt_dt_fr,
                'date_to' => $r->txt_dt_to,
                'd_w_tax' => (isset($r->txt_with_tax)) ? 'Y' : 'N',
                'd_sss_c' => (isset($r->txt_sss)) ? 'Y' : 'N',
                'd_philhealth' => (isset($r->txt_philhealth)) ? 'Y' : 'N',
                'd_pagibig' => (isset($r->txt_pag_ibig)) ? 'Y' : 'N',
                'financial_year' => $r->txt_yr,
                'month' => $r->txt_mo,
                'pay_type' => $r->txt_typ,
                'num_days' => $r->txt_work_d,
                'payroll_classic' => '',
                'gen_13_month' => (isset($r->txt_gen_13_mo)) ? 'Y' : 'N',
                'gen_13month_from' => (isset($r->txt_gen_13_mo)) ? $r->txt_gen_13_dt_fr : null,
                'gen_13month_to' => (isset($r->txt_gen_13_mo)) ? $r->txt_gen_13_dt_to : null,
            ];
        try {

            DB::table(PayrollPeriod::$tbl_name)->insert($data);
            Core::Set_Alert('success', 'Successfully added new Payroll Period.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
    public function getOne(Request $r)
    {
        try {
            return response()->json(['status'=>'OK','data'=>DB::table(PayrollPeriod::$tbl_name)->where(PayrollPeriod::$pk, '=', $r->id)->first()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
    }
    public function update(Request $r)
    {
        $data = [
                // 'pay_code'=>$r->txt_code ,
                'date_from' => $r->txt_dt_fr,
                'date_to' => $r->txt_dt_to,
                'd_w_tax' => (isset($r->txt_with_tax)) ? 'Y' : 'N',
                'd_sss_c' => (isset($r->txt_sss)) ? 'Y' : 'N',
                'd_philhealth' => (isset($r->txt_philhealth)) ? 'Y' : 'N',
                'd_pagibig' => (isset($r->txt_pag_ibig)) ? 'Y' : 'N',
                'financial_year' => $r->txt_yr,
                'month' => $r->txt_mo,
                'pay_type' => $r->txt_typ,
                'num_days' => $r->txt_work_d,
                'payroll_classic' => '',
                'gen_13_month' => (isset($r->txt_gen_13_mo)) ? 'Y' : 'N',
                'gen_13month_from' => (isset($r->txt_gen_13_mo)) ? $r->txt_gen_13_dt_fr : null,
                'gen_13month_to' => (isset($r->txt_gen_13_mo)) ? $r->txt_gen_13_dt_to : null,
            ];
        try {

            DB::table(PayrollPeriod::$tbl_name)->where(PayrollPeriod::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully modified a Payroll Period.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }

    public function delete(Request $r)
    {
        $data = ['cancel' => 'Y'];
        try {

            DB::table(PayrollPeriod::$tbl_name)->where(PayrollPeriod::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully removed a Payroll Period.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }

    public function getdates(Request $r)
    {
        try {
            $pp = Payroll::PayrollPeriod2($r->month, $r->pp, $r->year);
            $pp->from = date('Y-m-d', strtotime($pp->from));
            $pp->to = date('Y-m-d', strtotime($pp->to));
            return json_encode($pp);
        } catch (\Exception $e) {
            return "error";
        }
    }
}
