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
					'bracket1' => floatval(($r->brk[0] != '') ? $r->brk[0] : 0.00),
					'bracket2' => floatval(($r->brk[1] != '') ? $r->brk[1] : 0.00),
					'bracket3' => floatval(($r->brk[2] != '') ? $r->brk[2] : 0.00),
					'bracket4' => floatval(($r->brk[3] != '') ? $r->brk[3] : 0.00),
					'bracket5' => floatval(($r->brk[4] != '') ? $r->brk[4] : 0.00),
					'bracket6' => floatval(($r->brk[5] != '') ? $r->brk[5] : 0.00),
					'bracket7' => floatval(($r->brk[6] != '') ? $r->brk[6] : 0.00),
					'bracket8' => floatval(($r->brk[7] != '') ? $r->brk[7] : 0.00),
					'bracket9' => floatval(($r->brk[8] != '') ? $r->brk[8] : 0.00),
					'bracket10'=> floatval(($r->brk[9] != '') ? $r->brk[9] : 0.00),
					'factor1' => floatval(($r->fct[0] != '') ? $r->fct[0] : 0.00),
					'factor2' => floatval(($r->fct[1] != '') ? $r->fct[1] : 0.00),
					'factor3' => floatval(($r->fct[2] != '') ? $r->fct[2] : 0.00),
					'factor4' => floatval(($r->fct[3] != '') ? $r->fct[3] : 0.00),
					'factor5' => floatval(($r->fct[4] != '') ? $r->fct[4] : 0.00),
					'factor6' => floatval(($r->fct[5] != '') ? $r->fct[5] : 0.00),
					'factor7' => floatval(($r->fct[6] != '') ? $r->fct[6] : 0.00),
					'factor8' => floatval(($r->fct[7] != '') ? $r->fct[7] : 0.00),
					'factor9' => floatval(($r->fct[8] != '') ? $r->fct[8] : 0.00),
					'factor10'=> floatval(($r->fct[9] != '') ? $r->fct[9] : 0.00),
					'add_on1' => floatval(($r->addon[0] != '') ? $r->addon[0]: 0.00),
					'add_on2' => floatval(($r->addon[1] != '') ? $r->addon[1]: 0.00),
					'add_on3' => floatval(($r->addon[2] != '') ? $r->addon[2]: 0.00),
					'add_on4' => floatval(($r->addon[3] != '') ? $r->addon[3]: 0.00),
					'add_on5' => floatval(($r->addon[4] != '') ? $r->addon[4]: 0.00),
					'add_on6' => floatval(($r->addon[5] != '') ? $r->addon[5]: 0.00),
					'add_on7' => floatval(($r->addon[6] != '') ? $r->addon[6]: 0.00),
					'add_on8' => floatval(($r->addon[7] != '') ? $r->addon[7]: 0.00),
					'add_on9' => floatval(($r->addon[8] != '') ? $r->addon[8]: 0.00),
					'add_on10'=> floatval(($r->addon[9] != '') ? $r->addon[9]: 0.00),
				];
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
		$data  = [
					// 'code' => $r->txt_code,
					'description' => $r->txt_desc,
					'exemption' => $r->txt_exemp,
					'bracket1' => floatval(($r->brk[0] != '') ? $r->brk[0] : 0.00),
					'bracket2' => floatval(($r->brk[1] != '') ? $r->brk[1] : 0.00),
					'bracket3' => floatval(($r->brk[2] != '') ? $r->brk[2] : 0.00),
					'bracket4' => floatval(($r->brk[3] != '') ? $r->brk[3] : 0.00),
					'bracket5' => floatval(($r->brk[4] != '') ? $r->brk[4] : 0.00),
					'bracket6' => floatval(($r->brk[5] != '') ? $r->brk[5] : 0.00),
					'bracket7' => floatval(($r->brk[6] != '') ? $r->brk[6] : 0.00),
					'bracket8' => floatval(($r->brk[7] != '') ? $r->brk[7] : 0.00),
					'bracket9' => floatval(($r->brk[8] != '') ? $r->brk[8] : 0.00),
					'bracket10'=> floatval(($r->brk[9] != '') ? $r->brk[9] : 0.00),
					'factor1' => floatval(($r->fct[0] != '') ? $r->fct[0] : 0.00),
					'factor2' => floatval(($r->fct[1] != '') ? $r->fct[1] : 0.00),
					'factor3' => floatval(($r->fct[2] != '') ? $r->fct[2] : 0.00),
					'factor4' => floatval(($r->fct[3] != '') ? $r->fct[3] : 0.00),
					'factor5' => floatval(($r->fct[4] != '') ? $r->fct[4] : 0.00),
					'factor6' => floatval(($r->fct[5] != '') ? $r->fct[5] : 0.00),
					'factor7' => floatval(($r->fct[6] != '') ? $r->fct[6] : 0.00),
					'factor8' => floatval(($r->fct[7] != '') ? $r->fct[7] : 0.00),
					'factor9' => floatval(($r->fct[8] != '') ? $r->fct[8] : 0.00),
					'factor10'=> floatval(($r->fct[9] != '') ? $r->fct[9] : 0.00),
					'add_on1' => floatval(($r->addon[0] != '') ? $r->addon[0]: 0.00),
					'add_on2' => floatval(($r->addon[1] != '') ? $r->addon[1]: 0.00),
					'add_on3' => floatval(($r->addon[2] != '') ? $r->addon[2]: 0.00),
					'add_on4' => floatval(($r->addon[3] != '') ? $r->addon[3]: 0.00),
					'add_on5' => floatval(($r->addon[4] != '') ? $r->addon[4]: 0.00),
					'add_on6' => floatval(($r->addon[5] != '') ? $r->addon[5]: 0.00),
					'add_on7' => floatval(($r->addon[6] != '') ? $r->addon[6]: 0.00),
					'add_on8' => floatval(($r->addon[7] != '') ? $r->addon[7]: 0.00),
					'add_on9' => floatval(($r->addon[8] != '') ? $r->addon[8]: 0.00),
					'add_on10'=> floatval(($r->addon[9] != '') ? $r->addon[9]: 0.00),
				];
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