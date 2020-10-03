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

	}

	public function view()
	{
		$SQLWtax = "SELECT * FROM hris.hr_wtax WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->wtax = DB::select($SQLWtax);
		
		return view('pages.mfile.wtax', ['wtax' => $this->wtax]);
	}

	public function add(Request $r)
	{
		$data  = [
			'code' 			=> strtoupper($r->txt_code),
			'description' 	=> $r->txt_desc,
			'exemption' 	=> $r->txt_exemp,
		];

		$data = $this->mapData($data, $r);
		try {

    		if(DB::table(Wtax::$tbl_name)->insert($data)){
    			Core::Set_Alert('success', 'Successfully added new Witholding Tax.');
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
            return response()->json(['status'=>'OK','data'=>DB::table(Wtax::$tbl_name)->where(Wtax::$pk, '=', $r->id)->first()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
	}
	public function update(Request $r)
	{
		$data  = [
			// 'code' => $r->txt_code,
			'description' => $r->txt_desc,
			'exemption' => $r->txt_exemp,
		];

		$data = $this->mapData($data, $r);
		try {
			$code = strtoupper($r->txt_code);

    		DB::table(Wtax::$tbl_name)->where(Wtax::$pk, $code)->update($data);
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
			$code = strtoupper($r->txt_code);

    		DB::table(Wtax::$tbl_name)->where(Wtax::$pk, $code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Witholding Tax.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}

	private  function mapData($data, Request $r) {
		for ($i = 1; $i <= 10; $i++) {
			$bracketKey = 'bracket' . $i;
			$factorKey = 'factor' . $i;
			$addOnKey = 'add_on' . $i;

			$bracketValue = $r->brk[$i - 1] != '' ? Core::ToFloat($r->brk[$i - 1]) : 0.00;
			$factorValue = $r->fct[$i - 1] != '' ? Core::ToFloat($r->fct[$i - 1]) : 0.00;
			$addOnValue = $r->addon[$i - 1] != '' ? Core::ToFloat($r->addon[$i - 1]) : 0.00;

			$data[$bracketKey] = $bracketValue;
			$data[$factorKey] = $factorValue;
			$data[$addOnKey] = $addOnValue;
		}

		return $data;
	}
}