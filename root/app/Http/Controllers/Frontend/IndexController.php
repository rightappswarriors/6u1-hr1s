<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;

class IndexController extends Controller
{
    

    public function __construct()
    {
    	// $this->data = Holiday::Load_Holidays();
    }


    public function view()
    {
        if (Session::exists('_user')) {
            return redirect('/home');
        }
        return view('pages.frontend.index'/*, compact('data')*/);
    }
}