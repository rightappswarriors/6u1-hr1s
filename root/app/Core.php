<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
use Carbon\Carbon;

class Core extends Model
{
    public static function company_name()
    {
    	/**
    	* Returns name of the company
    	*/
    	return env('APP_COMPANY', '-no-company-name-');
    }
    // Use raw SQL when retrieving data
	public static function sql($sql)
	{	
		try {
			return DB::select(DB::raw($sql));
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public static function get_User($val)
	{
		/**
		*	Get user
		*	@param $val uid in x08
		*/
		try {
			return DB::table('x08')->where('uid', strtoupper($val))->first();
		} catch (\Exception $e) {
			return null;
		}
	}

	public static function getm99One($clm)
	{
		/*
		|	Created Update function - Copied from GUIHULNGAN 2 (Core.php) FEB 8, 2019
		|	Note: for m99, clm = column
		*/
		try
		{
			if (!empty($clm))
			{
				$data =  DB::table(DB::raw('hris.m99'))->select('hris.m99.'.$clm)->first();
				return $data;
			} else {
				return null;
			}
		}
		catch (Exception $e)
		{
			return null;
		}
	}

	public static function getm99($clm)
	{
		/*
		|	Revised getm99One function which returns only the value of the selected column on m99 table
		*/
		try {
			if ($clm!=null) {
				$data =  DB::table(DB::raw('hris.m99'))->select('hris.m99.'.$clm)->first();
				$data = (array)$data;
				return $data[$clm];
			}
		} catch (\Exception $e) {
			return null;
		}
	}

	public static function get_nextincrementlimitchar($val = "", $limit = 0) {
		/*
		|	Created Update function - Copied from GUIHULNGAN 2 (Core.php) FEB 8, 2019
		|	The parameter "$val" is from getm99One and "$limit" is character limit.
		*/
	   $newvalue = ""; $len = strlen($val); $vsplit = str_split($val);
	   if(count($vsplit) > 0) { foreach($vsplit AS $a => $b) {
	       $vsplit[$a] = intval($b);
	   } }
	   $newvalue = intval(join("", $vsplit)) + 1; $vsplit = str_split($newvalue);
	   $appstr = ""; $newlimit = $limit - count($vsplit);
	   if($newlimit > 0) { for($i = 0; $i < $newlimit; $i++) {
	       $appstr .= "0";
	   } }
	   $newvalue = $appstr . $newvalue;
	   return $newvalue;
	}

	// Created Update function - Copied from GUIHULNGAN 2 (Core.php) FEB 8, 2019
	public static function updatem99($clm, $val)
	{
		try
		{
			return DB::table(DB::raw('hris.m99'))->update([$clm => $val]);
		}
		catch (Exception $e) {
			Core::alert(0, '');
			return false;
		}
	}

	// Get Current Date or Time with Format
	public static function CURRENT_DT($format = "Y-m-d H:i:s")
	{
		return Carbon::now()->format($format);
	}

	// public static function GET_TIME_DIFF($time1, $time2)
	// {
	// 	$time1 = strtotime("1/1/1980 ".$time1);
	// 	$time2 = strtotime("1/1/1980 ".$time2);

	// 	if ($time2 < $time1) {
	// 		$time2 = $time2 + 86400;
	// 	}

	// 	return date("H:i", strtotime("1980-01-01 00:00:00") + ($time2 - $time1));
	// }

	public static function GET_TIME_DIFF($time1, $time2) {
		/*
		| Returns the time difference between time1 and  time2
		| Return value is in string having the format of "00:00:00"
		*/
		$time1_h = date('H', strtotime($time1));
		$time1_m = date('i', strtotime($time1));
		$time2_h = date('H', strtotime($time2));
		$time2_m = date('i', strtotime($time2));

		$hours = $time2_h - $time1_h;
		if ((int)$time2_m < (int)$time1_m) {
			$hours = $hours - 1;
			$time2_m = $time2_m + 60;
		}
		$minutes = $time2_m - $time1_m;

		// return $hours.":".$minutes;
		return sprintf('%02d:%02d', $hours, $minutes);
	}

	public static function GET_TIME_TOTAL(Array $times)
	{
		$minutes = 0; //declare minutes either it gives Notice: Undefined variable
	    // loop throught all the times
	    foreach ($times as $time) {
	        list($hour, $minute) = explode(':', $time);
	        $minutes += $hour * 60;
	        $minutes += $minute;
	    }

	    $hours = floor($minutes / 60);
	    $minutes -= $hours * 60;

	    // returns the time already formatted
	    return sprintf('%02d:%02d', $hours, $minutes);
	}

	// Handle File Upload
	public static function UPLOAD_FILE($file, $folder = "others", $date = null)
	{
		/*
		| This function requires the file
		| Second parameter is for the folder name
		| Third parameter is for the custom file date
		| Returns the new filename.
		*/
		try {
			$_this = new Core;
			$f = $file;
			$time = $_this->CURRENT_DT('H_i_s');
			$date = (($date==null) ? date('Y-m-d') : $date);
			$filenameWithExt = $f->getClientOriginalName();
			$filename = pathInfo($filenameWithExt, PATHINFO_FILENAME);
			$extension = $f->getClientOriginalExtension();
			$newFilename = "hris_".$time.".".$extension;
			$path = $f->storeAs('public/'.$folder.'/'.$date,$newFilename);
			return $newFilename;
		} catch (\Exception $e) {
			return false;
		}
	}

	public static function READ_FILE($file, string $filetype = 'txt', $folder = null)
	{
		/**
		*	Handle Read File
		*	@param $file
		*	@param $filetype = txt file is default
		*	@param $folder
		*	@return $nf
		*/
		try {
    		$nf = [];
    		switch ($filetype) {
    			case 'txt':
    				$folder = ($folder==null) ? "others" : $folder;
			        $file_path = storage_path($folder."/".$file);
			        if (file_exists($file_path)) {
			            $file = file($file_path);
			            foreach ($file as $line) {
			                array_push($nf, trim($line));
			            }
			        }
			        else {
			            $nf = "FILE NOT FOUND.(File path = ".$file_path.")";
			        }
    				break;
    			
    			case 'image':
    				// $folder = ($folder==null)? "storage\app\public\profile_images" : $folder;
    				// $file_path = url($folder);
    				// if(file_exists($file_path)) {
    				// 	$nf = $file_path;
    				// }
    				// else {
    				// 	$nf = "FILE NOT FOUND.(File path = ".$file_path.")";
    				// }
    				break;
    			default:
    				$nf = "INVALID FILE TYPE.";
    				break;
    		}
	        return $nf;
    	} catch (\Exception $e) {
    		return false;
    	}
	}

	// Return IN/OUT Value
	public static function IO($val)
	{
		switch ((string)$val) {
			case 'I':
				return "In";
				break;

			case 'i':
				return "In";
				break;

			case '1':
				return "In";
				break;

			case 'O':
				return "Out";
				break;

			case 'o':
				return "Out";
				break;

			case '0':
				return "Out";
				break;
			
			default:
				return "Undefined";
				break;
		}
	}

	public static function IO2(string $type, $val)
	{
		switch ($type) {
			case 'capital':
				switch ($val) {
					case '1':
						return "I";
						break;

					case 'i':
						return "I";
						break;

					case 'I':
						return "I";
						break;

					case 'In':
						return "I";
						break;

					case '0':
						return "O";
						break;

					case 'o':
						return "O";
						break;

					case 'O':
						return "O";
						break;

					case 'Out':
						return "O";
						break;
					
					default:
						return "Undefined";
						break;
				}
				break;

			case 'number':
				switch ($val) {
					case '1':
						return "1";
						break;

					case 'i':
						return "1";
						break;

					case 'I':
						return "1";
						break;

					case 'In':
						return "1";
						break;

					case '0':
						return "0";
						break;

					case 'o':
						return "0";
						break;

					case 'O':
						return "0";
						break;

					case 'Out':
						return "0";
						break;
					
					default:
						return "Undefined";
						break;
				}
				break;

			case 'small':
				switch ($val) {
					case '1':
						return "o";
						break;

					case '0':
						return "i";
						break;
					
					default:
						return "Undefined";
						break;
				}
				break;

			case 'word':
				switch ($val) {
					case '1':
						return "In";
						break;

					case '0':
						return "Out";
						break;
					
					default:
						return "Undefined";
						break;
				}
				break;
			
			default:
				return "Undefined";
				break;
		}
	}

	// Return SOURCE value
	public static function SOURCE(string $val)
	{
		switch (strtoupper($val)) {
			case 'M':
				return "Manual Entry";
				break;

			case 'U':
				return "Upload";
				break;

			case 'LB':
				return "Log Box";
				break;

			case 'B':
				return "Biomteric Device";
				break;
			
			default:
				return "Undefined";
				break;
		}
	}

	// Return Working Days
	public static function CountWorkingDays($date_1, $date_2)
	{
		$datetime1 = date_create(date('Y-m-d',strtotime($date_1)));
	    $datetime2 = date_create(date('Y-m-d',strtotime($date_2)));
	    
	    $interval = date_diff($datetime1, $datetime2);
	    $interval = $interval->format('%a');
	    $workdays = 0;
	    
	    list($year, $month, $day) = explode("-", $date_1);
	    for ($i=0; $i < $interval; $i++) { 
	    	$q_date = self::GetMonth($month).' '.$day.', '.$year;
	    	$dayofweek = date('l', strtotime($q_date));
	    	if ($dayofweek != "Sunday" && $dayofweek != "Saturday") {
	    		$workdays++;
	    	}
	    	$day++;
	    	if ($day > date('t', strtotime($q_date))) {
	    		$month++;
	    		$day = 1;
	    	}
	    	if ($month > 12) {
	    		$year++;
	    		$month = 1;
	    		$day = 1;
	    	}
	    }

	    return $workdays;
	}

	public static function DateDiff($date_1, $date_2)
	{
		/*
		| Returns the date difference between date 1 and date 2
		*/
		$date_1 = date_create(date('Y-m-d', strtotime($date_1)));
		$date_2 = date_create(date('Y-m-d', strtotime($date_2)));

		$diff = date_diff($date_1, $date_2);
		$diff = $diff->format('%a');
		return $diff;
	}
	
	public static function TotalDays($date_1, $date_2)
	{
		/*
		| Return total days
		*/
		$datetime1 = date_create(date('Y-m-d',strtotime($date_1)));
	    $datetime2 = date_create(date('Y-m-d',strtotime($date_2)));

	    $interval = date_diff($datetime1, $datetime2);
	    $interval = $interval->format('%a');
	    return $interval+1;
	}

    public static function CoveredDates($date_1, $date_2)
    {
    	/*
    	| Get dates from start day to last day
    	*/
    	$date_1 = date('Y-n-j', strtotime($date_1));
    	$date_2 = date('Y-n-j', strtotime($date_2));
    	$datetime1 = date_create(date('Y-m-d',strtotime($date_1)));
	    $datetime2 = date_create(date('Y-m-d', strtotime($date_2)));
	    $interval = date_diff($datetime1, $datetime2);
	    $interval = $interval->format('%a'); 
	    $workdays = [];
	    
	    list($year, $month, $day) = explode("-", $date_1);
	    for ($i=0; $i <= $interval; $i++) { 
	    	$q_date = self::GetMonth($month).' '.$day.', '.$year;
	    	// $dayofweek = date('l', strtotime($q_date));
	    	array_push($workdays, $q_date);
	    	$day++;
	    	if ($day > date('t', strtotime($q_date))) {
	    		$month++;
	    		$day = 1;
	    	}
	    	if ($month > 12) {
	    		$year++;
	    		$month = 1;
	    		$day = 1;
	    	}
	    }

	    return $workdays;
    }

	// Set Alert Session
    public static function Set_Alert($alertType, $alertMsg = null)
    {
        /*
        | ***LEGEND***
        | ALERT TYPE | ALERT COLOR | ALERT INDICATOR
        | --------------------------------------------
        | primary    | blue        | Alert
        | secondary  | gray        | Notice
        | success    | green       | Success
        | danger     | red         | Error
        | warning    | yellow      | Warning
        | info       | light blue  | Info
        | light      | white       | Notice
        | dark       | darker gray | Dead End
        |--------------------------------------------
        */

        switch ($alertType) {
            case 'primary':
                Session::flash('alert-indi', 'Alert');
                break;
            case 'secondary':
                Session::flash('alert-indi', 'Notice');
                break;
            case 'success':
                Session::flash('alert-indi', 'Success');
                break;
            case 'danger':
                Session::flash('alert-indi', 'Error');
                break;
            case 'warning':
                Session::flash('alert-indi', 'Warning');
                break;
            case 'info':
                Session::flash('alert-indi', 'Info');
                break;
            case 'light':
                Session::flash('alert-indi', 'Notice');
                break;
            case 'dark':
                Session::flash('alert-indi', 'Dead End');
                break;
            default:
                Session::flash('alert-indi', 'Dead End');
                break;
        }

        $_this = new Core;
        if ($alertMsg == "" || $alertMsg == null) {
            $alertMsg = $_this->Default_Alert_Msg($alertType);
        }

        Session::flash('alert-type', $alertType);
        Session::flash('alert-msg', $alertMsg);
    }

	// Default Alert Messages
    public static function Default_Alert_Msg($alertType)
    {
        /*
        | ***LEGEND***
        | ALERT TYPE | ALERT COLOR | ALERT INDICATOR
        | --------------------------------------------
        | primary    | blue        | Alert
        | secondary  | gray        | Notice
        | success    | green       | Success
        | danger     | red         | Error
        | warning    | yellow      | Warning
        | info       | light blue  | Info
        | light      | white       | Notice
        | dark       | darker gray | Dead End
        |--------------------------------------------
        */
        switch ($alertType) {
            case 'primary':
                $msg = "New Notification.";
                break;
            case 'secondary':
                $msg = "Something went wrong.";
                break;
            case 'success':
                $msg = "Action was succesful!";
                break;
            case 'danger':
                $msg = "There was an error.";
                break;
            case 'warning':
                $msg = "The action contain some errors.";
                break;
            case 'info':
                $msg = "New Update!";
                break;
            case 'light':
                $msg = "";
                break;
            case 'dark':
                $msg = "";
                break;
            case 'added':
                $msg = "Successfully Added.";
                break;

            case 'edit':
                $msg = "Successfully Updated.";
                break;

            case 'update':
                $msg = "Successfully Update.";
                break;

            case 'delete':
                $msg = "Successfully Deleted.";
                break;

            case 'error':
                $msg = "An error occured while processing your request.";
                break;
            
            default:
                $msg = "";
                break;
        }
        return $msg;
    }

    // Convert number from 1 - 7 into week names
    public static function WeekVal($number)
    {
    	if ($number <= 7) {
    		$week = array(1 => 'MONDAY', 2 => 'TUESDAY', 3 => 'WEDNESDAY', 4 =>'THURSDAY', 5 => 'FRIDAY', 6 => 'SATURDAY', 7 => 'SUNDAY');
	    	return $week[$number];
    	} else {
    		return "NULL";
    	}
    }

    // Convert number from 1 - 7 into week shortnames
    public static function WeekValShort($number)
    {
        if ($number <= 7) {
        	$week = array(1 => 'mon', 2 => 'tue', 3 => 'wed', 4 =>'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun');
	        return $week[$number];
        } else {
        	return "NULL";
        }
    }

    // Convert week name into numbers from 1 - 7
    public static function WeekValRev($weekval)
    {

    	switch ($weekval) {
    		case 'MONDAY':
    			return 1;
    			break;

    		case 'TUESDAY':
    			return 2;
    			break;

    		case 'WEDNESDAY':
    			return 3;
    			break;

    		case 'THURSDAY':
    			return 4;
    			break;

    		case 'FRIDAY':
    			return 5;
    			break;

    		case 'SATURDAY':
    			return 6;
    			break;

    		case 'SUNDAY':
    			return 7;
    			break;
    		
    		default:
    			return "NULL";
    			break;
    	}
    }

    public static function currSign(String $value = null)
    {
    	/**
	    * Returns the currency symbol of the currency that was set in .env file
	    * Copy and paste this => {!!Core::currSign()!!}
	    * @return symbol of selected currency
	    */
    	$curr = ($value!=null) ? $value : config('app.currency');

    	try {
    		$symbol = config('app.c-symbols-def.'.$curr);
    	} catch (\Exception $e) {
    		$curr = config('app.currency');
    		$symbol = config('app.c-symbols-def.'.$curr);
    	}

    	if ($symbol==null) {
    		$symbol = "SYMBOL_NOT_FOUND";
    	}

    	return $symbol;
    }

    public static function ToWord($value = null)
    {
    	switch (strtoupper($value)) {
    		case 'M':
    			$value = "Monthly";
    			break;

    		case 'D':
    			$value = "Daily";
    			break;

    		case 'S':
    			$value = "Semi-monthly";
    			break;

    		case 'W':
    			$value = "Weekly";
    			break;

    		case 'A':
    			$value = "Annual";
    			break;
    		
    		default:
    			$value = "no_value";
    			break;
    	}

    	return $value;
    }

    public static function GetTotal(array $values)
    {
    	try {
    		$total = 0;
	    	$error = [];
	    	foreach($values as $value) {
	    		if (is_numeric($value)) {
	    			$total += $value;
	    		} else {
	    			array_push($error, $value);
	    		}
	    	}

	    	return [round($total, 2), $error];
    	} catch (\Exception $e) {
    		return 0;
    	}
    }

    public static function ToHours(string $time)
    {
    	/**
    	* @param string "00:00" format
    	* @return float "0.0000.."
    	*/
    	try {
    		list($hour, $minute) = explode(":", $time);
    		$hour += $minute / 60;
    		return round($hour,2);
    	} catch (\Exception $e) {
    		// return $e->getMessage();
    		return 0;
    	}
    }

    public static function ToMinutes(string $time)
    {
    	/**
    	* @param string "00:00" format
    	* @return float "0.0000.."
    	*/
    	try {
    		list($hour, $minute) = explode(":", $time);
    		$minute += ($hour * 60);
    		return $minute;
    	} catch (\Exception $e) {
    		// return $e->getMessage();
    		return 0;
    	}
    }

    public static function ToHourOnly(string $time)
    {
    	/**
    	* converts string time to float. Only converts the value of hour. Minute value will be discarded.
    	* @param string "00:00" format
    	* @return only hour float "0.0000.."
    	*/
    	try {
    		list($hour, $minute) = explode(":", $time);
    		return round($hour);
    	} catch (\Exception $e) {
    		return 0;
    	}
    }

    // Returns postgre schema that was set in .env file
    public static function PostgreSchema()
    {
    	return config('database')['connections']['pgsql']['schema'];
    }

    public static function Months()
    {
    	// Returns respective month when giving a string
    	$months = [
    		'01' => 'January',
    		'02' => 'February',
    		'03' => 'March',
    		'04' => 'April',
    		'05' => 'May',
    		'06' => 'June',
    		'07' => 'July',
    		'08' => 'August',
    		'09' => 'September',
    		'10' => 'October',
    		'11' => 'November',
    		'12' => 'December',
    	];

    	return $months;
    }

    public static function GetMonth($val)
    {
    	// Returns respective month when giving an int
    	$months = [
    		1 => 'January',
    		2 => 'February',
    		3 => 'March',
    		4 => 'April',
    		5 => 'May',
    		6 => 'June',
    		7 => 'July',
    		8 => 'August',
    		9 => 'September',
    		10 => 'October',
    		11 => 'November',
    		12 => 'December',
    	];

    	return $months[$val];
    }

    public static function InformationSchema_Columns($column)
    {
    	/*
    	| Returns the given table's information schema.
    	| This returns one of the few information when called.
    	| - Database name of each columns
    	| - table schema
    	| - columne name
    	*/
    	try {
    		return DB::select('select * from INFORMATION_SCHEMA.COLUMNS where table_name = \''.$column.'\' AND table_schema = \''.self::PostgreSchema().'\';'); 
    	} catch (\Exception $e) {
    		return "error";
    	}
    }

    public static function ToFloat($val)
    {
    	/**
    	* Convert number string with comma (eg. 1,000.00) into float (eg 1000.00)
    	*/
    	return floatval(str_replace(',', '', $val));
    }

    public static function getSessionData($user = '_user'){
    	if(isset($user) && session()->has($user)){
    		return session()->get($user);
    	}
    }

    public static $default_img = 'root/storage/app/public/profile_images/profile_user2.jpg';
}
