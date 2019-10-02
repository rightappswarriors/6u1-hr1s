<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CheckRights
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currAction = $request->route()->getAction();
        $req = $currAction['restriction'];
        
        if (Session::get('_user')!=null) {
            $arr_rest = explode(', ', Session::get('_user')[0]->restriction);
            if(!in_array($req, $arr_rest)) {
                return redirect('/error/2');
            }
            return $next($request);
        } else {
            return redirect('/error/2');
        }

    }
}
