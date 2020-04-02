<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Session;
use Account;
use Notification_N;

class OTController extends Controller
{
    //
    public function view()
    {
    	$where = (Account::GET_DATA_FROM_CURRENT('grp_id') == 001 ? [['hr_ot.cancel',FALSE]] : [['empid',Account::ID()],['hr_ot.cancel',FALSE]]);
    	$ret = [
    		'list' => DB::table('hr_ot')->leftjoin('hr_employee as emp','hr_ot.empid','emp.empid')->leftjoin('x08 as finalize','hr_ot.finalize_uid','finalize.uid')->leftjoin('x08 as approval','hr_ot.approval_uid','approval.uid')->where($where)->select('hr_ot.*','emp.lastname','emp.firstname','finalize.opr_name as finalize_opr','approval.opr_name as approval_opr')->get(),
    		'current_loggedin' => Account::GET_DATA_FROM_CURRENT('grp_id')
    	];
    	return view('pages.timekeeping.OT.apply',$ret);
    }

    public function add(Request $r){
    	$data = ['apply_date'=> strtoupper($r->txt_date) , 'apply_remark' => $r->txt_name, 'empid' => Account::ID()];
    	try { 
    		DB::table('hr_ot')->insert($data);
            // Core::updatem99('jt_cn',Core::get_nextincrementlimitchar($jt_cn, 1));
    		Core::Set_Alert('success', 'Successfully added Overtime entry. Please wait for further notifications on system to be informed.');
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
        $data = ['apply_date'=> strtoupper($r->txt_date) , 'apply_remark' => $r->txt_name];
    	try {
            // $id = ($r->txt_code != $r->txt_temp) == true ? $r->txt_temp : $r->txt_code;
            $id = $r->txt_code;
            // return $id;
            // DB::table(JobTitle::$tbl_name)->where(JobTitle::$pk, $id)->update($data);
    		DB::table('hr_ot')->where('otid',$id)->update($data);
    		Core::Set_Alert('success', 'Successfully edited Overtime entry. Please wait for further notifications on system to be informed.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }

    public function delete(Request $r)
    {
    	$data = ['cancel' => TRUE];
        try {
            DB::table('hr_ot')->where('otid',$r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully removed a OT Application');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }

    public function finalize_view($param = null)
    {
        $ret = [
            'list' => DB::table('hr_ot')->leftjoin('hr_employee as emp','hr_ot.empid','emp.empid')->where([['hr_ot.cancel',FALSE],['hr_ot.finalize_decision',null]])->select('hr_ot.*','emp.lastname','emp.firstname')->get(),
            'title' => 'Finalize OT Application'
        ];
        return view('pages.timekeeping.OT.finalize',$ret);
    }

    public function finalize_actions(Request $r){
        try {
            
            $data = ['finalize_uid' => Account::ID(), 'finalize_decision' => $r->actionrequired, 'finalize_remark' => $r->remark, 'finalize_date_time' => Date('Y-m-d H:i:m')];
            if(DB::table('hr_ot')->where('otid',$r->txt_code)->update($data)){
                $data = DB::table('hr_ot')->where('otid',$r->txt_code)->first();
                Notification_N::sendNotificationSingle('Your OT application has been Finalized.','OT Application','/timekeeping/Apply-For-OT',$data->empid);
                Core::Set_Alert('success', 'Successfully Updated OT Application');
                return back();
            }

        } catch (Exception $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }

    public function approval_view($param = null)
    {
        $ret = [
            'list' => DB::table('hr_ot')->leftjoin('hr_employee as emp','hr_ot.empid','emp.empid')->where([['hr_ot.cancel',FALSE],['hr_ot.finalize_decision','<>',null],['hr_ot.approval_decision',null]])->select('hr_ot.*','emp.lastname','emp.firstname')->get(),
            'title' => 'Approval of OT Application'
        ];
        return view('pages.timekeeping.OT.finalize',$ret);
    }

    public function approval_actions(Request $r){
        try {
            
            $data = ['approval_uid' => Account::ID(), 'approval_decision' => $r->actionrequired, 'approval_remark' => $r->remark, 'approval_date_time' => Date('Y-m-d H:i:m')];
            if(DB::table('hr_ot')->where('otid',$r->txt_code)->update($data)){
                $data = DB::table('hr_ot')->where('otid',$r->txt_code)->first();
                Notification_N::sendNotificationSingle('Your OT application has been ' . ($r->actionrequired == 1 ? 'Approved' : 'Disapproved') . '.','OT Application','/timekeeping/Apply-For-OT',$data->empid);
                Core::Set_Alert('success', 'Successfully Updated OT Application');
                return back();
            }

        } catch (Exception $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }

}
