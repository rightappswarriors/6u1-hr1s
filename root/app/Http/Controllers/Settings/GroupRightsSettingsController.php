<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use Employee;
use X05S;
use X07;
use Auth;
use X05;

class GroupRightsSettingsController extends Controller
{
    public function __construct()
    {
        $this->employees = Employee::Load_Employees();
        // $this->ag = X07::Load_X07();
        $this->ag = X07::Load_ALL_X07();
    }

    public function viewModules()
    {
        $data = X05::Load_X05();
        
        return view('pages.settings.group_rights_crud', compact('data'));
    }

    public function viewUserGroup()
    {
        $groups = $this->ag;
        $modules = DB::table('x05')->get();
        $a = 0;
        foreach ($groups as $group) {
            foreach ($modules as $module) {
                if (DB::table('x06')->where('grp_id', $group->grp_id)->where('mod_id', $module->mod_id)->first()==null) {
                    $a++;
                }
            }
        }
        $data = [$this->employees, $groups, 'nogr' => $a, /*X05S::Load_All()*/X05::Load_X05()];
    	return view('pages.settings.group_rights_settings', compact('data'));
    }

    public function LoadLevel1()
    {
        try {
            return DB::table('x05')->where('level',1)->get();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function LoadLevel2(Request $r)
    {
        try {
            return DB::table('x05')->where('level',1)->where('plevel1', $r->id)->get();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function AddRights($alertCore = true)
    {
        try {
            $groups = $this->ag;
            $modules = DB::table('x05')->get();
            foreach ($groups as $group) {
                foreach ($modules as $module) {
                    if (DB::table('x06')->where('grp_id', $group->grp_id)->where('mod_id', $module->mod_id)->first()==null) {
                        DB::table('x06')->insert([
                            'grp_id' => $group->grp_id,
                            'mod_id' => $module->mod_id,
                            'restrict' => 0,
                            'add' => 0,
                            'upd' => 0,
                            'cancel' => 0,
                            'print' => 0,
                        ]);
                    }
                }
            }
            if($alertCore){
                Core::Set_Alert('success', 'Default group rights are added to new groups.');
            }
            return back();
        } catch (\Exception $e) {
            return "error";
        }
    }

    ////////////////////////////////////////////////// NEW //////////////////////////////////////////////////

    public function EditRights(Request $r)
    {
        try {
            // $res = implode(', ', $r->restrictions);
            // $data = ['restrictions' => $res];
            $data = ['restrict' => 0];
            // return DB::table('x07')->where('grp_id', $r->restriction_grpid)->update($data);
            $query = DB::table('x06')->where('grp_id', $r->restriction_grpid);
            if(isset($r->restrictions) && !empty($r->restrictions)){
                $data = ['restrict' => 1];
                $query = $query->whereIn('mod_id',$r->restrictions);
            }
            return $query->update($data);
        } catch (Exception $e) {
            return "error";
        }
    }

    public function AddRights_New(Request $r)
    {
        try {
            $latest_entry = DB::table('x07')->get();
            foreach($latest_entry as $k => $v) {
                if($v->grp_desc == strtoupper($r->txt_grp)) {
                    return "Group already exist";
                }
            }

            $latest_id = Core::get_nextincrementlimitchar(Core::getm99('grp_id'), 3);
            $flag = DB::table('x07')->insert(['grp_id'=>$latest_id, 'grp_desc'=>$r->txt_grp]);
            if(!$flag) 
                return "query error";
            else {
                Core::updatem99('grp_id', $latest_id);
                return "okay";
            }
        } catch (Exception $e) {
            return "error";
        }
    }

    public function AddGroupRights_New(Request $r)
    {
        if(DB::table('x05')->where('mod_id',$r->txt_grp)->exists()){
            return 'ID already Exist. Please try another ID';
        }
        $url = ltrim(rtrim($r->url_grp,'/'), '/');
        if(DB::table('x05')->insert(['mod_id' => $r->txt_grp, 'grp_desc' => $r->id_grp, 'path' => $url])){
            self::AddRights(false);
            Core::Set_Alert('success', 'Added new group right successfully.');
            return back();
        }
    }

    public function EditGroupRights_New(Request $r){
        $url = ltrim(rtrim($r->url_grp,'/'), '/');
        if(DB::table('x05')->where('mod_id',$r->txt_grp)->update(['grp_desc' => $r->id_grp, 'path' => $url])){
            Core::Set_Alert('success', 'Edited successfully.');
            return back();
        }
    }

    public function DeleteGroupRights_New(Request $r){
        if(DB::table('x05')->where('mod_id',$r->hidden_txt_id)->delete() && DB::table('x06')->where('mod_id',$r->hidden_txt_id)->delete()){
            return 'okay';
            return back();
        }
    }

    public function DeleteRights(Request $r)
    {
        try {
            return DB::table('x07')->where('grp_id', $r->hidden_txt_id)->update(['cancel'=> true]) && DB::table('x06')->where('grp_id', $r->hidden_txt_id)->delete();
        } catch (Exception $e) {
            return "error";
        }
    }
}