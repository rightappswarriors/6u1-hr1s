<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Employee;

class OBController extends Controller
{
    //
    public function view(){
    	$arrRet = [
    		'ob' => DB::table('hr_ob')->join('hr_employee','hr_employee.empid','hr_ob.empid')->where([['active',TRUE]])->select('datefrom','dateto','firstname','lastname','obid','remark','hr_ob.empid')->get(),
    		'employee' => Employee::Load_Employees()
    	];
    	return view('pages.timekeeping.OB',$arrRet);
    }

    public function add(Request $r)
    {
    	$data = ['empid'=>$r->empid , 'datefrom' => $r->datefrom, 'dateto' => $r->dateto, 'remark' => $r->txt_limit];
    	try {
    		DB::table('hr_ob')->insert($data);
    		Core::Set_Alert('success', 'Successfully added new OB.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }

    public function update(Request $r)
    {
    	// $data = ['description' => strtoupper($r->txt_name), 'leave_limit' => $r->txt_limit, 'carry_over' => $r->txt_carry_over, 'incremental' => $r->increment];
    	$data = ['empid'=>$r->empid , 'datefrom' => $r->datefrom, 'dateto' => $r->dateto, 'remark' => $r->txt_limit];
    	try {

    		DB::table('hr_ob')->where('obid', $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Leave Types.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }

    public function delete(Request $r)
    {
    	$data = ['active' => FALSE];
        try {
            DB::table('hr_ob')->where('obid', $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully modified an Leave Types.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
