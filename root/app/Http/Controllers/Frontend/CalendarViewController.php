<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Holiday;

class CalendarViewController extends Controller
{
    

    public function __construct()
    {
    	$this->data = Holiday::Load_Holidays();
    }


    public function view()
    {
        $data = $this->data;
        return view('pages.frontend.calendar_view', compact('data'));
    }
}