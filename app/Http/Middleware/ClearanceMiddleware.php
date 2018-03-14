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
        if (Auth::user()->hasPermissionTo('Administer roles & permissions')) //If user has this //permission
        {
            return $next($request);
        }

        if ($request->is('note/create'))//If user is creating a post
        {
            if (!Auth::user()->hasPermissionTo('Create Note'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('note/*/edit')) //If user is editing a post
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
