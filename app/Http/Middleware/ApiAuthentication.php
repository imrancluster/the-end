<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiAuthentication
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

        return $next($request);
    }
}
