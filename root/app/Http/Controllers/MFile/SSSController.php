<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use SSS;
use DB;

class SSSController extends Controller
{
	public function __construct()
	{
		$SQLSSS = "SELECT * from hris.hr_sss WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->sss = DB::select($SQLSSS);
	}
	public function view()
	{
		// return dd($this->sss);
		return view('pages.mfile.sss', ['sss' => $this->sss]);
	}
	public function add(Request $r)
	{
		// return dd($r->all());
		$data  = [
					'code' => $r->txt_code,
					'bracket1' => $r->txt_br_1,
					'bracket2' => $r->txt_br_2,
					's_credit' => $r->txt_sal_cre,
					'empshare_sc' => $r->txt_emp_sh,
					's_ec' => $r->txt_ec,
					'empshare_ec' => $r->txt_eme_sh

				];
		try {

    		if(DB::table(SSS::$tbl_name)->insert($data)){
    			Core::Set_Alert('success', 'Successfully added new SSS.');
    			// Core::updatem99('empid', Core::get_nextincrementlimitchar($empid->empid, 8));
    			return back();
    		}
 

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}
	public function addSub(Request $r)
	{
		if(SSS::Add(['description' => $r->desc])){
			Core::Default_Alert_Msg('success');
		}
		return 'Okay';
	}

	public function editSub(Request $r)
	{
		if(SSS::Edit($r->sss_sub_id,['description' => $r->desc])){
			Core::Default_Alert_Msg('success');
		}
		return 'Okay';
	}
	public function delSub(Request $r)
	{
		if(SSS::Del($r->sss_sub_id,['cancel' => 'Y'])){
			Core::Default_Alert_Msg('success');
		}
		return 'Okay';
	}
	public static function getOne(Request $r)
	{
		try {
            return response()->json(['status'=>'OK','data'=>DB::table(SSS::$tbl_name)->where(SSS::$pk, '=', $r->id)->first()]);
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
					's_credit' => $r->txt_sal_cre,
					'empshare_sc' => $r->txt_emp_sh,
					's_ec' => $r->txt_ec,
					'empshare_ec' => $r->txt_eme_sh

				];
		try {

    		DB::table(SSS::$tbl_name)->where(SSS::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an SSS.');
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

    		DB::table(SSS::$tbl_name)->where(SSS::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an SSS.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}

	public function sss_add(Request $r)
	{
		$data = ['description' => $r->desc];
		try {
			SSS::Add($data);
			Core::Set_Alert('success', 'Successfully added a SSS Loan Type.');
    		return "Okay";
		} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return "Error";
    	}
	}

	public function sss_edit(Request $r)
	{
		$data = ['description' => $r->desc];
		try {
			SSS::Edit($r->sss_sub_id, $data);
			Core::Set_Alert('success', 'Successfully edited a SSS Loan Type.');
    		return "Okay";
		} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return "Error";
    	}
	}

	public function sss_del(Request $r)
	{
		$data = ['cancel' => true];
		try {
			SSS::Del($r->sss_sub_id, $data);
			Core::Set_Alert('success', 'Successfully deleted a SSS Loan Type.');
    		return "Okay";
		} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return "Error";
    	}
	}
}