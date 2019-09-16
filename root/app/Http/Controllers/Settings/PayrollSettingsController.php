<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;

class PayrollSettingsController extends Controller
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

        return view('pages.settings.payroll_settings', compact('data'));
    }
}