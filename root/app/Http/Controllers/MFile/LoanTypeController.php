<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use LoanType;
use DB;

class LoanTypeController extends Controller
{
	public function __construct()
    {
        $SQLLoanType = "SELECT * from hr_loan_type WHERE COALESCE(cancel,cancel,'')<>'Y'";
        $this->depart = DB::select($SQLLoanType);
    }
    public function view()
    {
    	// return dd($this->depart);
    	return view('pages.mfile.loantype', ['loantype' => $this->depart]);
    }
    public function add(Request $r) 
    {
        // return dd($r->all());
    	$data = ['code'=>$r->txt_code , 'description' => $r->txt_name];
    	try {

    		DB::table(LoanType::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Loan Type.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
    	$data = ['description' => $r->txt_name];
    	try {

    		DB::table(LoanType::$tbl_name)->where(LoanType::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Loan Type.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function delete(Request $r)
    {
    	$data = ['cancel' => 'Y'];
        try {

            DB::table(LoanType::$tbl_name)->where(LoanType::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully modified a Loan Type.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
