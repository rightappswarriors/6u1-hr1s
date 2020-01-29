<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use EmployeeDeduction;
use OtherDeductions;
use Employee;
use Payroll;
use Office;
class OtherDeductionMainController extends Controller
{
    

    public function __construct()
    {
        $this->list = OtherDeductions::Load_List();
        $this->data = EmployeeDeduction::Load_Deductions();
        $this->office = Office::get_all();
    }

    public function view()
    {
    	$data = [$this->list, $this->data, $this->office];
        // dd($data);
    	return view('pages.payroll.other_deductions_main', compact('data'));
    }

    public function add(Request $r)
    {
        if(EmployeeDeduction::Add_Deduction($r)) {
            Core::Set_Alert('success', 'Successfully added new Other Deduction.');
            return back();
        } else {
            Core::Set_Alert('danger', 'Entry not added.');
            return back();
        }
    }

    public function update(Request $r)
    {
        if(EmployeeDeduction::Update_Deduction($r)) {
            Core::Set_Alert('success', 'Successfully updated new Other Deduction.');
            return back();
        } else {
            Core::Set_Alert('danger', 'Entry not updated.');
            return back();
        }
    }

    public function delete(Request $r)
    {

        if(EmployeeDeduction::Delete_Deduction($r)) {
            Core::Set_Alert('success', 'Successfully deleted new Other Deduction.');
            return back();
        } else {
            Core::Set_Alert('danger', 'Entry not deleted.');
            return back();
        }
    }

    public function find(Request $r)
    {
        // return dd($r->all());
        return EmployeeDeduction::Find_Deduction($r->month, $r->year, $r->period, $r->ofc);
    }

    public function find2(Request $r)
    {
        return EmployeeDeduction::Find_Deduction2($r->id);
    }

}
