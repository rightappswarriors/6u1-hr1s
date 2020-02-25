<?php

namespace App\Http\Controllers\Leave;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Leave;

class leaveApprovalController extends Controller
{
    //

    public function process(Request $request){
    	if($request->isMethod('GET')){
	    	$arrRet = [
	    		'forApproval' => DB::table('hris.hr_leaves_approval')->leftJoin('hris.hr_employee','hr_employee.empid','hr_leaves_approval.empid')->leftJoin('hris.hr_leave_type','hr_leave_type.code','hr_leaves_approval.leave_type')->where([['status',0]])->whereNull('hr_leaves_approval.cancel')->select('hr_leave_type.description','hr_leaves_approval.*','hr_employee.lastname','hr_employee.firstname')->get()
	    	];
	    	return view('pages.leave.leavedecision',$arrRet);
    	} else {
    		try {
    			$object = [];
	    		if(isset($request->id)){
	    			$data = DB::table("hr_leaves_approval")->where('approvalid',$request->id)->first();
	    			if(isset($data)){
	    				$object['cbo_employee'] = $data->empid;
	    				$object['dtp_filed'] = $data->d_filed;
	    				$object['cbo_leave'] = $data->leave_type;
	    				$object['dtp_lfrm'] = $data->leave_from;
	    				$object['txt_no_of_days'] = $data->no_of_days;
	    				$object['dtp_lto'] = $data->leave_to;
	    				$object['cbo_leave_pay'] = $data->leave_pay;
	    				$object['txt_reason'] = $data->leave_reason;
	    				$object['from'] = $request->id;
	    				$object['noofdays'] = null;
	    				$object['type'] = null;
	    				$object['mode'] = 'new';
	    				$requestToSend = new \Illuminate\Http\Request($object);
	    				$var = new \App\Http\Controllers\Timekeeping\LeavesEntryController;
	    				$return = $var->add($requestToSend);
	    				if($return){
	    					if(DB::table('hr_leaves_approval')->where('approvalid',$request->id)->update(['approvedby' => (Core::getSessionData()[0]->uid ?? 'NOT LOGGED IN'), 'approvedate' => Date('Y-m-d'), 'approvetime' => Date('G:i:s'), 'approveremark' => $request->remarks, 'status' => $request->judge])){
	    						return 'ok';
	    					}
	    				}
	    			}
	    		}
    		} 
    		catch (Exception $e) {
    			return $e;
    		}
    	}
    }

}
