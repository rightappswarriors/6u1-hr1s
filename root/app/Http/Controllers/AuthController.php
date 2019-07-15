<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Core;
use Session;

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
        if ($this->attemptLogin($r)) {
            $this->authenticated($r);
            return redirect($this->redirectTo);
        } else {
            return back()->withErrors("Incorrect Credentials");
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
        foreach ($users as $u) {
            if ($u->uid == strtoupper($r->username)) {
                if ($u->pwd == $r->password) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function authenticated(Request $r)
    {
        // Set User Account Session
        // $r->session()->regenerate();
        // Session::put('_user', ['id' => $r->txt_uname]);
        $user = Core::get_User(strtoupper($r->username));
        if ($user!=null) {
            Session::push('_user', $user);
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->to('/');
    }
}
