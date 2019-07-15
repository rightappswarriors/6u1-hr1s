<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Core;

class CheckRights
{
    // Check authentication session.
    public function handle($request, Closure $next, $level)
    {

        if(intval(Session::get('_user')[0]->grp_id) < $level) {
            return redirect('/home');
        }

        $this->RefreshSession(Session::get('_user'));
        return $next($request);
    }

    public function RefreshSession($session)
    {
        Session::put('_user', $session);
    }

}