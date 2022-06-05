<?php

namespace App\Http\Middleware;

use Closure;

class Recharge
{

    public function handle($request, Closure $next)
    {
        // if(empty(\Auth::user())){
        //     return $next($request);
        // }

        // $routes_allowed = ['c.docs.home','c.docs.routemap','c.docs.index','c.docs.article','c.blog.home','c.blog.category',
        // 'c.blog.article','logout','c.user.recharge_offers.view','c.user.recharge','c.payment.callback','c.recharge.status',
        // 'c.refund.payment','c.refund.status','c.user.recharge_history.view'];

        // if(in_array($request->route()->getName(), $routes_allowed)){
        //     return $next($request);
        // }

        // if( \Auth::user()->recharge_balance == (null||0) || \Auth::user()->recharge_expiry_date < date('Y-m-d') ){
        //     return redirect()->route('c.user.recharge_offers.view');
        // }

        return $next($request);
    }
    
}
