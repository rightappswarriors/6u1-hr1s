<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use Employee;
use X07;

class GroupRightsSettingsController extends Controller
{
    public function __construct()
    {
        $this->employees = Employee::Load_Employees();
        $this->ag = X07::Load_X07();
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
        $data = [$this->employees, $groups, 'nogr' => $a];
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

    public function AddRights()
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
            Core::Set_Alert('success', 'Default group rights are added to new groups.');
            return back();
        } catch (\Exception $e) {
            return "error";
        }
    }

}