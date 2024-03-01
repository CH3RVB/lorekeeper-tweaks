<?php

namespace App\Http\Middleware;

use App\Models\User\User;
use Auth;
use Cache;
use Closure;
use Illuminate\Http\Request;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * * @param  \Closure  $next
     *
     * * @return mixed
     * */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            //if user is invisible...
            if(Auth::user()->settings->is_invisible){
                //check if null so it's not just updating every time a page is clicked
                if(Auth::user()->last_seen != null){
                    //set last seen to null...
                    User::where('id', Auth::user()->id)->update(['last_seen' => null]);
                }
            }else{
                //otherwise, we set the status.
                $expiresAt = now()->addMinutes(2); /* keep online for 2 min */
                Cache::put('user-is-online-'.Auth::user()->id, true, $expiresAt);

                /* last seen */
                User::where('id', Auth::user()->id)->update(['last_seen' => now()]);
            }
        }

        return $next($request);
    }
}
