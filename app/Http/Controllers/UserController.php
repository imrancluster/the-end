<?php

namespace App\Http\Controllers;

use App\Living;
use App\Mail\Activation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Spatie\Permission\Models\Role;


class UserController extends Controller
{

    public function __construct()
    {
        //isAdmin middleware lets only users with a //specific permission permission to access these resources
        // $this->middleware(['isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->middleware(['isAdmin']);

        $user = User::paginate(10);

         return $user;
    }

    public function showAllRoles()
    {
        return Role::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password'=>'required|min:6|confirmed'
        ]);

        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]
        );

        // Create a living row for new user
        Living::create(['user_id' => $user->id]);

        // @TODO: Need basic member role Id
        // $role = 2; // Sa
        // $role_r = Role::where('id', '=', $role)->firstOrFail();
        // $user->assignRole($role_r); //Assigning role to user

        $activationId = DB::table('users_activations')->insert([
           'user_id' => $user->id, 'token' => str_random(30)
        ]);

        $activation = DB::table('users_activations')->where('user_id', $user->id)->first();

        $user->link = url("/api/users/activation/{$activation->token}");

        \Mail::to($user)->send(new Activation($user));

        return response()->json([
            'error' => false,
            'message' => 'User has been created. Activation email sent',
            'data' => $user,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user->is_admin == 1) {
            return response()->json([
                'error' => true,
                'message' => 'Access deny',
                'data' => [],
            ], 403);
        }

        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            'password'=>'required|min:6|confirmed'
        ]);

        $input = $request->only(['name', 'email', 'password']);

        $roles = $request['roles']; //Retreive all roles

        $user->fill($input)->save();

        if (isset($roles)) {
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
        }
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'User successfully edited.',
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'User successfully deleted.',
            ],
        ], 200);
    }


    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function activation($token) {

        $check = DB::table('users_activations')->where('token', $token)->first();

        if (!is_null($check)) {
            $user = User::find($check->user_id);

            if ($user->is_activated == 1) {
                return response()->json([
                    'data' => [
                        'message' => 'User is already activated',
                    ],
                ], 200);
            }

            $user->is_activated = 1;
            $user->save();

            DB::table('users_activations')->where('token', $token)->delete();

            return response()->json([
                'data' => [
                    'message' => 'User active successfully',
                ],
            ], 200);

        }

        return response()->json([
            'data' => [
                'error' => true,
                'message' => 'Your token is invalid',
            ],
        ], 404);
    }

    public function living($token) {

        $living = DB::table('livings')->where('token', $token)->first();

        if ($living) {

            $live = Living::findOrFail($living->id);
            $live->send_email_after = 15;
            $live->last_email_seen = time();
            $live->token = str_random(50);
            $live->save();

            return response()->json([
                'data' => [
                    'error' => false,
                    'message' => 'Your information has been updated successfully.',
                ],
            ], 200);
        }

        return response()->json([
            'data' => [
                'error' => true,
                'message' => 'Your token is invalid',
            ],
        ], 404);
    }
}
