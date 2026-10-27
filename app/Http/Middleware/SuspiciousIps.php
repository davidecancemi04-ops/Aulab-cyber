<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
class SuspiciousIps
{
    protected $max_attempts = 5;
    protected $decay_minutes = 1;
    protected $blocked_minutes = 1;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = $this->throttleKey($ip);

        if(cache()->has($key . ':blocked')){
            session()->flash('error', 'Yuor Ip has been blocked for $this->time_blocked minutes(s)due to too many requests.');
            return redirect()->back();
    }
    if(cache()->has($key )){
        if(attempts() >= $this->max_attempts){
            
        cache::put($key . ':blocked', true, $this->blocked_minutes * 60);
        Log::warning("IP $ip has been blocked for $this->blocked_minutes minutes(s)due to too many requests.");
        session::flash('error', 'Yuor Ip has been blocked for $this->time_blocked minutes(s)due to too many requests.');
            return redirect()->back();
            } else {
                Cache::put($key, 1, $this->decay_minutes * 60);
            }
        }

        return $next($request);
    }

    protected function throttleKey($ip)
    {
        return 'throttle:'.sha1($ip);
    }
}