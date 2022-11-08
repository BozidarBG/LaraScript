<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UtmMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->isMethod('GET') && $request->has('utm_medium') && $request->has('utm_source') && $request->has('utm_content')){
            $utm_medium=$request->utm_medium;
            $tum_source=$request->utm_source;
            $utm_content=$request->utm_content;
            //radi cookie
            $query=$request->query();
            info($query);
            unset($request['utm_source']);
            unset($request['utm_medium']);
            unset($request['utm_content']);
//            dd($request->query);
            //info($request->all();
            return redirect()->route('home');
            //return $next($request);
        }
        return $next($request);
    }
}
