<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use JobTitle;
use DB;

class JobTitleController extends Controller
{
	public function __construct()
    {
        $SQLJobTitle = "SELECT * FROM hris.hr_jobtitle WHERE COALESCE(cancel,cancel,'')<>'Y' ORDER BY jtid ASC";
        $this->jobtitle = DB::select($SQLJobTitle);
    }
    public function view()
    {
    	// return dd($this->jobtitle);
    	return view('pages.mfile.job_title', ['jobtitle' => $this->jobtitle]);
    }
    public function add(Request $r)
    {
    	$jt_cn = Core::getm99('jt_cn');
        $data = ['jtid'=> strtoupper($r->txt_code) , 'jtitle_name' => $r->txt_name, 'jt_cn' => $jt_cn];
    	try { 
    		DB::table(JobTitle::$tbl_name)->insert($data);
            Core::updatem99('jt_cn',Core::get_nextincrementlimitchar($jt_cn, 1));
    		Core::Set_Alert('success', 'Successfully added new Job Title.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
        // return dd($r->all());
        // if(($r->txt_name != $r->txt_temp) == true) {
        //     $data = ['jtid' => strtoupper($r->txt_code), 'jtitle_name' => $r->txt_name];
        // } else {
        //     $data = ['jtitle_name' => $r->txt_name];
        // }
        $data = ['jtid' => strtoupper($r->txt_code), 'jtitle_name' => $r->txt_name];
    	try {
            // $id = ($r->txt_code != $r->txt_temp) == true ? $r->txt_temp : $r->txt_code;
            $id = $r->txt_temp;
            // return $id;
            // DB::table(JobTitle::$tbl_name)->where(JobTitle::$pk, $id)->update($data);
    		DB::table(JobTitle::$tbl_name)->where(JobTitle::$newpk, $id)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Job Title.');
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

            DB::table(JobTitle::$tbl_name)->where(JobTitle::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully removed a Job Title.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
    public function check(Request $r)
    {
        if (count(DB::table(JobTitle::$tbl_name)->where("jt_cn", '!=',$r->id)->where(JobTitle::$pk, "=", strtoupper($r->code))->get()) > 0) {
            return "true";
        }
        return "false";
    }
}
