<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use OtherEarnings;
use DB;

class OtherEarningsController extends Controller
{
	public function __construct()
    {
        $SQLOtherEarnings = "SELECT * from hr_other_earnings WHERE COALESCE(cancel,cancel,'')<>'Y'";
        $this->otherearnings = DB::select($SQLOtherEarnings);
    }
    public function view()
    {
    	// return dd($this->depart);
    	return view('pages.mfile.otherearnings', ['otherearnings' => $this->otherearnings]);
    }

    /**
    * add
    * @param Request
    *
    * @return view | back
    */
    public function add(Request $r) 
    {
        // return dd($r->all());
    	$data = ['code'=>$r->txt_code , 'description' => $r->txt_name];
    	try {

    		DB::table(OtherEarnings::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Other Earnings.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }

    /**
    * update
    * @param Request
    *
    * @return view | back
    */
    public function update(Request $r)
    {
    	$data = ['description' => $r->txt_name];
    	try {

    		DB::table(OtherEarnings::$tbl_name)->where(OtherEarnings::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Other Earnings.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }

    /**
    * delete
    * @param Request
    *
    * @return view | back
    */
    public function delete(Request $r)
    {
    	$data = ['cancel' => 'Y'];
        try {

            DB::table(OtherEarnings::$tbl_name)->where(OtherEarnings::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully modified an Other Earnings.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
