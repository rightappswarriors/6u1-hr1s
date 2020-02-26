<?php

namespace App\Http\Controllers\Biometrics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use ErrorCode;
use TimeLogEntryController;

class BiometricsController extends Controller
{
    //
    public function ReceiveData(Request $request){
    	// returns : 1st array: not found on database, 2nd array: success: 3rd array: already added
    	if(isset($request->data)){
    		$decoded = json_decode($request->data);
    		$noData = $success = $alreadyloggedin = [];
    		foreach ($decoded as $key => $value) {
    			$object = [];
    			$employeeDetails = DB::table('hr_employee')->where('biometric',$value->userid)->select('empid')->first();
    			if(isset($employeeDetails)){

    				switch ($value->status) {
    					case 'OUT':
    						$object['sel_status'] = 0;
    						break;
    					case 'IN':
    						$object['sel_status'] = 1;
    						break;
    				}

    				if(DB::table('hr_tito2')->where([['work_date',Date('Y-m-d',strtotime($value->time))],['empid',$employeeDetails->empid],['status',$object['sel_status']]])->doesntExist()){
	    				$object['id'] = $employeeDetails->empid;
	    				$object['date_workdate'] = Date('Y-m-d',strtotime($value->time));
	    				$object['time_timelog'] = Date('G:i:s',strtotime($value->time));
	    				$object['source'] = 'B';

	    				if(!empty($object)){
	    					$var = new \App\Http\Controllers\Timekeeping\TimeLogEntryController;
	    					$requestToSend = new \Illuminate\Http\Request($object);
	    					$return = $var->addLog($requestToSend);
	    					if($return){
	    						$success = $return;
	    					}
	    				}
    				} else {
    					array_push($alreadyloggedin, $value->userid);
    				}

    			} else {
    				array_push($noData, $value->userid);
    			}
    			
    		}

    		return response()->json([$noData,$success,$alreadyloggedin]);
    	}

    }
}
