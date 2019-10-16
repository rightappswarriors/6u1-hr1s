<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use OtherDeductions;
use DB;

class OtherDeductionsController extends Controller
{
	public function __construct()
    {
        $SQLOtherEarnings = "SELECT * from hris.hr_other_deductions WHERE COALESCE(cancel,cancel,'')<>'Y'";
        $this->otherearnings = DB::select($SQLOtherEarnings);
    }
    public function view()
    {
    	// return dd($this->depart);
    	return view('pages.mfile.otherdeductions', ['otherearnings' => $this->otherearnings]);
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
    	$data = ['code'=>strtoupper($r->txt_code) , 'description' => $r->txt_name];
    	try {

    		DB::table(OtherDeductions::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Other Deductions.');
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

    		DB::table(OtherDeductions::$tbl_name)->where(OtherDeductions::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Other Deductions.');
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

            DB::table(OtherDeductions::$tbl_name)->where(OtherDeductions::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully modified an Other Deductions.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
