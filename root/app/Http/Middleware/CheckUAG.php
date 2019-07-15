<?php

namespace App\Http\Middleware;

use Closure;
use Core;
use Account;

class CheckUAG
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
        if (Account::UAG()!="ADMINISTRATORS") {
            return redirect('/restricted');
        }
        return $next($request);
    }
}
