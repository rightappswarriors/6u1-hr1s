<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use Philhealth;
use DB;

class PhilhealthController extends Controller
{
	public function __construct()
	{
		$SQLPhilhealth = "SELECT * from hris.hr_Philhealth WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->Philhealth = DB::select($SQLPhilhealth);
	}
	public function view()
	{
		// return dd($this->Philhealth);
		return view('pages.mfile.philhealth', ['philhealth' => $this->Philhealth]);
	}
	public function add(Request $r)
	{
		// return dd($r->all());
		$data  = [
					'code' => $r->txt_code,
					'bracket1' => $r->txt_br_1,
					'bracket2' => $r->txt_br_2,
					'salary_base' => $r->txt_sal_bs,
					'emp_er' => $r->txt_emp_sh,
					'emp_ee' => $r->txt_eme_sh,

				];
		try {

    		if(DB::table(Philhealth::$tbl_name)->insert($data)){
    			Core::Set_Alert('success', 'Successfully added new Philhealth.');
    			// Core::updatem99('empid', Core::get_nextincrementlimitchar($empid->empid, 8));
    			return back();
    		}
 

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}
	public static function getOne(Request $r)
	{
		try {
            return response()->json(['status'=>'OK','data'=>DB::table(Philhealth::$tbl_name)->where(Philhealth::$pk, '=', $r->id)->first()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
	}
	public static function update(Request $r)
	{
		$data  = [
					// 'code' => $r->txt_code,
					'bracket1' => $r->txt_br_1,
					'bracket2' => $r->txt_br_2,
					'salary_base' => $r->txt_sal_bs,
					'emp_er' => $r->txt_emp_sh,
					'emp_ee' => $r->txt_eme_sh,

				];
		try {

    		DB::table(Philhealth::$tbl_name)->where(Philhealth::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Philhealth.');
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

    		DB::table(Philhealth::$tbl_name)->where(Philhealth::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Philhealth.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}
}