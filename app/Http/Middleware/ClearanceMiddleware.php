<?php

namespace App\Http\Middleware;

use Closure;

class ClearanceMiddleware
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
        if (empty($user))
        {
            return response()->json(['errors' => "Unable to authenticate with invalid token."])->setStatusCode(401);
        }

        if (!$user->is_activated)
        {
            return response()->json(['errors' => "User is not activated."])->setStatusCode(401);
        }

        // logging user
        Auth::onceUsingId($user->id);

        if (Auth::user()->hasPermissionTo('Administer roles & permissions')) //If user has this //permission
        {
            return $next($request);
        }

        if ($request->is('notes'))//If user is creating a note
        {
            if (!Auth::user()->hasPermissionTo('Create Note'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('notes/*')) //If user is editing a post
        {
            if (!Auth::user()->hasPermissionTo('Edit Note')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Delete')) //If user is deleting a post
        {
            if (!Auth::user()->hasPermissionTo('Delete Note')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        return $next($request);
    }
}
