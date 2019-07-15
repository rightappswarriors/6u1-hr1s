<?php
	// Get All by Table Name
    public static function Get_All_By_Table_Name($tbl_name)
    {
    	try {
    		$box = DB::table($tbl_name)->get();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get All by Table Name and Status (1 = true / 0 = false)
    public function Get_All_By_Table_Name_n_Status($tbl_name,$col_name)
    {
        try {
            $box = DB::table($tbl_name)->where($col_name, '=', 1)->get();
            return $box;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

    // Get All by Table Name and Status Reverse (0 = true / 1 = false)
    public function Get_All_By_Table_Name_n_Status_Rev($tbl_name,$col_name)
    {
        try {
            $box = DB::table($tbl_name)->where($col_name, '=', 0)->get();
            return $box;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

// ***USER***
    // Get User By Id
    public function Get_User_By_Id($id)
    {
    	try {
    		$box = DB::table('users')->where('id', '=', $id)->first();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get User Educational Background By Id
    public function Get_User_Educ_Bckgrnd_By_Id($id)
    {
    	try {
    		$box = DB::table('user_educ_bckgrnd')->where('u_id', '=', $id)->first();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get User Position By Id
    public function Get_User_Position_By_Id($id)
    {
    	try {
    		$box = DB::table('user_position')->where('u_id', '=', $id)->first();
    		$box2 = DB::table('tbl_position')->where('tp_id', '=', $box->tp_id)->first();
    		$position = ucwords(strtolower($box2->tp_desc));
    		return $position;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get User Personal Info By Id
    public function Get_User_Prsnl_Info_By_Id($id)
    {
    	try {
    		$box = DB::table('user_prsnl_info')->where('u_id', '=', $id)->first();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get User Work Info By Id
    public function Get_User_Wrk_Info($id)
    {
    	try {
    		$box = DB::table('user_wrk_info')->where('u_id', '=', $id)->first();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get User ID using slug
    public function GetId_bySlug($tbl_name,$tbl_col,$tbl_operator,$tbl_data)
    {
        try {
        	$box = DB::table($tbl_name)->where($tbl_col , $tbl_operator, $tbl_data)->first();
        	return $box;
        } catch (\Exception $e) {
        	$msg = $e->getMessage();
    		return $msg;
        }
    }

    // Add to user_prsnl_info
    public function Add_New_User($newUsername, $newPassword, $newEmail)
    {
    	try {
	    	$slug = Str::random(10);
	        $verify_token = Str::random(40);

	        $box = User::create([
	            'username' => $newUsername,
	            'email' => $newEmail,
	            'password' => Hash::make($data['password']),
	            'user_slug' => $slug,
	            'status' => 'P',
	        ]);

	        $get_user = $this->GetId_bySlug('users','user_slug','=',$slug);
	        // dd($get_user);

	        DB::table('tbl_verify_code')->insert([
	            'u_id' => $get_user->id,
	            'vc_desc' => 'Registration Code:',
	            'vc_token' => $verify_token,
	            'created_at' => $created_at,
	            'updated_at' => $updated_at,
	        ]);

	        DB::table('user_position')->insert([
	            'tp_id' => 2,
	            'u_id' => $get_user->id,
	            'created_at' => $created_at,
                'updated_at' => $updated_at,
	        ]);

    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Update User Status
    public function Update_User_Status($id,$opt)
    {
        /*
        | ***LEGEND***
        | LETTER | DESCRIPTION | BTN VAL
        | ---------------------------------
        |    A   | APPROVED    | approve
        |    B   | BANNED      | ban
        |    D   | DENIED      | deny
        |    I   | INACTIVE    | inactive
        |    P   | PENDING     | pending
        */
        try {
            switch ($opt) {
                case 'approve':
                    $msg = 'approve';
                    $mew_status = "A";
                    break;
                
                case 'ban':
                    $msg = 'ban';
                    $mew_status = "B";
                    break;

                case 'deny':
                    $msg = 'deny';
                    $mew_status = "D";
                    break;

                case 'inactive':
                    $msg = 'inactive';
                    $mew_status = "I";
                    break;

                case 'pending':
                    $msg = 'pending';
                    $mew_status = "P";
                    break;
                default:
                    $msg = 'error';
                    $mew_status = null;
                    break;
            }
            if ($mew_status!=null) {
                DB::table('users')->where('id', '=',$id)->update([
                    'status' => $mew_status,
                    'updated_at' => Carbon::now(),
                ]);
            }
            return $msg;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

// ***QUESTION***
    // *QUESTION CODE
    // Add Question Code
    public function Add_QuestionCode($data)
    {
    	try {
    		$token = Str::random(15);
	    	
	    	$module = "Add Question Code";
	    	$action = "Added New Question Code";

    		DB::table('qstn_code')->insert([
    			'qcode_token' => $token,
    			'qcode_desc' => $data['qstn_desc'],
    			'created_at' => $this->created_at,
	            'updated_at' => $this->updated_at,
    		]);

    		// $msg = $this->updateSystemLog($updated_at, $module, $action);
    		$msg = "ok";
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get All Question Code
    public function Get_Question_Code()
    {
    	try {
    		$box = DB::table('qstn_code')->get();
    		$msg = $box;
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Active Question Code
    public function Get_Active_Question_Code()
    {
    	try {
    		$box = DB::table('qstn_code')->where('qcode_status', '=', '1')->get();
    		$msg = $box;
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Question Code by Id
    public function Get_Question_Code_By_Id($id)
    {
    	try {
    		$box = DB::table('qstn_code')->where('qcode_id', '=', $id)->first();
    		$msg = $box;
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // *QUESTION CATEGORY
    // Add Question Category
    public function Add_QuestionCategory($data)
    {
    	$created_at = Carbon::now();
    	$updated_at = Carbon::now();
    	$module = "Add Question Category";
    	$action = "Added New Question Category";
    	try {
    		DB::table('qstn_category')->insert([
    			'qcat_name' => $data['qstn_category'],
    			'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now(),
    		]);
    		$msg = $this->updateSystemLog($updated_at, $module, $action);
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get All Question Categories
    public function Get_Question_Category()
    {
    	try {
    		$box = DB::table('qstn_category')->get();
    		$msg = $box;
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Active Question Categories
    public function Get_Active_Question_Category()
    {
    	try {
    		$box = DB::table('qstn_category')->where('qcat_status', '=', '1')->get();
    		$msg = $box;
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Question Category by Id
    public function Get_Question_Category_By_Id($id)
    {
    	try {
    		$box = DB::table('qstn_code')->where('qcat_id', '=', $id)->first();
    		$msg = $box;
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Question Category by Slug
    public function Get_Question_Category_By_Slug($slug)
    {
    	try {
    		$box = DB::table('qstn_category')->where('slug', '=', $slug)->first();
    		$msg = $box;
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // *QUESTION CONTENT
    // Add Questions
    public function Add_Question($data)
    {
    	// dd($data->all());
    	try {
    		$token = Str::random(15);
	    	$module = "Add Question";
	    	$action = "Added New Question";

	    	$qcode = $this->Get_Question_Code_By_Id($data['qstn_qcode_sel']);
	    	$qcat = $this->Get_Question_Category_By_Slug($data['qstn_category_sel']);

	    	DB::table('qstn_content')->insert([
    			'qcon_question' => ucfirst(strtolower($data['qstn_question'])),
    			'qcon_token' => $token,
    			'qcode_id' => $qcode->qcode_id,
    			'qcat_id' => $qcat->qcat_id,
    			'created_at' => $this->created_at,
	            'updated_at' => $this->updated_at,
    		]);

    		$qcon = $this->Get_Question_By_Token_n_Date($token, $this->created_at);

    		foreach ($data['choices'] as $choices) {
    			DB::table('qstn_choices')->insert([
	    			'qchoice_text' => ucfirst(strtolower($choices)),
	    			'qcon_id' => $qcon->qcon_id,
	    			'qchoice_token' => $token,
	    			'created_at' => $this->created_at,
		            'updated_at' => $this->updated_at,
	    		]);
    		}

    		$qchoice = $this->Get_Choice_By_Text_n_Date(ucfirst(strtolower($data['answer'])), $this->created_at, $token);

    		DB::table('qstn_answer')->insert([
    			'qcon_id' => $qcon->qcon_id,
    			'qchoice_id' => $qchoice->qchoice_id,
    			'created_at' => $this->created_at,
	            'updated_at' => $this->updated_at,
    		]);

    		// $msg = $this->updateSystemLog($updated_at, $module, $action);
    		$msg = 'ok';
    		return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get All Questions (Randomize)
    public function Get_All_Questions()
    {
    	try {
    		$box = DB::table('qstn_content')->orderBy(DB::raw('RAND()'))->get();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Questions by Question Code Id (Normal)
    public function Get_Questions_By_Question_Code_Id_N($id)
    {
    	try {
    		$box = DB::table('qstn_content')->where('qcode_id', '=', $id)->get();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Questions by Question Code Id (Randomize)
    public function Get_Questions_By_Question_Code_Id_R($id)
    {
    	try {
    		$box = DB::table('qstn_content')->where('qcode_id', '=', $id)->orderBy(DB::raw('RAND()'))->get();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Question by Token and Date
    public function Get_Question_By_Token_n_Date($token, $date)
    {
    	try {
    		$box = DB::table('qstn_content')->where('qcon_token', '=', $token)->where('created_at', '=', $date)->first();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // *QUESTION CHOICES
    // Get All Choices by Id (Randomize)
    public function Get_Choice_By_QCon_Id_R($id)
    {
    	try {
    		$box = DB::table('qstn_choices')->where('qcon_id', '=', $id)->orderBy(DB::raw('RAND()'))->get();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Choices by Id (Normal)
    public function Get_Choice_By_QCon_Id_N($id)
    {
    	try {
    		$box = DB::table('qstn_choices')->where('qcon_id', '=', $id)->get();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // Get Choices by Text and Date
    public function Get_Choice_By_Text_n_Date($Ans, $date, $token)
    {
    	try {
    		$box = DB::table('qstn_choices')->where('qchoice_text', '=', $Ans)->where('created_at', '=', $date)->where('qchoice_token', '=', $token)->first();
    		return $box;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }

    // *QUESTION ANSWER
    // Get Answer by Question Content Id
    public function Get_Answer_By_Question_Content_Id($id)
    {
    	try {
    		$box = DB::table('qstn_answer')->where('qcon_id', '=', $id)->first();
	    	$answer = $box->qchoice_id;
	    	return $answer;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		// $msg = 'Error';
    		return $msg;
    	}
    }

// ***EXAM***
    // Record Exam Answer
    public function Record_Exam_Answer()
    {
    	
    }

    // Get Perfect Score by Question Code
    public function Get_Perfect_score_By_Question_Code($id)
    {
    	try {
    		$box = DB::table('qstn_content')->where('qcode_id', '=', $id)->get();
	    	$total_items = $box->count();
	    	// $total_items = 10;
	    	return $total_items;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		// $msg = 'Error';
    		return $msg;
    	}
    }

    // Get Exam Passing Percentage
    public function Get_Exam_Passing_Percentage()
    {
    	$percentage = 80;
    	return $percentage;
    }

    // Raw Score to Percentage (String)
    public function Raw_Score_to_Percentage_S($qcode, $rs)
    {
    	$raw_score = $rs;
    	$perfect_score = $this->Get_Perfect_score_By_Question_Code($qcode);
        try {
    		$scoreInPercentage = ($raw_score / $perfect_score) * 100;
	    	$msg = (integer)$scoreInPercentage."%";
	    	return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		// $msg = 'Error';
    		return $msg;
    	}
    }

    // Raw Score to Percentage (Integer)
    public function Raw_Score_to_Percentage_I($qcode, $rs)
    {
    	$raw_score = $rs;
    	$perfect_score = $this->Get_Perfect_score_By_Question_Code($qcode);
        try {
    		$scoreInPercentage = ($raw_score / $perfect_score) * 100;
	    	$msg = (integer)$scoreInPercentage."%";
	    	return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		// $msg = 'Error';
    		return $msg;
    	}
    }

    // Perfect Score to Percentage
    public function Perfect_Score_to_Percentage($qcode)
    {
        $perfect_score = $this->Get_Perfect_score_By_Question_Code($qcode);
        $percentageInDecimal = $this->Get_Exam_Passing_Percentage() / 100;

        try {
    		$passing_score = $percentageInDecimal * $perfect_score;
    		$msg = (integer)$passing_score."%";
	    	return $msg;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		// $msg = 'Error';
    		return $msg;
    	}
    }

    // Check Answer
    public function Check_Answer($correct_ans, $raw_ans)
    {
    	try {
    		if ($correct_ans==$raw_ans) {
    			return true;
    		}
    		else {
    			return false;
    		}
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		// $msg = 'Error';
    		return $msg;
    	}
    }

    // Scale Percentage Score
    public function Scale_Percentage_Score($qcode, $rs)
    {
    	try {
    		$percentage_score = $this->Raw_Score_to_Percentage_I($qcode, $rs);
    		switch ((integer)$percentage_score) {
    			case $percentage_score <= 20:
    				$scale = 'E';
    				break;

    			case $percentage_score <= 40:
    				$scale = 'D';
    				break;

    			case $percentage_score <= 60:
    				$scale = 'C';
    				break;

    			case $percentage_score <= 80:
    				$scale = 'B';
    				break;

    			case $percentage_score <= 100:
    				$scale = 'A';
    				break;
    			
    			default:
    				$scale = 'F';
    				break;
    		}
    		return $scale;
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return $msg;
    	}
    }


// ***SCHEDULER***
    // Validate Schedule Values
    public function Validate_Schedule_Values($data)
    {
        $messages = [
            'required' => 'This field is blank',
        ];
        $this->validate($data, [
            'sched_title' => 'required',
            'sched_duration_d' => 'required',
        ],$messages);

        if ($data->sched_duration_t != null || $data->sched_duration_t == "on") {
            switch ($data->sched_duration_d) {
                case 'standard':
                    $this->validate($data, [
                        'sched_date_start' => 'required',
                    ],$messages);
                    break;

                case 'longevent':
                    $this->validate($data, [
                        'sched_date_start' => 'required',
                        'sched_date_end' => 'required',
                    ],$messages);
                    break;

                case 'repeating':
                    $this->validate($data, [
                        'sched_repeat_by' => 'required',
                    ],$messages);
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        else {
            $this->validate($data, [
                'sched_time_strt_hr' => 'required',
                'sched_time_strt_min' => 'required',
                'sched_time_strt_mrdm' => 'required',
            ],$messages);
            switch ($data->sched_duration_d) {
                case 'standard':
                    $this->validate($data, [
                        'sched_date_start' => 'required',
                    ],$messages);
                    break;

                case 'longevent':
                    $this->validate($data, [
                        'sched_date_start' => 'required',
                        'sched_date_end' => 'required',
                        'sched_time_end_hr' => 'required',
                        'sched_time_end_min' => 'required',
                        'sched_time_end_mrdm' => 'required',
                    ],$messages);
                    break;

                case 'repeating':
                    $this->validate($data, [
                        'sched_repeat_by' => 'required',
                        'sched_time_end_hr' => 'required',
                        'sched_time_end_min' => 'required',
                        'sched_time_end_mrdm' => 'required',
                    ],$messages);
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }
    // Add New Schedule
    public function Add_New_Schedule($title, $duration_d, $duration_t, $date_strt, $date_end, $time_strt_h, $time_strt_min, $time_strt_mrdm, $time_end_h, $time_end_min, $time_end_mrdm, $repeat_by, $repeat_on, $color)
    {
        /*
        | Sample 
        | ----------------------
        | $title,
        | $duration_d,
        | $duration_t,
        | $date_strt,
        | $date_end,
        | $time_strt_h,
        | $time_strt_min,
        | $time_strt_mrdm,
        | $time_end_h,
        | $time_end_min,
        | $time_end_mrdm,
        | $repeat_by,
        | $repeat_on,
        | $color
		|
        | Date Format when Saving to Table
        | --------------------------------------------------------------
        | yyyy-mm-dd            | Year(0000)-Month(00)-Day(00)
        | hr:min:sec            | Time(00:00:00) 24 Hrs
        | yyyy-mm-ddThr:min:sec | Date and time for Fullcalendar Plugin
        | hr:min                | Start & End Format for Repeating by week
        | [1,2,3,4,5,6,7]       | Week Format ([mon,tue,wed,thur,fri,sat,sun])
        */

        try {
            if ($duration_t != null || $duration_t == "on") {
                $title = $title."(All Day)";
                switch ($duration_d) {
                    case 'standard':
                        $tsched_start = $date_strt;
                        $tsched_end = null;
                        $tsched_repeat_by = null;
                        $tsched_repeat_on = null;
                        break;

                    case 'longevent':
                        $tsched_start = $date_strt;
                        $tsched_end = $date_end;
                        $tsched_repeat_by = null;
                        $tsched_repeat_on = null;
                        break;

                    case 'repeating':
                        $tsched_start = "00:00:00";
                        $tsched_end = null;
                        $tsched_repeat_by = $repeat_by;
                        break;
                    
                    default:
                        $tsched_start = null;
                        $tsched_end = null;
                        break;
                }
            }
            else {
                $time_strt_h = $this->Change_Time_12_to_24($time_strt_h, $time_strt_mrdm);
                $time_end_h = $this->Change_Time_12_to_24($time_end_h, $time_end_mrdm);

                $com_time_s = $time_strt_h.':'.$time_strt_min.':00';
                $com_time_e = $time_end_h.':'.$time_end_min.':00';

                // dd($com_time_s.",".$com_time_e);

                switch ($duration_d) {
                    case 'standard':
                        $tsched_start = $date_strt."T".$com_time_s;
                        $tsched_end = null;
                        $tsched_repeat_by = null;
                        $tsched_repeat_on = null;
                        break;

                    case 'longevent':
                        $tsched_start = $date_strt."T".$com_time_s;
                        $tsched_end = $date_end."T".$com_time_e;
                        $tsched_repeat_by = null;
                        $tsched_repeat_on = null;
                        break;

                    case 'repeating':
                        $tsched_start = $com_time_s;
                        $tsched_end = $com_time_e;
                        $tsched_repeat_by = $repeat_by;
                        break;
                    
                    default:
                        $tsched_start = null;
                        $tsched_end = null;
                        break;
                }
            }

            if ($repeat_by !=null) {
                switch ($repeat_by) {
                    case 'rep_daily':
                        $tsched_repeat_on = '["1","2","3","4","5","6","7"]';
                        break;

                    case 'rep_cstm_wk':
                        // dd($repeat_on);
                        // $tsched_repeat_on = implode(",", $repeat_on);
                        $tsched_repeat_on = json_encode($repeat_on);
                        break;
                    
                    default:
                        $tsched_repeat_on = null;
                        break;
                }
            }
            else {
                $tsched_repeat_on = null;
            }
            
            $tsched_color = $this->Get_Custom_Color_Value($color);

            $check = $this->Save_New_Schedule($title,$tsched_start,$tsched_end,$duration_d,$tsched_repeat_by,$tsched_repeat_on,$tsched_color);

            if ($check == "ok") {
                $msg = "ok";
            }
            else {
                $msg = $check;
            }

            return $msg;

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

    // Save New Schedule
    public function Save_New_Schedule($title,$tsched_start,$tsched_end,$tsched_type,$tsched_repeat_by,$tsched_repeat_on,$tsched_color)
    {
        try {
            DB::table('tbl_schedules')->insert([
                'tsched_title' => $title,
                'tsched_start' => $tsched_start,
                'tsched_end' => $tsched_end,
                'tsched_type' => $tsched_type,
                'tsched_repeatby' => $tsched_repeat_by,
                'tsched_repeaton' => $tsched_repeat_on,
                'tsched_color' => $tsched_color,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]);
            return "ok";
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

    // Change time from 12hrs to 24hrs
    public function Change_Time_12_to_24($time,$meridiem)
    {
        $newtime = 0;
        if($meridiem == "AM") {
            $newtime = intval($time);
        }
        elseif($meridiem == "PM") {
            $newtime = 12 + intval($time);
        }

        if ($newtime < 10) {
            $newtime = "0".(string)$newtime;
        }

        return $newtime;
    }

/*------------------------11/22/2018------------------------*/
// Update row
    public static function Update_SingleRow($tbl_name, $IdColName, $operator, $rowId, $columns, $newvalues)
    {
        $MQBC = new MyQueryBuilderController;
        try {
            $tbl = DB::table($tbl_name);
            $arr_where = $MQBC->Array_Where($IdColName, $operator, $rowId);
            $tbl->where($arr_where['column'], $arr_where['operator'], $arr_where['value']);
            $tbl->update([$columns => $newvalues]);
            return "ok";
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

    // Convert to Array for Where Clause
    public static function Array_Where($column, $operator, $value)
    {
        try {
            $c = array(
                'column' => $column, 
                'operator' => $operator, 
                'value' => $value,
            );
            return $c;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }