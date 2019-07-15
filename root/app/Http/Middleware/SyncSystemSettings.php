<?php

namespace App\Http\Middleware;

use Closure;
use Core;
use DB;
use ErrorCode;
use Session;

class SyncSystemSettings
{
    public function __construct()
    {
        $this->m99 = DB::table('m99')->first();
    }

    
    public function handle($request, Closure $next)
    {
        $this->CheckSystemDates();
        return $next($request);
    }

    public function CheckSystemDates()
    {
        try {
            if ($this->m99->hy != date('Y') || $this->m99->hm != date('m')) {
                $this->AutomaticMaintenance();
                $this->UpdateSystemDates();
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('middleware', 'SyncSystemSettings', '00001', $e->getMessage());
        }
    }

    public function UpdateSystemDates()
    {
        try {
            $data = [
                'hy' => date('Y'),
                'hm' => date('m')
            ];
            DB::table('m99')->update($data);
        } catch (\Exception $e) {
            ErrorCode::Generate('middleware', 'SyncSystemSettings', '00002', $e->getMessage());
        }
    }

    public function AutomaticMaintenance()
    {
        try {
            $this->Reset_LeaveLimit();
        } catch (\Exception $e) {
            ErrorCode::Generate('middleware', 'SyncSystemSettings', '00003', $e->getMessage());
        }
    }

    public function Reset_LeaveLimit()
    {
        $lt = Core::PostgreSchema()."."."hr_leave_type";
        $lec = Core::PostgreSchema()."."."hr_emp_leavecount";
        $sql = "SELECT elccode, leave_type, empid, count, peak, leave_limit FROM ".$lt." lt INNER JOIN ".$lec." lec ON lt.code = lec.leave_type ";
        try {
            if ($this->m99->hm != date('m')) {
                $sql = $sql."WHERE lt.carry_over = 'M'";
                $lc = Core::sql($sql);
                $this->Update_LeaveCount($lc, "M");
            }
            if ($this->m99->hy != date('Y')) {
                $sql = $sql;
                $lc = Core::sql($sql);
                $this->Update_LeaveCount($lc, "Y");
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('middleware', 'SyncSystemSettings', '00004', $e->getMessage());
        }
    }

    public function Update_LeaveCount($lc, $mode)
    {
        try {
            if (count($lc)>0) {
                foreach ($lc as $key) {
                    $count = 0;
                    if ($mode == "Y") {
                        $new_peak = $key->leave_limit;
                    } elseif ($mode == "M") {
                        $new_peak = ($key->peak - $key->count) + $key->leave_limit;
                    }

                    $data = [
                        'count' => $count, 
                        'peak' => $new_peak
                    ];
                    DB::table('hr_emp_leavecount')->where('elccode', $key->elccode)->update($data);
                }
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('middleware', 'SyncSystemSettings', '00005', $e->getMessage());
        }
    }

}