<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DateTime;
use Notification_N;
use X07;
use X08;

class NotificationController extends Controller
{
	public function __construct()
    {
        
    }

	public function view()
    {
        $this->notif = Notification_N::Load_Notifications();
        $this->x07 = X07::Load_X07();
        // $this->x08 = X08::Load_X08();

 		$x07 = $this->x07;
 		$notif = $this->notif;

 		$data = [$x07, $notif];
    	return view('pages.notification.notification_test', compact('data'));
    }

    public function send(Request $r)
    {
        $result = Notification_N::Send($r->groups, $r->content, $r->subject);
        return back()->with($result);
    }

    public function find(Request $r)
    {
        $notif = Notification_N::Get_Latest_Notification($r->uid);
        $notif_new = array();
        $notif_user = array();
        $noti_count = 0;

        foreach($notif as $k => $v) {
            if(strtotime(date('Y-m-d H:i:s')) >=  strtotime(Notification_N::Get_Notification_All_Info($v->ntf_id)->ntf_date)) {

                $date = new DateTime(date('Y-m-d', strtotime(Notification_N::Get_Notification_All_Info($v->ntf_id)->ntf_date)));
                $date_today = new DateTime(date('Y-m-d', strtotime(date('Y-m-d H:i:s'))));

                // return $date->diff($date_today)->days;

                if($v->seen && $date->diff($date_today)->days >= 2) {

                } else {
                    $notif_new[] = $v;
                    $notif_user[] = Notification_N::Get_Notification_All_Info($v->ntf_id);
                    if(!$v->seen) $noti_count++;
                }
            }
        }

        $data = [$notif_new, $notif_user, $noti_count];
        return $data;
    }

    public function toggle(Request $r)
    {
        $data = Notification_N::Toggle($r->x_uid, $r->val, $r->x_ntf_id);
        return ($data)?"Okay":"Not Okay";
    }
}