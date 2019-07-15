<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Artisan;

class WebsiteController extends Controller
{
    /*
	| Switch Mode:
	| on = Maintenance Mode is Active
	| off = Maintenance Moe is Dissabled
	*/

    // Execute maintenance mode (Whole website)
    public function MaintenanceMode($var)
    {
    	switch ($var) {
    		case 'on':
    			Artisan::call('down');
    			return redirect()->route('redirect', ['page' => 4]);
    			break;

    		case 'off':
    			Artisan::call('up');
    			return redirect()->route('redirect', ['page' => 5]);
    			break;
    		
    		default:
    			Artisan::call('up');
    			return redirect()->route('redirect', ['page' => 4]);
    			break;
    	}
    }

    // Execute maintenance mode (For Users)
    public function UserMaintenanceMode($var)
    {
    	switch ($var) {
    		case 'on':
    			# code...
    			break;

    		case 'off':
    			# code...
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    }

    public function Restricted()
    {
        return redirect()->route('redirect', ['page' => 2]);
    }
}
