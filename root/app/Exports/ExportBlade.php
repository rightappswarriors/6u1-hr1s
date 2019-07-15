<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class ExportBlade implements ShouldAutoSize, FromView
{

    private $data;

    public function __construct($blade, $data)
    {
    	$this->blade = $blade;
        $this->data = $data;
    }

    public function view(): View
    {
        return view($this->blade, ['data'=>$this->data]);
    }
}