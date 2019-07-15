<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use BusinessUnits;
use DB;

class BusinessUnitsController extends Controller
{
	public function __construct()
    {
        $SQLDepart = "SELECT * from hris.hr_business_unit WHERE COALESCE(cancel,cancel,'')<>'Y'";
        $this->depart = DB::select($SQLDepart);
    }
    public function view()
    {
    	// return dd($this->depart);
    	return view('pages.mfile.businessunits', ['dept' => $this->depart]);
    }
    public function add(Request $r) 
    {
        // return dd($r->all());
    	$data = [
                    'bucode'=>$r->txt_code, 
                    'bunit_desc' => $r->txt_name,
                    'bank_disburse' => $r->txt_ds_bnk,
                    'bank_addr' => $r->txt_bnk_add,
                    'accnt_no' => $r->txt_acc_num,
                    'contact_person' => $r->txt_ct_pr,
                    'designation_cp' => $r->txt_dsg,
                    'letter_format' => $r->txt_let_fm,
                    'bletter_prepared' => $r->txt_bk_lt_p,
                    'designation_blp' => $r->txt_dsg1,
                    'bletter_noted' => $r->txt_bk_lt_n,
                    'designation_bln' => $r->txt_dsg2,
                    'accnt_data_folder' => $r->txt_acc_dt_fl,
                ];
    	try {

    		DB::table(BusinessUnits::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Business Unit.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public static function getOne(Request $r)
    {
        try {
            return response()->json(['status'=>'OK','data'=>DB::table(BusinessUnits::$tbl_name)->where(BusinessUnits::$pk, '=', $r->id)->first()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
    }
    public function update(Request $r)
    {
    	$data = [
                    // 'bucode'=>$r->txt_code,
                    'bunit_desc' => $r->txt_name,
                    'bank_disburse' => $r->txt_ds_bnk,
                    'bank_addr' => $r->txt_bnk_add,
                    'accnt_no' => $r->txt_acc_num,
                    'contact_person' => $r->txt_ct_pr,
                    'designation_cp' => $r->txt_dsg,
                    'letter_format' => $r->txt_let_fm,
                    'bletter_prepared' => $r->txt_bk_lt_p,
                    'designation_blp' => $r->txt_dsg1,
                    'bletter_noted' => $r->txt_bk_lt_n,
                    'designation_bln' => $r->txt_dsg2,
                    'accnt_data_folder' => $r->txt_acc_dt_fl,
                ];
    	try {

    		DB::table(BusinessUnits::$tbl_name)->where(BusinessUnits::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Business Unit.');
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

            DB::table(BusinessUnits::$tbl_name)->where(BusinessUnits::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully modified a Business Unit.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
