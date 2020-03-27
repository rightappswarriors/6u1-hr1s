<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class Account extends Model
{
    public static function UAG() // User Account Group
	{
		try {
			$_this = new Account;
			return DB::table('x07')->where('grp_id', $_this->CURRENT()->grp_id)->first()->grp_desc;
		} catch (\Exception $e) {
			return $e->getMessage();
			return null;
		}
	}

	public static function CURRENT() // Current user logged in
	{
		try {
			return Session::get('_user')[0];
		} catch (\Exception $e) {
			return null;
		}
	}

	public static function NAME()
	{
		$_this = new Account;
		return $_this->CURRENT()->opr_name;
	}

	public static function HAS_IMAGE($uid)
	{
		try {
			return DB::table('x08')->where('uid', $uid)->first()->img != "";
		} catch (\Exception $e) {
			return null;
		}
	}

	public static function GET_IMAGE($uid)
	{
		try {
			return DB::table('x08')->where('uid', $uid)->first()->img;
		} catch (\Exception $e) {
			return null;
		}
	}

	public static function GET_ASSOCIATED_EMPLOYEE()
	{
		try {
			return self::CURRENT()->empid;
		} catch (\Exception $e) {
			return null;
		}
	}

	public static function GET_DATA_FROM_CURRENT(string $objectData)
	{
		try {
			return self::CURRENT()->$objectData;
		} catch (\Exception $e) {
			return null;
		}
	}

	public static function ID()
	{
		try {
			return self::CURRENT()->uid;
		} catch (\Exception $e) {
			return null;
		}
	}
}
