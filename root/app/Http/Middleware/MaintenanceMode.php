<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Core;

class MaintenanceMode
{
    // Check authentication session.
    public function handle($request, Closure $next)
    {
        return redirect()->route('redirect', ['page' => 7]);
        // return $next($request);
    }

}