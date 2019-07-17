<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class OtherDeductionMainController extends Controller
{
    

    public function __construct()
    {
    }

    public function view()
    {
    	$data = [];
        // dd($data[1]);
    	return view('pages.payroll.other_deductions_main', compact('data'));
    }
}
