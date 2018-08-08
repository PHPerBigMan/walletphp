<?php

namespace App\Http\Middleware;

use App\Model\Admin;
use Closure;

class ApiCheck
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
        if($request->method() == "GET"){

            return response()->json(['code'=>403,'msg'=>'请求出错']);
        }


        return $next($request);
    }
}
