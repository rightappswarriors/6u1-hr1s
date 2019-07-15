<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Core;

class CheckAuth
{
    // Check authentication session.
    public function handle($request, Closure $next)
    {
        // dd(intval(Session::get('_user')[0]->grp_id));
        if (!Session::exists('_user')) {
            // user value cannot be found in session
            if(url()->current()!=url('/login')) {
                return redirect('/login');
            }
        }
        if (Session::get('_user')==null) {
            return redirect('/logout');
        } /*else {
            if(Session::get('_user')[0]->grp_id!="001") {
                return redirect('/logout');
            }
        }*/

        $this->RefreshSession(Session::get('_user'));
        return $next($request);
    }

    public function RefreshSession($session)
    {
        Session::put('_user', $session);
    }
}