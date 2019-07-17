<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use DB;
use Core;
use PayrollPeriod;
use Employee;
use Timelog;
use Account;
use Schema;
use Session;
use ErrorCode;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - SSC
|
| 00000 - add
| 00001 - update
| 
|--------------------------------------------------------------------------
*/

class SystemSettingsController extends Controller
{

    public function __construct()
    {
    	$this->ghistory = DB::table('m99')->first();
        $this->size = DB::select('select * from INFORMATION_SCHEMA.COLUMNS where table_name = \'m99\' AND table_schema = \'hris\';'); 
    }

    public function view()
    {
        // if(Session::get('_user')[0]->grp_id == "001") {
            $data = (array) [$this->ghistory][0];
            $size = $this->size;

            // dd($data);

            return view('pages.settings.system_settings', compact('data', 'size'));
        // } else {
        //     Core::Set_Alert('warning', 'It seems like you do not have the rights to open this page. Ask your administrator for access.');
        //     return back();
        // }
    }

    public function update(Request $r)
    {
        try {
            $data = [$r->col=>$r->val];
            // DB::table('m99')->where('par_code', '=', '001')->update($data);    uncomment
            // Core::Set_Alert('success', 'Successfully added new Loan Entry.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Core::Set_Alert('danger', $e->getMessage());
            // ErrorCode::Generate('controller', 'SystemSettingsController', '00001', $e->getMessage());
            // return back();
        }
    }

    public function add(Request $r)
    {
        try {
            // Schema::table('m99', function (Blueprint $table) use ($r) {
            //     $table->char($r->txt_name, $r->text_len)->nullable($value = true);
            // }); // Creates a column to the table m99

            // $data = [$r->txt_name=>$r->txt_val];
            // DB::table('m99')->where('par_code', '=', '001')->update($data); // Inserts data to the new column
            // Core::Set_Alert('success', 'Successfully added new System Setting.');
            Core::Set_Alert('info', 'Function Currently Not Available.');
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'SystemSettingsController', '00000', $e->getMessage());
            return back();
        }
    }
}