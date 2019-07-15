<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Notification_N;
use X07;
use X08;

class NotificationController extends Controller
{
	public function __construct()
    {
        $this->notif = Notification_N::Load_Notifications();
        $this->x07 = X07::Load_X07();
        $this->x08 = X08::Load_X08();
    }

	public function view()
    {
        // dd(date('Y-m-d H:i:s'));
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
        $notif_user = array();
        $noti_count = 0;

        foreach($notif as $k => $v) {
            $notif_user[] = Notification_N::Get_Notification_All_Info($v->ntf_id);
            if(!$v->seen) $noti_count++;
        }

        $data = [$notif, $notif_user, $noti_count];
        return $data;
    }
}