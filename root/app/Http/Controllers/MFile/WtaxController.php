<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use Wtax;
use DB;

class WtaxController extends Controller
{
	public function __construct()
	{
		$SQLWtax = "SELECT * FROM hris.hr_wtax WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->wtax = DB::select($SQLWtax);
	}
	public function view()
	{
		// return dd($this->wtax);
		return view('pages.mfile.wtax', ['wtax' => $this->wtax]);
	}
	public function add(Request $r)
	{
		// return dd($r->all());
		$data  = [
					'code' => $r->txt_code,
					'description' => $r->txt_desc,
					'exemption' => $r->txt_exemp,

					'bracket1' => ($r->brk[0] != '') ? Core::ToFloat($r->brk[0]) : 0.00,
					'bracket2' => ($r->brk[1] != '') ? Core::ToFloat($r->brk[1]) : 0.00,
					'bracket3' => ($r->brk[2] != '') ? Core::ToFloat($r->brk[2]) : 0.00,
					'bracket4' => ($r->brk[3] != '') ? Core::ToFloat($r->brk[3]) : 0.00,
					'bracket5' => ($r->brk[4] != '') ? Core::ToFloat($r->brk[4]) : 0.00,
					'bracket6' => ($r->brk[5] != '') ? Core::ToFloat($r->brk[5]) : 0.00,
					'bracket7' => ($r->brk[6] != '') ? Core::ToFloat($r->brk[6]) : 0.00,
					'bracket8' => ($r->brk[7] != '') ? Core::ToFloat($r->brk[7]) : 0.00,
					'bracket9' => ($r->brk[8] != '') ? Core::ToFloat($r->brk[8]) : 0.00,
					'bracket10'=> ($r->brk[9] != '') ? Core::ToFloat($r->brk[9]) : 0.00,

					'factor1' => ($r->fct[0] != '') ? Core::ToFloat($r->fct[0]) : 0.00,
					'factor2' => ($r->fct[1] != '') ? Core::ToFloat($r->fct[1]) : 0.00,
					'factor3' => ($r->fct[2] != '') ? Core::ToFloat($r->fct[2]) : 0.00,
					'factor4' => ($r->fct[3] != '') ? Core::ToFloat($r->fct[3]) : 0.00,
					'factor5' => ($r->fct[4] != '') ? Core::ToFloat($r->fct[4]) : 0.00,
					'factor6' => ($r->fct[5] != '') ? Core::ToFloat($r->fct[5]) : 0.00,
					'factor7' => ($r->fct[6] != '') ? Core::ToFloat($r->fct[6]) : 0.00,
					'factor8' => ($r->fct[7] != '') ? Core::ToFloat($r->fct[7]) : 0.00,
					'factor9' => ($r->fct[8] != '') ? Core::ToFloat($r->fct[8]) : 0.00,
					'factor10'=> ($r->fct[9] != '') ? Core::ToFloat($r->fct[9]) : 0.00,

					'add_on1' => ($r->addon[0] != '') ? Core::ToFloat($r->addon[0]) : 0.00,
					'add_on2' => ($r->addon[1] != '') ? Core::ToFloat($r->addon[1]) : 0.00,
					'add_on3' => ($r->addon[2] != '') ? Core::ToFloat($r->addon[2]) : 0.00,
					'add_on4' => ($r->addon[3] != '') ? Core::ToFloat($r->addon[3]) : 0.00,
					'add_on5' => ($r->addon[4] != '') ? Core::ToFloat($r->addon[4]) : 0.00,
					'add_on6' => ($r->addon[5] != '') ? Core::ToFloat($r->addon[5]) : 0.00,
					'add_on7' => ($r->addon[6] != '') ? Core::ToFloat($r->addon[6]) : 0.00,
					'add_on8' => ($r->addon[7] != '') ? Core::ToFloat($r->addon[7]) : 0.00,
					'add_on9' => ($r->addon[8] != '') ? Core::ToFloat($r->addon[8]) : 0.00,
					'add_on10'=> ($r->addon[9] != '') ? Core::ToFloat($r->addon[9]) : 0.00,
				];
		// dd($data);
		try {

    		if(DB::table(Wtax::$tbl_name)->insert($data)){
    			Core::Set_Alert('success', 'Successfully added new Witholding Tax.');
    			// Core::updatem99('empid', Core::get_nextincrementlimitchar($empid->empid, 8));
    			return back();
    		}
    		//

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}
	public static function getOne(Request $r)
	{
		try {
            return response()->json(['status'=>'OK','data'=>DB::table(Wtax::$tbl_name)->where(Wtax::$pk, '=', $r->id)->first()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
	}
	public static function update(Request $r)
	{
		// dd($r->all());
		$data  = [
					// 'code' => $r->txt_code,
					'description' => $r->txt_desc,
					'exemption' => $r->txt_exemp,
					
					'bracket1' => ($r->brk[0] != '') ? Core::ToFloat($r->brk[0]) : 0.00,
					'bracket2' => ($r->brk[1] != '') ? Core::ToFloat($r->brk[1]) : 0.00,
					'bracket3' => ($r->brk[2] != '') ? Core::ToFloat($r->brk[2]) : 0.00,
					'bracket4' => ($r->brk[3] != '') ? Core::ToFloat($r->brk[3]) : 0.00,
					'bracket5' => ($r->brk[4] != '') ? Core::ToFloat($r->brk[4]) : 0.00,
					'bracket6' => ($r->brk[5] != '') ? Core::ToFloat($r->brk[5]) : 0.00,
					'bracket7' => ($r->brk[6] != '') ? Core::ToFloat($r->brk[6]) : 0.00,
					'bracket8' => ($r->brk[7] != '') ? Core::ToFloat($r->brk[7]) : 0.00,
					'bracket9' => ($r->brk[8] != '') ? Core::ToFloat($r->brk[8]) : 0.00,
					'bracket10'=> ($r->brk[9] != '') ? Core::ToFloat($r->brk[9]) : 0.00,

					'factor1' => ($r->fct[0] != '') ? Core::ToFloat($r->fct[0]) : 0.00,
					'factor2' => ($r->fct[1] != '') ? Core::ToFloat($r->fct[1]) : 0.00,
					'factor3' => ($r->fct[2] != '') ? Core::ToFloat($r->fct[2]) : 0.00,
					'factor4' => ($r->fct[3] != '') ? Core::ToFloat($r->fct[3]) : 0.00,
					'factor5' => ($r->fct[4] != '') ? Core::ToFloat($r->fct[4]) : 0.00,
					'factor6' => ($r->fct[5] != '') ? Core::ToFloat($r->fct[5]) : 0.00,
					'factor7' => ($r->fct[6] != '') ? Core::ToFloat($r->fct[6]) : 0.00,
					'factor8' => ($r->fct[7] != '') ? Core::ToFloat($r->fct[7]) : 0.00,
					'factor9' => ($r->fct[8] != '') ? Core::ToFloat($r->fct[8]) : 0.00,
					'factor10'=> ($r->fct[9] != '') ? Core::ToFloat($r->fct[9]) : 0.00,

					'add_on1' => ($r->addon[0] != '') ? Core::ToFloat($r->addon[0]) : 0.00,
					'add_on2' => ($r->addon[1] != '') ? Core::ToFloat($r->addon[1]) : 0.00,
					'add_on3' => ($r->addon[2] != '') ? Core::ToFloat($r->addon[2]) : 0.00,
					'add_on4' => ($r->addon[3] != '') ? Core::ToFloat($r->addon[3]) : 0.00,
					'add_on5' => ($r->addon[4] != '') ? Core::ToFloat($r->addon[4]) : 0.00,
					'add_on6' => ($r->addon[5] != '') ? Core::ToFloat($r->addon[5]) : 0.00,
					'add_on7' => ($r->addon[6] != '') ? Core::ToFloat($r->addon[6]) : 0.00,
					'add_on8' => ($r->addon[7] != '') ? Core::ToFloat($r->addon[7]) : 0.00,
					'add_on9' => ($r->addon[8] != '') ? Core::ToFloat($r->addon[8]) : 0.00,
					'add_on10'=> ($r->addon[9] != '') ? Core::ToFloat($r->addon[9]) : 0.00,
				];
		// dd($data);
		try {

    		DB::table(Wtax::$tbl_name)->where(Wtax::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Witholding Tax.');
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

    		DB::table(Wtax::$tbl_name)->where(Wtax::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Witholding Tax.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}
}