<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Authsession
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

        $user_session = Session::get("user_app");
        
        if(isset($user_session)){
            return $next($request);
        }else{
            return redirect()->route("login.page");
        }

    }
}
