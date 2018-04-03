<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

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

        // Load User
        $role = Role::findByName('Member');

        if ($request->is('api/notes'))//If user is creating a note
        {
            if (!Auth::user()->hasRole('Member')) {
                return response()->json(['errors' => "You are not a member."])->setStatusCode(401);
            }

            if (!$role->hasPermissionTo('Create Note'))
            {
                return response()->json(['errors' => "User has no permission for create note."])->setStatusCode(401);
            }
            else {
                return $next($request);
            }
        }


        if ($request->is('api/notes/*')) //If user is editing a post
        {
            if (!$role->hasPermissionTo('Edit Note')) {
                return response()->json(['errors' => "User has no permission for Edit Note."])->setStatusCode(401);
            }
        }


        if ($request->isMethod('delete')) //If user is deleting a post
        {

            if (!$role->hasPermissionTo('Delete Note')) {
                return response()->json(['errors' => "User has no permission for Delete Note."])->setStatusCode(401);
            }
        }

        return $next($request);
    }
}
