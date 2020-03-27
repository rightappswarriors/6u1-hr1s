<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use DB;
use Core;
use Employee;
use X05S;
use X07;
use X08;
use Session;
use Account;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - USC
|
| 00000 - add
| 00001 - update
| 00002 - delete
| 
|--------------------------------------------------------------------------
*/

class UserSettingsController extends Controller
{
    public function __construct()
    {
    	// $this->ghistory = DB::table('hr_leaves')->where('cancel', '=', null)->orderBy('d_filed', 'DESC')->orderBy('leave_from', 'DESC')->get();
        $this->employees = Employee::Load_Employees();
        $this->x08 = X08::Load_X08();
        $this->x05_sub = X05S::Load_All();
    }

    public function view()
    {
        // if(Session::get('_user')[0]->grp_id == "001") {
            $data = [$this->x08, $this->employees, $this->x05_sub];
            return view('pages.settings.user_settings', compact('data'));
        // } else {
        //     Core::Set_Alert('warning', 'It seems like you do not have the rights to open this page. Ask your administrator for access.');
        //     return back();
        // }
    }

    public function add(Request $r) 
    {

        // $restrictions = implode(', ', $r->restrictions);

        $r->txt_name = strtoupper($r->txt_name);
        $r->txt_user = strtoupper($r->txt_user);

        // Checks if the username already exists
        $users = DB::table(X08::$tbl_name)->select('uid')->get();
        // foreach($users as $key => $value) {
        //     if($value->uid == $r->txt_user) {
        //         Core::Set_Alert('warning', 'Username already exist. Please select a different username.');
        //         return back();
        //     }
        // }
        // reworked checking, too slow to validate
        if(DB::table(X08::$tbl_name)->where('uid',$r->txt_user)->exists()){
             Core::Set_Alert('warning', 'Username already exist. Please select a different username.');
            return back();
        }

        if ($this->secondary_validation($r->txt_name)) {
            Core::Set_Alert('warning', 'Username already exist. Please select a different username.');
            return back();
        }

        if($r->txt_pass != $r->txt_pass_r) {
            Core::Set_Alert('danger', 'Password do not match.');
            return back();
        } else {
            $group = X07::GetGroup($r->cbo_grp)->grp_desc;
            
            $data = ['uid'=>$r->txt_user, 'opr_name'=>$r->txt_name, 'pwd'=>$r->txt_pass, 'grp_id'=>$r->cbo_grp, 'd_code'=>$group, 'approve_disc'=>'y'/*, 'restriction'=>$restrictions*/, 'empid' => $r->cbo_emp];
            
            try {

                DB::table(X08::$tbl_name)->insert($data);
                Core::Set_Alert('success', 'Successfully added new User.');
                return back();

            } catch (\Illuminate\Database\QueryException $e) {
                Core::Set_Alert('danger', $e->getMessage());
                ErrorCode::Generate('controller', 'UserSettingsController', '00000', $e->getMessage());
                return back();
            } 
        } 
    }

    public function secondary_validation($username)
    {
        $ac = new AuthController;
        $u = $ac->override();
        if ($u->uid == $username) {
            return true;
        }
        return false;
    }

    public function update(Request $r)
    {
        $restrictions = implode(', ', $r->restrictions);

        if($r->txt_pass != $r->txt_pass_r) {
            Core::Set_Alert('danger', 'Password do not match.');
            return back();
        } else {
            $group = X07::GetGroup($r->cbo_grp)->grp_desc;
            $r->txt_name = strtoupper($r->txt_name);
            $r->txt_user = strtoupper($r->txt_user);
            $data = ['uid'=>$r->txt_user, 'opr_name'=>$r->txt_name, 'pwd'=>$r->txt_pass, 'grp_id'=>$r->cbo_grp, 'd_code'=>$group, 'restriction'=>$restrictions, 'empid' => $r->cbo_emp];
            try {
                DB::table(X08::$tbl_name)->where(X08::$pk, $r->txt_user)->update($data);
                Core::Set_Alert('success', 'Successfully modified a User.');
                return back();
            } catch (\Illuminate\Database\QueryException $e) {
                Core::Set_Alert('danger', $e->getMessage());
                ErrorCode::Generate('controller', 'UserSettingsController', '00001', $e->getMessage());
                return back();
            }
        }
    }

    public function delete(Request $r) 
    {
        try {
            $data = ['approve_disc'=>"n"];
            DB::table(X08::$tbl_name)->where(X08::$pk, $r->txt_user)->update($data);
            Core::Set_Alert('success', 'Successfully removed a User.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'UserSettingsController', '00002', $e->getMessage());
            return back();
        }
    }
}