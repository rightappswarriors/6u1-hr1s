<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use ContributionRemittance;
use DB;

class ContributionRemittanceController extends Controller
{
	public function __construct()
	{
		$SQLHDMF = "SELECT * from hris.hr_contri_remittance WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->cr = DB::select($SQLHDMF);
	}
	public function view()
	{
		// return dd($this->hdmf);
		return view('pages.mfile.contributionremittance', ['cr' => $this->cr]);
	}
	public function add(Request $r)
	{
		// return dd($r->all());
		$data  = [
					'crcode' => $r->txt_code,
					'month' => $r->txt_mo,
					'sbr' => $r->txt_sbr,
					'sbr_date' => $r->txt_sbr_dt,
					'pbr' => $r->txt_pbr,
					'pbr_date' => $r->txt_pbr_dt,
					'pr' => $r->txt_pr,
					'pr_date' => $r->txt_pr_dt,

				];
		try {

    		if(DB::table(ContributionRemittance::$tbl_name)->insert($data)){
    			Core::Set_Alert('success', 'Successfully added new Contribution Remittance.');
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
            return response()->json(['status'=>'OK','data'=>DB::table(ContributionRemittance::$tbl_name)->where(ContributionRemittance::$pk, '=', $r->id)->first()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
	}
	public static function update(Request $r)
	{
		$data  = [
					'month' => $r->txt_mo,
					'sbr' => $r->txt_sbr,
					'sbr_date' => $r->txt_sbr_dt,
					'pbr' => $r->txt_pbr,
					'pbr_date' => $r->txt_pbr_dt,
					'pr' => $r->txt_pr,
					'pr_date' => $r->txt_pr_dt,

				];
		try {

    		DB::table(ContributionRemittance::$tbl_name)->where(ContributionRemittance::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Contribution Remittance.');
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

    		DB::table(ContributionRemittance::$tbl_name)->where(ContributionRemittance::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Contribution Remittance.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}
}