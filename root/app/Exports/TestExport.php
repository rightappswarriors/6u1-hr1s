<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use DB;
use Excel;


class TestExport/* implements WithDrawings*/
{

    // public function drawings()
    // {
    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('This is my logo');
    //     $drawing->setPath(url('root/storage/app/public/profile_images/profile_user2.jpg'));
    //     $drawing->setHeight(90);
    //     $drawing->setCoordinates('B3');

    //     return $drawing;
    // }

    /*
    * Put this prototype on hold
    */

    public static function export()
    {
        Excel::create('New file', function($excel) {
            $excel->sheet('New sheet', function($sheet) {
                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue('data1');
                });
                // $sheet->loadView($blade, array('data' => $data));
            });
        })->export('xls');
    }
}