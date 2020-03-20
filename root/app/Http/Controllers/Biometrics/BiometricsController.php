<?php

namespace App\Http\Controllers\Biometrics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use ErrorCode;
use TimeLogEntryController;
use Notification_N;

class BiometricsController extends Controller
{
    public $databaseData;
    public function __construct(){
        $this->databaseData = [['1','am',Core::getm99One('span_am_in')->span_am_in],['0','am',Core::getm99One('span_am_out')->span_am_out],['1','pm',Core::getm99One('span_pm_in')->span_pm_in],['0','pm',Core::getm99One('span_pm_out')->span_pm_out]];
    }

    //
    public function ReceiveData(Request $request){
    	// returns : 1st array: not found on database, 2nd array: success: 3rd array: time not on span: 4th: exceed
    	if(isset($request->data)){  
    		$decoded = json_decode($request->data);
    		$noData = $successArr = $unsuccessArr = $notonspan = $exceedArr = [];
            $success = false;
            $toAdd = true;
            $action = null;
    		foreach ($decoded as $key => $value) {
    			$object = [];
    			$employeeDetails = DB::table('hr_employee')->where('biometric',$value->userid)->select('empid')->first();
    			if(isset($employeeDetails)){
                    $trigger = self::processTimeForSpan($this->databaseData,$value->time);
                    if(isset($trigger)){
                       $returnOfInOutProcess = self::processInOut($trigger[0],$trigger[2],$value->time,$employeeDetails->empid);
                       if($returnOfInOutProcess[0]){
                           switch ($trigger[0]) {
                               case '1':
                                    $toAdd = true;
                                    break;
                                case '0':
                                    $success = DB::table('hr_tito2');
                                    if(isset($returnOfInOutProcess[2])){
                                        $success = $success->where([['serial_id',$returnOfInOutProcess[2]]])->update(['time_log'=>Date('G:i',strtotime($value->time))]);
                                    } else {
                                        $toAdd = true;
                                    }
                                   break;
                           }

                           if($toAdd){
                                $object['sel_status'] = $trigger[0];
                                $object['id'] = $employeeDetails->empid;
                                $object['date_workdate'] = Date('Y-m-d',strtotime($value->time));
                                $object['time_timelog'] = Date('G:i:s',strtotime($value->time));
                                $object['source'] = 'B';
                                if(!empty($object)){
                                    $var = new \App\Http\Controllers\Timekeeping\TimeLogEntryController;
                                    $requestToSend = new \Illuminate\Http\Request($object);
                                    $return = $var->addLog($requestToSend);
                                    if($return != 'exceed'){
                                        $success = $return;
                                    } else {
                                        array_push($exceedArr, $value->userid);
                                    }
                                }
                           }
                           if($success){
                                $action = 'saved';
                                array_push($successArr, $value->userid);
                           } else {
                                array_push($unsuccessArr, $value->userid);
                                $action = 'unsaved';
                           }
                       }
                    } else {
                        array_push($notonspan, $value->userid);
                        $action = 'not_on_time_span';
                    }
    			} else {
    				array_push($noData, $value->userid);
                    $action = 'not_on_employee_list';
    			}

                if(DB::table('biometricdata')->where([['empid' , $value->userid],['date', Date('Y-m-d',strtotime($value->time))],['action', $action]])->doesntExist()){

                    DB::table('biometricdata')->insert([
                        'selection' => ($object['sel_status'] ?? null), 
                        'empid' => ($employeeDetails->empid ?? $value->userid), 
                        'time' => Date('G:i:s',strtotime($value->time)), 
                        'date' => Date('Y-m-d',strtotime($value->time)), 
                        'action' => $action, 
                        't_time' => Date('G:i:s'), 
                        't_date' => Date('Y-m-d')
                    ]);
                    $lastid = DB::getPdo()->lastInsertId();

                    if(isset($lastid) && $action == 'not_on_time_span'){
                        if(Notification_N::sendNotificationGroupFromDB(1,['001'],'/timekeeping/timelog-entry?ref='.$lastid) == 'Okay'){
                            Notification_N::sendNotificationSingleFromDB(1,$value->userid);
                        }   
                    }
                }
    			
    		}

    		return response()->json([$noData,$successArr,$notonspan,$exceedArr]);
    	}

    }

    public static function processTimeForSpan($timeSpan,$employeetimestamp){
        if(isset($timeSpan) && isset($employeetimestamp)){
            $toReturn = [];
            $data = $timeSpan;
            $formattedTime = new \Datetime(Date('H:i',strtotime($employeetimestamp)));
            foreach ($data as $key => $value) {
                if(isset($value[2])){
                    $decoded = json_decode($value[2]);
                    if(isset($decoded[0]) && isset($decoded[1])){
                        $from = new \Datetime(Date('H:i',strtotime($decoded[0])));
                        $to = new \Datetime(Date('H:i',strtotime($decoded[1])));
                        if (self::isTimeInBetween($formattedTime,$from,$to)){
                            return [$value[0],$value[1],[Date('H:i',strtotime($decoded[0])),Date('H:i',strtotime($decoded[1]))]];
                        }
                    }
                }
            }
        }
        return null;
    }


    public static function isTimeInBetween($timeToCalculate,$from,$to){
        return ($timeToCalculate >= $from && $timeToCalculate <= $to);
    }

    public static function processInOut($operation,$ftDates,$date,$empid){
        $table = 'hr_tito2';
        if(isset($operation) && isset($date) && isset($empid) && isset($ftDates)){
            $formattedTime = Date('H:i',strtotime($date));
            $from = new \Datetime(Date('H:i',strtotime($ftDates[0])));
            $to = new \Datetime(Date('H:i',strtotime($ftDates[1])));
            $parsedDate = Date('Y-m-d',strtotime($date));
            $data = DB::table($table);
            $where = [['empid',$empid],['work_date',$parsedDate],['status',$operation]];
            $data = $data->where($where);
            $toReturn = false;
            switch ($operation) {
                case '1':
                    $data = $data->get();
                    if(!isset($data)){
                        $toReturn = [true,null];
                    } else {
                        foreach ($data as $key => $value) {
                            if(self::isTimeInBetween(new \Datetime(Date('H:i',strtotime($value->time_log))),$from,$to)){
                                return [false,$value->empid,$value->serial_id];
                            }
                        }
                        $toReturn = [true,null];
                    }
                    return $toReturn;
                    break;
                 case '0':
                    $data = $data->orderBy('serial_id','DESC')->get();
                    if(!isset($data)){
                        $toReturn = [true,null];
                    } else {
                        foreach ($data as $key => $value) {
                            if(self::isTimeInBetween(new \Datetime(Date('H:i',strtotime($value->time_log))),$from,$to)){
                                return [true,$value->empid,$value->serial_id];
                            }
                        }
                        $toReturn = [true,null];
                    }
                    return $toReturn;
                    break;
            }
            return [false,null];
        }
    }



}
