<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Employee;
use ErrorCode;
use RATA;
use Position;
use Core;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - OEMC
|
| 00001 - find
| 00002 - employee
| 00003 - generate
| 00004 - monthlyra
| 00005 - monthlyta
| 00006 - deduc1
| 00007 - deduc2
| 00008 - get_total_deduction
| 00009 - get_net_amount
| 00010 - amount_paid
| 00011 - absence_w_pay
| 
|--------------------------------------------------------------------------
*/

class OtherEarningsMainController extends Controller
{
    

    public function __construct()
    {
    	$this->ghistory = RATA::Load_RATA();
        $this->employees = Employee::Load_Employees();
    }

    public function view()
    {
    	$data = [$this->ghistory, $this->employees];
        // dd($data[1]);
    	return view('pages.payroll.other_earnings_main', compact('data'));
    }

    public function find(Request $r)
    {
        try {
            $data = DB::table(RATA::$tbl_name)->where('date', $r->date_queried)->orderBy('rata_id', 'ASC')->get();
            for($i=0; $i<count($data); $i++) {
                $data[$i]->count = $i+1;
                $data[$i]->name = Employee::Name($data[$i]->empid);
                // $data[$i]->position_readable = Position::Get_Position(Employee::GetEmployee($data[$i]->empid)->positions);
                $data[$i]->position_readable = Employee::GetJobTitle($data[$i]->empid);
                $data[$i]->rate_type = Employee::GetEmployee($data[$i]->empid)->rate_type;
                $data[$i]->pay_rate = Employee::GetEmployee($data[$i]->empid)->pay_rate;
            }
            return $data;
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00001', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function employee(Request $r)
    {
        // dd($r->all());
        try {
            $data = Employee::Load_Employees();
            for($i=0; $i<count($data); $i++) {
                $data[$i]->count = $i+1;
                $data[$i]->name = Employee::Name($data[$i]->empid);
                // $data[$i]->position_readable = Position::Get_Position(Employee::GetEmployee($data[$i]->empid)->positions);
                $data[$i]->position_readable = Employee::GetJobTitle($data[$i]->empid);

            }
            return $data;
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00002', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function generate(Request $r)
    {
        try {
            // dd($r->all());
            $data = $r->empid;
            for($i=0; $i<count($data); $i++) {
                $latest_id = Core::get_nextincrementlimitchar(Core::getm99(RATA::$pk), 8);
                $new_data = ['rata_id'=>$latest_id, 'date'=>$r->date, 'empid'=>$data[$i]['empid']];
                DB::table(RATA::$tbl_name)->insert($new_data);
                Core::updatem99(RATA::$pk, $latest_id);
            }
            
            return $data;
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00003', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function monthlyra(Request $r)
    {
        try {
            $data = ['monthly_ra'=>$r->value];
            $result = DB::table(RATA::$tbl_name)->where(RATA::$pk, $r->rata_id)->update($data);
            return ($result)?"Ok":"Failed";
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00004', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function monthlyta(Request $r)
    {
        try {
            $data = ['monthly_ta'=>$r->value];
            $result = DB::table(RATA::$tbl_name)->where(RATA::$pk, $r->rata_id)->update($data);
            return ($result)?"Ok":"Failed";
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00005', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function deduc1(Request $r)
    {
        try {
            $data = ['deduc_1'=>$r->value];
            $result = DB::table(RATA::$tbl_name)->where(RATA::$pk, $r->rata_id)->update($data);
            return ($result)?"Ok":"Failed";
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00006', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function deduc2(Request $r)
    {
        try {
            $data = ['deduc_2'=>$r->value];
            $result = DB::table(RATA::$tbl_name)->where(RATA::$pk, $r->rata_id)->update($data);
            return ($result)?"Ok":"Failed";
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00007', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function get_total_deduction(Request $r)
    {
        try {

            $data = DB::table(RATA::$tbl_name)->select('rata_id', 'deduc_1', 'deduc_2'/*, 'total_deduc'*/)->where(RATA::$pk, $r->rata_id)->get();

            $new_data = ['total_deduc' => floatval($data[0]->deduc_1) + floatval($data[0]->deduc_2)];
            DB::table(RATA::$tbl_name)->where(RATA::$pk, $r->rata_id)->update($new_data);
            return $data;
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00008', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function get_net_amount(Request $r)
    {
        try {

            $data = DB::table(RATA::$tbl_name)->select('rata_id', 'monthly_ra', 'monthly_ta'/*, 'net_amount_received'*/)->where(RATA::$pk, $r->rata_id)->get();

            $new_data = ['net_amount_received' => floatval($data[0]->monthly_ra) + floatval($data[0]->monthly_ta)];
            DB::table(RATA::$tbl_name)->where(RATA::$pk, $r->rata_id)->update($new_data);
            return $data;
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00009', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function amount_paid(Request $r)
    {
        try {
            $data = ['amount_paid'=>$r->value];
            $result = DB::table(RATA::$tbl_name)->where(RATA::$pk, $r->rata_id)->update($data);
            return ($result)?"Ok":"Failed";
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00010', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    public function absence_w_pay(Request $r)
    {
        try {
            $data = ['absent_wo_pay'=>$r->value];
            $result = DB::table(RATA::$tbl_name)->where(RATA::$pk, $r->rata_id)->update($data);
            return ($result)?"Ok":"Failed";
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'OtherEarningsMainController', '00011', $e->getMessage());
            Core::Set_Alert('danger', 'See latest Error Log: '.$e->getMessage());
        }
    }

    ///////////////////////// -- PRINT -- /////////////////////////

    public function print_view($month)
    {

        $employee = DB::table(RATA::$tbl_name)->where('date', $month)->orderBy('rata_id', 'ASC')->get();
        if(count($employee) > 0) {
            for($i=0; $i<count($employee); $i++) {
                    $employee[$i]->count = $i+1;
                    $employee[$i]->name = Employee::Name($employee[$i]->empid);
                    // $employee[$i]->position_readable = Position::Get_Position(Employee::GetEmployee($employee[$i]->empid)->positions);
                    $employee[$i]->position_readable = Employee::GetJobTitle($employee[$i]->empid);
                    $employee[$i]->rate_type = Employee::GetEmployee($employee[$i]->empid)->rate_type;
                    $employee[$i]->pay_rate = Employee::GetEmployee($employee[$i]->empid)->pay_rate;
                }

            $data = [$employee];
            // dd($data[0]);
            return view('pages.payroll.other_earnings_print', compact('data'));
        } else {
            $data = [$this->ghistory, $this->employees];
            return redirect()->route('oehome');
        }  
    }
}
