<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Notification_N;

class NotificationSettingsController extends Controller
{

    public function view()
    {
    	$data = [];
    	return view('pages.settings.notification_settings', compact('data'));
    }

    public function send(Request $r)
    {
        $datetime = $r->date.' '.date('H:i:s', strtotime($r->time));
        $result = Notification_N::Send($r->cbo, $r->content, $r->title, $datetime);
        // return $r->all();
        // $result = Notification_N::Send($r->groups, $r->content, $r->subject);
        return $result;
    }
}