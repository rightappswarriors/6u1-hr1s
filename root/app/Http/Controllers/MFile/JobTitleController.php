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
        $this->SQLJobTitle = "SELECT * FROM hris.hr_jobtitle WHERE COALESCE(cancel,cancel,'')<>'Y' ORDER BY jtid ASC";
    }
    public function view()
    {
        $this->jobtitle = DB::select($this->SQLJobTitle);
    	return view('pages.mfile.job_title', ['jobtitle' => $this->jobtitle]);
    }
    public function add(Request $r)
    {
    	$sql = DB::select('SELECT max(jt_cn::int) FROM hris.hr_jobtitle WHERE cancel is null');
        $max = $sql[0]->max;
        $jt_cn = $max + 1;
        $data = ['jtid'=> strtoupper($r->txt_code) , 'jtitle_name' => $r->txt_name, 'jt_cn' => $jt_cn];
    	try { 
    		DB::table(JobTitle::$tbl_name)->insert($data);
            // Core::updatem99('jt_cn',Core::get_nextincrementlimitchar($jt_cn, 1));
    		Core::Set_Alert('success', 'Successfully added new Job Title.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }

    public function newAdd(Request $r) {
        try {
            $code = strtoupper($r->txt_code);
            $name = $r->txt_name;
            $nextIncrement = JobTitle::getNextIncrement();

            $jobTitle = JobTitle::getJobTitleByCode($code);
            if ($jobTitle != null) {

                if (JobTitle::isDeleted($jobTitle)) {
                    $errorCode = Core::$CODE_DELETED;
                    $message = 'Code was previously used but deleted';
                } else {
                    $errorCode = Core::$CODE_EXISTS;
                    $message = 'Code already exists';
                }

                return response()->json(Core::createErrorResponse($errorCode, $message), 400);
            }

            $data = [
                'jtid'          => $code,
                'jtitle_name'   => $name,
                'jt_cn'         => $nextIncrement
            ];
            DB::table(JobTitle::$tbl_name)->insert($data);

            $responseCode = 201;
            return response()->json(Core::createSuccessResponse($responseCode, $data), $responseCode);

        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = 500;
            return response()->json(Core::createErrorResponse($errorCode, $e->getMessage()), $errorCode);
        }
    }

    public function restore(Request $r) {
        try {
            $code = strtoupper($r->txt_code);
            
            $data = ['cancel' => null];

            $restored = tap(DB::table(JobTitle::$tbl_name)
                            ->where(JobTitle::$jtid, $code))
                            ->update($data)
                            ->first();

            Core::Set_Alert('success', 'Successfully restored Job Title.');

            $responseCode = 200;
            return response()->json(Core::createSuccessResponse($responseCode, $restored), $responseCode);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = 500;
            return response()->json(Core::createErrorResponse($errorCode, $e->getMessage()), $errorCode);    
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
        if (count(DB::table(JobTitle::$tbl_name)->whereNull("cancel")->where("jt_cn", '!=',$r->id)->where(JobTitle::$pk, "=", strtoupper($r->code))->get()) > 0) {
            return "true";
        }
        return "false";
    }
}
