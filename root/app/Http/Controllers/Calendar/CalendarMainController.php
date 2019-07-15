<?php

namespace App\Http\Controllers\Calendar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use Holiday;
use ErrorCode;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - CMC
|
| 00000 - add
| 00001 - update
| 00002 - update
| 00003 - deleteA
| 00004 - restore
| 
|--------------------------------------------------------------------------
*/


class CalendarMainController extends Controller
{
    

    public function __construct()
    {
    	$this->data = Holiday::Load_Holidays();
        $this->data_deleted = Holiday::Load_Holidays_Deleted();
    }

    public function get_deleted() {
        return $this->data_deleted;
    }


    public function view()
    {
        $data = $this->data;
        // dd($data);
        return view('pages.calendar.calendar_main', compact('data'));
    }

    public function add(Request $r)
    {
        $r->txt_type = strtoupper($r->txt_type);

        $data = ['date_holiday'=>$r->txt_date, 'description'=>$r->txt_desc, 'holiday_type'=>$r->txt_type];
        try {
            DB::table(Holiday::$tbl_name)->insert($data);
            Core::Set_Alert('success', 'Successfully added new Holiday.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'CalendarMainController', '00000', $e->getMessage());
            return back();
        }
    }

    public function update(Request $r) {
        $r->txt_type = strtoupper($r->txt_type);
        if(array_key_exists('delBtn', $r->all())) { // check if delete button is clicked
            $r->txt_id = explode('*', $r->txt_id)[0];
            $data = ['date_holiday'=>$r->txt_date, 'description'=>$r->txt_desc, 'holiday_type'=>$r->txt_type];
            try {
                DB::table(Holiday::$tbl_name)->where(Holiday::$pk, $r->txt_id)->update(['cancel'=>'Y']);
                Core::Set_Alert('success', 'Successfully deleted a Holiday.');
                return back();

            } catch (\Illuminate\Database\QueryException $e) {
                Core::Set_Alert('danger', $e->getMessage());
                ErrorCode::Generate('controller', 'CalendarMainController', '00002', $e->getMessage());
                return back();
            }
        } else { // if save button is clicked
            $r->txt_id = explode('*', $r->txt_id)[0];
            $data = ['date_holiday'=>$r->txt_date, 'description'=>$r->txt_desc, 'holiday_type'=>$r->txt_type];
            try {
                DB::table(Holiday::$tbl_name)->where(Holiday::$pk, $r->txt_id)->update($data);
                Core::Set_Alert('success', 'Successfully edited new Holiday.');
                return back();
            } catch (\Illuminate\Database\QueryException $e) {
                Core::Set_Alert('danger', $e->getMessage());
                ErrorCode::Generate('controller', 'CalendarMainController', '00001', $e->getMessage());
                return back();
            }
        }    
    }

    public function deleteA($id) {
        try {
            DB::table(Holiday::$tbl_name)->where(Holiday::$pk, $id)->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'CalendarMainController', '00003', $e->getMessage());
            return back();
        }
    }

    public function restore($id) {
        try {
            DB::table(Holiday::$tbl_name)->where(Holiday::$pk, $id)->update(['cancel'=>null]);
        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'CalendarMainController', '00004', $e->getMessage());
            return back();
        }
    }
}