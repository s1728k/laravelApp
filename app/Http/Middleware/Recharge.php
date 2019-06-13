<?php

namespace App\Http\Middleware;

use Closure;

class Recharge
{

    public function handle($request, Closure $next)
    {
        if(empty(\Auth::user())){
            return $next($request);
        }
        if($request->route()->getName() == 'c.user.recharge_offers.view'){
            return $next($request);
        }
        if( \Auth::user()->recharge_balance == (null||0) || \Auth::user()->recharge_expiry_date < date('Y-m-d') ){
            return redirect()->route('c.user.recharge_offers.view');
        }
        return $next($request);
    }
    
}
