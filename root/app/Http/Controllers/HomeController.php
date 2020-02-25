<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Core;
use Account;
use Session;
use DTR;
use Storage;
use DB;
use Holiday;
use Leave;

class HomeController extends Controller
{
    //
    public function __construct()
    {

    }

    public function view()
    {
        // dd(json_encode(DB::table('m99')->first()));
        // dd(in_array('masterfile', explode(', ', Session::get('_user')[0]->restriction)));
        try {

            $dtr = DTR::GetTimedInToday(); /*dd($dtr[0]->status);*/
            $dtr_timeout = DTR::GetTimedOutToday(); 
            $upHolidy = Holiday::GetUpcomingHoliday();
            $count_in = count(DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '1')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->take(6)->get());
            $count_out = count(DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '0')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->take(6)->get());
            $count_sum = $count_in - $count_out;
            $whereForLeave = (Core::getSessionData()[0]->grp_id == 001 ? [] :[['empid',Core::getSessionData()[0]->uid]]);
            $leaveList = DB::table('hris.hr_leaves_approval')->leftJoin('hris.hr_employee','hr_employee.empid','hr_leaves_approval.empid')->leftJoin('hris.hr_leave_type','hr_leave_type.code','hr_leaves_approval.leave_type')->where($whereForLeave)->select('hr_leave_type.description','hr_leaves_approval.*','hr_employee.lastname','hr_employee.firstname')->get();
            $count_leave = Leave::GetTodayLeave();

            if (count($dtr)!=0) {
                for ($i=0; $i < count($dtr); $i++) { 
                    $time_log = explode(",", $dtr[$i]->time_log);
                    if (count($time_log)==2) {
                        unset($dtr[$i]);
                    } else {
                        if ($dtr[$i]->status=="O") {
                            unset($dtr[$i]);
                        }
                    }
                }
            }
            $data = [$dtr, $upHolidy, $count_in, $count_leave, $dtr_timeout, $count_sum,$leaveList];
            // dd($data);
            if (Account::UAG()=="ADMINISTRATORS") {
                return view($this->admin(), compact('data'));
            }
            return view($this->user(), compact('data'));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function user()
    {
    	return "index";
    }

    public function admin()

    {
    	return "index";
    }

    public function loadSideNav()
    {
        $sql = "SELECT * FROM hris.x05 WHERE level = 1";
    }

    public function loadSetting()
    {
        $data = [Account::GET_IMAGE(Account::CURRENT()->uid)];
        return view('pages.settings.home_settings', compact('data'));
    }

    public function uploadImage(Request $r)
    {
        $this->validate($r, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $uid = Account::CURRENT()->uid;
        $imageName = time().'_avatar_'.$uid.'.'.request()->image->getClientOriginalExtension();

        try {

            if(Account::HAS_IMAGE($uid)) {
                unlink('root/storage/app/public/profile_images/'.Account::GET_IMAGE($uid));
            }

            DB::table('x08')->where('uid', $uid)->update(['img'=>$imageName]);

            request()->image->move('root/storage/app/public/profile_images/', $imageName);
            Core::Set_Alert('success', 'Successfully changed profile avatar.');
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
