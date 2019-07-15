<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Storage;
use Employee;

class UploadDTRController extends Controller
{
    public function view(Request $r)
    {
    	return view('pages.timekeeping.upload_dtr');
    }

    public function submit(Request $r)
    {
    	$error_msg = [
        'file' => 'INVALID FILE',
        'required' => 'PLEASE SELECT A FILE',
        'mimes' => 'MUST BE TEXT FILE',
	    ];
    	$this->validate($r, [
    		'file_dtr' => 'required|file|mimes:txt',
    	],$error_msg);
    	if ($this->upload($r)) {
    		Core::Set_Alert('success', 'File Uploaded');
    		$data = $this->read($r->newfile, $r->filefolder);
            $timelog = new TimeLogEntryController;
            for($i=0;$i<count($data[0]);$i++) {
                $data[0][$i]->empid = Employee::bmIDtoempID($data[0][$i]->bmid);
                $data[0][$i]->status_desc = Core::io($data[0][$i]->status);
                $data[0][$i]->source = "U";
                $timelog->addLog2($data[0][$i]);
            }
            if (!empty($data[1])) {
                Core::Set_Alert('danger', $data[1]);
            }
    		return view('pages.timekeeping.upload_dtr', compact('data'));
    	}
    	Core::Set_Alert('danger', 'Failed to Upload');
    	return back();
    }

    public function upload(Request $r)
    {
    	try {
    		$f = Core::upload_file($r->file_dtr, 'dtr_files');
    		$r->request->add([
    			'newfile' => $f,
    			'filefolder' => 'dtr_files/'.Core::current_dt('Y-m-d'),
    		]);
            return true;
    	} catch (\Exception $e) {
    		return false;
    	}
    }

    public function read($file, $folder = null)
    {
    	$con = Core::READ_FILE($file, 'txt', "app/public/".$folder);
        $con2 = [];
        $con3 = [];
        $errors = [];
        for($i=0;$i<count($con);$i++) {
            $line = $con[$i];
            $line = trim(str_replace(" ", "-", $line));
            $line = explode("-", $line);
            if (count($line)==19) {
                array_push($con2, [$line[0],  $line[15], $line[16], $line[17], $line[18]]);
            } else {
                array_push($errors, "Data on line ".($i+1)." is corrupted. Line ".($i+1)." was not uploaded.");
            }
        }
        for ($i=0; $i < count($con2); $i++) { 
            $line = $con2[$i];
            if (DB::table('hr_employee')->where('biometric', '=', $line[0])->first()!=null) {
                $status = $line[4] - $line[3];
                $temp = (object) ['work_date'=>$line[1], 'time_log'=>$line[2], 'bmid'=>$line[0], 'status'=>$status];
                array_push($con3, $temp);
            } else {
                array_push($errors, "Employee not found. Line ".($i+1)." not uploaded.");
            }
        }
        $errors = implode("\n", $errors);
        $data = [$con3, $errors];
    	return $data;
    }

    public function read2()
    {
    	$this->read('hris_16_24_40.txt', 'dtr_files/2019-02-09');
    }
}
