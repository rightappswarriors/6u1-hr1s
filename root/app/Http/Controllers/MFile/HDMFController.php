<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use HDMF;
use DB;
use Pagibig;

class HDMFController extends Controller
{
	public function __construct()
	{
		
	}
	public function view()
	{
		$SQLHDMF = "SELECT * from hris.hr_hdmf WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->hdmf = DB::select($SQLHDMF);

		return view('pages.mfile.hdmf', ['hdmf' => $this->hdmf]);
	}
	public function add(Request $r)
	{
		$data  = [
					'code' => $r->txt_code,
					'bracket1' => $r->txt_br_1,
					'bracket2' => $r->txt_br_2,
					'pct' => $r->txt_pct,
					'emp_ee' => $r->txt_emp_sh,
					'emp_er' => $r->txt_eme_sh

				];
		try {

    		if(DB::table(HDMF::$tbl_name)->insert($data)){
    			Core::Set_Alert('success', 'Successfully added new HDMF.');
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
            return response()->json(['status'=>'OK','data'=>DB::table(HDMF::$tbl_name)->where(HDMF::$pk, '=', $r->id)->first()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
	}
	public static function update(Request $r)
	{
		$data  = [
					'bracket1' => $r->txt_br_1,
					'bracket2' => $r->txt_br_2,
					'pct' => $r->txt_pct,
					'emp_ee' => $r->txt_emp_sh,
					'emp_er' => $r->txt_eme_sh
				];
		try {

    		DB::table(HDMF::$tbl_name)->where(HDMF::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an HDMF.');
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

    		DB::table(HDMF::$tbl_name)->where(HDMF::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an HDMF.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}

	public function pagibig_add(Request $r)
	{
		$data = ['description' => $r->desc];
		try {
			Pagibig::Add($data);
			Core::Set_Alert('success', 'Successfully added a Pag-ibig Loan Type.');
    		return "Okay";
		} catch (\Illuminate\Database\QueryException $e) {
			dd($e);
    		Core::Set_Alert('danger', $e->getMessage());
    		return "Error";
    	}
	}

	public function pagibig_edit(Request $r)
	{
		$data = ['description' => $r->desc];
		try {
			Pagibig::Edit($r->pagibig_sub_id, $data);
			Core::Set_Alert('success', 'Successfully edited a Pag-ibig Loan Type.');
    		return "Okay";
		} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return "Error";
    	}
	}

	public function pagibig_del(Request $r)
	{
		$data = ['cancel' => true];
		try {
			Pagibig::Del($r->pagibig_sub_id, $data);
			Core::Set_Alert('success', 'Successfully deleted a Pag-ibig Loan Type.');
    		return "Okay";
		} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return "Error";
    	}
	}
}