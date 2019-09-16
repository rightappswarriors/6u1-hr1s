<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class TimekeepingSettingsController extends Controller
{
	public function __construct()
	{
		$this->data = DB::table('hris.m99')->first();
	}

    public function view()
    {
    	$data = [   
    		$this->data
    	];

        return view('pages.settings.timekeeping_settings', compact('data'));
    }

    public function update(Request $r, $col)
    {
    	try {
    		$fy = DB::table('hris.m99')->first()->fy;

    		$data = [
    			$col => $r->val,
    		];

    		$d = DB::table('hris.m99')->where('fy', $fy)->update($data);

    		return "Ok";
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }
}