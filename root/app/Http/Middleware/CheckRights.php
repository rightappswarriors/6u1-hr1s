<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use DB;

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
        $session = Session::get('_user');
        if (Session::get('_user')!=null) {
            $arr_rest = explode(', ', Session::get('_user')[0]->restriction);
            // dd(DB::table('x05')->join('x06','x06.mod_id','x05.mod_id')->where([['path',$request->path()],['x06.grp_id',$session[0]->grp_id]])->first());
            // dd([$req,$arr_rest,$request->path(),$request->is('settings/*')]);
            // if(!in_array($req, $arr_rest)) {
            //     return redirect('/error/2');
            // }
            if((DB::table('x05')->join('x06','x06.mod_id','x05.mod_id')->where([['path',$request->path()],['x06.grp_id',$session[0]->grp_id]])->first()->restrict ?? 0) > 0){
                return redirect('/error/2');
            }
            return $next($request);
        } else {
            return redirect('/error/2');
        }

    }
}
