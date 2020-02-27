<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Notification_N;
use Core;

class NotificationSettingsController extends Controller
{

    public function view()
    {
        // $object = [];
        // $object['date'] = Date('Y-m-d');
        // $object['time'] = Date('G:i:s');
        // $object['url'] = '/timekeeping/leaves-entry-apply';
        // dd(Core::convertObjectToRequestClass($object)->all());
    	$data = [];
        // dd(Notification_N::sendNotificationSingleFromDB(1,'ADMIN'));
    	return view('pages.settings.notification_settings', compact('data'));
    }

    public function send(Request $r)
    {
        $datetime = $r->date.' '.date('H:i:s', strtotime($r->time));
        $result = Notification_N::Send($r->cbo, $r->content, $r->title, $datetime,($r->url ?? ''));
        // return $r->all();
        // $result = Notification_N::Send($r->groups, $r->content, $r->subject);
        return $result;
    }
}