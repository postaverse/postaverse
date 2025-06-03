<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Banned;
use App\Models\BannedIp;

class CheckIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if authenticated user is banned
        if (Auth::check() && Auth::user()->bans()->exists()) {
            return redirect()->route('banned');
        }

        // Check if current IP address is banned
        $userIpAddress = $request->ip();
        if (BannedIp::where('ip_address', $userIpAddress)->exists()) {
            return redirect()->route('banned');
        }

        return $next($request);
    }
}