<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;
use X07;
use X08;

class Notification_N extends Model
{

    public static $tbl_name = "notification";
    public static $tbl_name_2 = "notification_user";
    public static $pk = "ntf_id";

    public static function Load_Notifications()
    {
    	return DB::table(self::$tbl_name)->get();
    }

    public static function Get_Notification_Subject($ntf_id)
    {
    	try {
    		return DB::table(self::$tbl_name)->where('ntf_id', $ntf_id)->first()->ntf_subj;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Get_Notification_Content($ntf_id)
    {
    	try {
    		return DB::table(self::$tbl_name)->where('ntf_id', $ntf_id)->first()->ntf_cont;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Get_Notification_Date($ntf_id)
    {
    	try {
    		return DB::table(self::$tbl_name)->where('ntf_id', $ntf_id)->first()->ntf_date;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Get_Notification_Url($ntf_id)
    {
    	try {
    		return DB::table(self::$tbl_name)->where('ntf_id', $ntf_id)->first()->ntf_url;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Get_Notification_All_Info($ntf_id)
    {
    	try {
    		$data = DB::table(self::$tbl_name)->where('ntf_id', $ntf_id)->first();
    		$data->time_readable = substr(substr($data->ntf_date, 11), 0, 5);
    		$data->url_readable = ($data->ntf_url == null)?"#":$data->ntf_url;
    		return $data;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Get_Latest_Notification($uid)
    {
    	try {
    		return DB::table(self::$tbl_name_2)->where('uid', $uid)->where('cancel', null)->orderBy('ntf_id', 'DESC')->get();
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Toggle_Notification($uid, $ntf_id, $value)
    {
    	try {
    		$data = ['seen'=>$value];
    		return (DB::table(self::$tbl_name_2)->where('uid', $uid)->where('ntf_id', $ntf_id)->update($data))?"Okay":"Error";
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Get_Notification_Status($uid, $ntf_id)
    {
    	try {
    		return DB::table(self::$tbl_name_2)->where('uid', $uid)->where('ntf_id', $ntf_id)->first()->seen;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function sendNotificationGroupFromDB($dbid,$groupid = [],$customURL = null,$datetime = null){
        if(isset($dbid) && isset($groupid)){
            $datetime = ($datetime ?? date('Y-m-d H:i'));
            $data = DB::table('notification_messages')->where('notifid',$dbid)->first();
            if(isset($data)){
                return self::Send($groupid,$data->content,$data->subject,$datetime,($customURL ?? $data->url));
            }
        }
    }

    public static function sendNotificationSingleFromDB($dbid,$uid,$datetime = null){
        if(isset($dbid) && isset($uid)){
            $datetime = ($datetime ?? date('Y-m-d H:i'));
            $data = DB::table('notification_messages')->where('notifid',$dbid)->first();
            if(isset($data)){
                return self::sendNotificationSingle($data->content,$data->subject,$data->url,$uid,$datetime);
            }
        }
    }

    public static function sendNotificationSingle($content,$subject,$url = '#',$uid,$datetime = null){
        if(isset($content) && isset($subject) && isset($uid)){
            $datetime = ($datetime ?? date('Y-m-d H:i'));
            return self::Send([],$content,$subject,$datetime,$url,$uid);
        }
    }


    public static function Send(array $groups, string $content = "", string $subject, $datetime=null, $url="/home", $uidSolo = null)
    {
    	$grps = $toSend = array();
        $ngrps = null;
    	$x08 = X08::Load_X08();
        $ntfDate = ($datetime == null)?date('Y-m-d H:i').':00':$datetime;

    	for($i=0; $i<count($groups); $i++) {
    		$grps[] = $groups[$i];
    	}

    	try {
            if(!empty($grps)){
	    	  $ngrps = implode(', ', $grps);
            }

    		$data = [
    			'ntf_cont' => $content,
    			'grp_ids' => $ngrps,
    			'ntf_subj' => $subject,
    			'ntf_date' => $ntfDate,
                'ntf_url' => $url,
    		];

    		$id = DB::table(self::$tbl_name)->insertGetId($data, self::$pk);

            if(isset($uidSolo)){
                $ndata = [
                    'uid' => $uidSolo,
                    'ntf_id' => $id,
                    'seen' => 0,
                    'played' => 0,
                ];
                array_push($toSend, $ndata);
            } else {
                for($j=0; $j<count($x08); $j++) {
                    if(in_array(trim($x08[$j]->grp_id), $grps)) {
                        $ndata = [
                            'uid' => $x08[$j]->uid,
                            'ntf_id' => $id,
                            'seen' => 0,
                            'played' => 0,
                        ];
                        array_push($toSend, $ndata);
                    }
                }
            }
            if(DB::table(self::$tbl_name_2)->insert($toSend)){
                return "Okay";
            }
    	} catch (\Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Toggle($uid, $val, $ntf_id)
    {
        try {
            return DB::table(self::$tbl_name_2)->where('uid', $uid)->where('ntf_id', $ntf_id)->update(['played'=>$val]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}