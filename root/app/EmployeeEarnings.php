<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;
use OtherEarnings;
use Office;

class EmployeeEarnings extends Model
{
	public static $tbl_name = "hr_earning_entry";
    public static $pk = "entcode";

    public static function Load_Earnings()
    {
    	return DB::table(self::$tbl_name)->get();
    }

    public static function Add_Earnings($r)
    {
    	if($r->txt_amount < 1) return false;

    	try {
            $important_obj = Payroll::PayrollPeriod2($r->cbo_month, $r->cbo_period.'D', $r->cbo_year);

            $latest_id = Core::get_nextincrementlimitchar(Core::getm99('entcode'), 8);

            $data = [
                'entcode' => $latest_id,
                'payroll_period'=>$r->cbo_period,
                'emp_no'=>$r->cbo_employee,
                'emp_name'=>Employee::Name($r->cbo_employee),
                'earning_code'=>$r->cbo_type,
                'amount'=>$r->txt_amount,
                'date_from'=>$important_obj->from,
                'date_to'=>$important_obj->to,
                'month'=>$r->cbo_month,
                'year'=>$r->cbo_year,
            ];

            if(DB::table(EmployeeEarnings::$tbl_name)->insert($data)) {
                Core::updatem99('entcode', $latest_id);
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return $e->getMessage();
        }
    }

    public static function Update_Earning($r)
    {
    	if($r->txt_amount < 1) return false;
    	
    	try {
            $important_obj = Payroll::PayrollPeriod2($r->cbo_month, $r->cbo_period.'D', $r->cbo_year);
            $data = [
                'payroll_period'=>$r->cbo_period,
                'emp_no'=>$r->cbo_employee,
                'emp_name'=>Employee::Name($r->cbo_employee),
                'earning_code'=>$r->cbo_type,
                'amount'=>$r->txt_amount,
                'date_from'=>$important_obj->from,
                'date_to'=>$important_obj->to,
                'month'=>$r->cbo_month,
                'year'=>$r->cbo_year,
            ];

            return DB::table(EmployeeEarnings::$tbl_name)->where('id', $r->txt_hidden_id)->update($data);
        } catch (Exception $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return $e->getMessage();
        }
    }

    public static function Delete_Earning($r)
    {
    	try {
    		$data = ['cancel'=>true];
            return DB::table(EmployeeEarnings::$tbl_name)->where('id', $r->txt_hidden_id)->update($data);
        } catch (Exception $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return $e->getMessage();
        }
    }

    public static function Find_Earning($month, $year, $period, $office)
    {
    	try {
    		$important_obj = Payroll::PayrollPeriod2($month, $period.'D', $year);
            $ofc_emp = json_decode(Office::OfficeEmployees($office));
    		$data = [];
            if (count($ofc_emp) > 0) {
                for ($i=0; $i < count($ofc_emp); $i++) { 
                    $emp = $ofc_emp[$i];
                    $r = DB::table(self::$tbl_name)->where('emp_no', $emp->empid)->whereDate('date_from', $important_obj->from)->whereDate('date_to', $important_obj->to)->where('cancel', null)->first();
                    if ($r!=null) {
                        array_push($data, $r);
                    }
                }
            }
    		if(count($data) > 0) {
    			for($i=0; $i<count($data); $i++) {
	    			$data[$i]->date_from_readable = \Carbon\Carbon::parse($data[$i]->date_from)->format('M d, Y');
	    			$data[$i]->date_to_readable = \Carbon\Carbon::parse($data[$i]->date_to)->format('M d, Y');
	    			$data[$i]->earning_readable = OtherEarnings::Get_Name($data[$i]->earning_code);
	    		}
    		} else {
    			return "No record found.";
    		}

    		return $data; 
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

    public static function Find_Earning2($id)
    {
    	try {

    		$data = DB::table(self::$tbl_name)->where('id', $id)->where('cancel', null)->first();

    		if($data != null) {
    			return (array) $data;
    		} else {
    			return "No record found.";
    		}

    		return $data; 
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

}