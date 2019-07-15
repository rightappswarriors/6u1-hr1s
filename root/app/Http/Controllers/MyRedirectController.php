<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MyRedirectController extends Controller
{
    // Clear Alert and Redirect to Route
    public function clearAlert($route)
    {
    	return redirect()->route($route);
    }

    // Redirect to Maintenance Page
    public function redirect($page)
    {
        return view('pages.maintenance_page', compact('page'));
    }

    // Redirect if Page Not Found
    public function FindPage()
    {
        $page = 3;
        return view('pages.maintenance_page', compact('page'));
    }

    // Test
    public function test($controller, $page, Request $data)
    {
    	return view($page, $data);
    }
}
