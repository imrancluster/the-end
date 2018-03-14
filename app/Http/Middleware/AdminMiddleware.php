<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        $token = $request->header('X-Auth-Token');

        if (empty($token)) {
            return response()->json(['errors' => "Unable to authenticate with invalid token."])->setStatusCode(401);
        }

        $user = User::where('api_token', $token)->first();
        if (empty($user)) {
            return response()->json(['errors' => "Unable to authenticate with invalid token."])->setStatusCode(401);
        }

        // logging user
        Auth::onceUsingId($user->id);

        $users = User::all()->count();

        if (!Auth::user()->hasPermissionTo('Administer roles & permissions')) //If user does //not have this permission
        {
            abort('401');
        }

//        if (!($users == 1)) {
//            if (!Auth::user()->hasPermissionTo('Administer roles & permissions')) //If user does //not have this permission
//            {
//                abort('401');
//            }
//        }

        return $next($request);
    }
}
