<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct() {
        // I have removed the 'auth' middleware
        $this->middleware(['isAdmin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::paginate(10);//Get all roles

        return $roles;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name and permissions field
        $this->validate($request, [
                'name'=>'required|unique:roles|max:10',
            ]
        );

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;
        $role->save();

        //Looping thru selected permissions
        // $permissions = $request['permissions'];
        // foreach ($permissions as $permission) {
        //    $p = Permission::where('id', '=', $permission)->firstOrFail();
        //    //Fetch the newly created role and assign permission
        //    $role = Role::where('name', '=', $name)->first();
        //    $role->givePermissionTo($p);
        // }

        return response()->json([
            'error' => false,
            'message' => 'Role added.',
            'data' => $role,
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
        $role = Role::findOrFail($id);
        // $permissions = Permission::all();

        return $role;
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
        $role = Role::findOrFail($id);//Get role with the given id
        //Validate name and permission fields
        $this->validate($request, [
            'name'=>'required|max:10|unique:roles,name,'.$id,
        ]);

        $input = $request->except(['permissions']);
        $role->fill($input)->save();

        // $permissions = $request['permissions'];
        // $p_all = Permission::all();//Get all permissions

        // foreach ($p_all as $p) {
        //    $role->revokePermissionTo($p); //Remove all permissions associated with role
        //}

        // foreach ($permissions as $permission) {
        //    $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
        //    $role->givePermissionTo($p);  //Assign permission to role
        //}

        return response()->json([
            'error' => false,
            'message' => 'Role updated.',
            'data' => $role,
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
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'error' => false,
            'message' => 'Role deleted.',
            'data' => [],
        ], 200);

    }
}
