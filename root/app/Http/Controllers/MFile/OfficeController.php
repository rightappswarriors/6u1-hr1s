<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Employee;
use Office;

class OfficeController extends Controller
{
	public function __construct()
    {
        
    }
    public function view()
    {
        $SQLOffice = "SELECT * FROM (SELECT * FROM rssys.m08 WHERE active IS TRUE ORDER BY cc_desc ASC) ofc LEFT JOIN (SELECT hp_id, hp_type, CAST(cc_id AS integer) AS ofc_id, withpay, hp_pct, hp_amount FROM hris.hr_hazardpay) hp ON ofc.cc_id = hp.ofc_id ORDER BY cc_desc ASC, cc_id ASC";
        $this->office = DB::select($SQLOffice);;
    	return view('pages.mfile.office', ['office' => $this->office]);
    }
    /**
    * @param txt_code
    * @param txt_name
    * @param chk_hazrd
    */
    public function add(Request $r)
    {
        # Validate
        if ($this->ifExists($r)) {
            Core::Set_Alert('danger', "Office ID Already Used. Please try again.");
            return back();
        } else {
            # Save to rssys.m08
            $to_ofc = ['cc_code'=>$r->txt_code , 'cc_desc' => $r->txt_name];
            $new_entry_id = null;
            try {
                DB::table(Office::$tbl_name)->insert($to_ofc);
                $new_entry_id = DB::table(Office::$tbl_name)->where('cc_code', $r->txt_code)->where('cc_desc', $r->txt_name)->first()->cc_id;
            } catch (\Illuminate\Database\QueryException $e) {
                Core::Set_Alert('danger', $e->getMessage());
                return back();
            }
            # Save to hris.hr_hazardpay
            $chk_hazrd = false;
            if (isset($r->chk_hazrd)) {
                $chk_hazrd = true;
            }
            $to_hazard = ['hp_type' => 'office', 'cc_id' => $new_entry_id, 'withpay' => $chk_hazrd, 'hp_pct' => 25]; # Note: Hazard Pay Type has the ff. types: office - for office, individual - for single person/employee
            try {
                DB::table('hr_hazardpay')->insert($to_hazard);
                Core::Set_Alert('success', 'Successfully added new Office.');
                return back();
            } catch (\Exception $e) {
                Core::Set_Alert('danger', $e->getMessage());
                    return back();
            }
        }
    }
    /**
    * @param txt_id
    * @param txt_code
    * @param txt_name
    * @param txt_hazrd
    * @param chk_hazrd
    */
    public function update(Request $r)
    {
    	# Validate
        if ($this->ifExists($r)) {
            Core::Set_Alert('danger', "Office ID Already Used. Please try again.");
            return back();
        } else {
            # Update rssys.m08
            try {
                $data = ['cc_desc' => $r->txt_name];
                DB::table(Office::$tbl_name)->where('cc_id', $r->txt_id)->update($data);
                Core::Set_Alert('success', 'Successfully modified a Office.');

            } catch (\Illuminate\Database\QueryException $e) {
                Core::Set_Alert('danger', $e->getMessage());
            }

            # Update hris.hr_hazardpay
            $chk_hazrd = false;
            if (isset($r->chk_hazrd)) {
                $chk_hazrd = true;
            }
            $rec_hp = DB::table('hr_hazardpay')->where('hp_id', $r->txt_hazrd)->first();
            try {
                if ($rec_hp!=null) {
                    DB::table('hr_hazardpay')->where('hp_id', $r->txt_hazrd)->update(['withpay' => $chk_hazrd]);
                } else {
                    DB::table('hr_hazardpay')->insert(['hp_type' => 'office', 'cc_id' => $r->txt_id, 'withpay' => $chk_hazrd, 'hp_pct' => 25]);
                }
            } catch (\Exception $e) {
                Core::Set_Alert('danger', $e->getMessage());
                return back();
            }
            return back();
        }
    }
    public function delete(Request $r)
    {
    	$data = ['active' => FALSE];
        try {
            if (DB::table('hr_hazardpay')->where('cc_id', $r->txt_id)->first() != null) {
                DB::table('hr_hazardpay')->where('cc_id', $r->txt_id)->update($data);
            }
            if (DB::table(Office::$tbl_name)->where(Office::$pk, $r->txt_code)->update($data)) {
                Core::Set_Alert('success', 'Successfully remove a Office.');
            } else {
                Core::Set_Alert('danger', 'Unable to delete office.');
            }
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
    public function ifExists(Request $r)
    {
        if (DB::table(Office::$tbl_name)->where(Office::$id, '!=', $r->txt_id)->where(Office::$pk, '=', $r->txt_code)->first()) {
            return true;
        }
        return false;
    }
    public function getEmployees(Request $r)
    {
        return Office::OfficeEmployees($r->ofc_id);
    }
    public function getEmployees_byEmpStat(Request $r)
    {
        return Office::OfficeEmployees_byEmpStat($r->ofc_id, $r->emp_status);
    }

    public function isGeneratedDTR(Request $r)
    {
        // return $r->all();
        return [Employee::isGeneratedOnDTR($r->empid, Date('Y-m-d',strtotime($r->monthFrom)), Date('Y-m-d',strtotime($r->monthTo)), $r->gtype),DB::table('hr_dtr_sum_employees')->join('hr_dtr_sum_hdr','hr_dtr_sum_hdr.code','hr_dtr_sum_employees.dtr_sum_id')->where([['hr_dtr_sum_hdr.empid',$r->empid],['hr_dtr_sum_hdr.date_from',$r->monthFrom],['hr_dtr_sum_hdr.date_to',$r->monthTo]])->first()];
    }
}
