<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;
use Excel;

class Export2_1 extends Model
{
    public static function exportBlade($blade, $data)
    {
		Excel::create('New file', function($excel) use($blade, $data) {
		    $excel->sheet('New sheet', function($sheet) use($blade, $data) {
		        $sheet->loadView($blade, array('data' => $data));
		    });
		})->export('xls');
    }
    
}
