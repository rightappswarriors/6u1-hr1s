<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use Holiday;
use DB;

class HolidaysController extends Controller
{
	public function __construct()
	{
		$SQLHoliday = "SELECT * FROM hris.hr_holidays WHERE COALESCE(cancel,cancel,'')<>'Y' ORDER BY date_holiday DESC";
		$this->Holiday = DB::select($SQLHoliday);
	}
    public function view()
    {
    	// return dd($this->Holiday);
    	return view('pages.mfile.holidays', ['holiday' => $this->Holiday]);
    }
    public function add(Request $r)
    {
    	// return dd($r->all());
    	$data = ['date_holiday' => $r->txt_date, 'description' => $r->txt_name, 'holiday_type' => $r->txt_type];
    	try {

    		DB::table(Holiday::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Holiday.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
    	// return dd($r->all());
    	//code'=>$r->txt_code
    	$data = ['date_holiday' => $r->txt_date, 'description' => $r->txt_name, 'holiday_type' => $r->txt_type];
    	try {

    		DB::table(Holiday::$tbl_name)->where(Holiday::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Holiday.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
     public function delete(Request $r)
    {
    	// return dd($r->all());
    	//code'=>$r->txt_code
    	$data = ['cancel' => "Y"];
    	try {

    		DB::table(Holiday::$tbl_name)->where(Holiday::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully canceled a Holiday.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
}
