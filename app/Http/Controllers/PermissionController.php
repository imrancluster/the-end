<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function __construct() {
        $this->middleware(['isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Permission::all();
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
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);

        $name = $request['name'];

        $permission = new Permission();
        $permission->name = $name;
        $permission->save();

//
//        $roles = $request['roles'];
//
//        if (!empty($request['roles'])) { //If one or more role is selected
//            foreach ($roles as $role) {
//                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record
//
//                $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
//                $r->givePermissionTo($permission);
//            }
//        }

        return response()->json([
            'error' => false,
            'message' => 'Permission added.',
            'data' => $permission,
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
        $permission = Permission::findOrFail($id);

        return $permission;
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
        $permission = Permission::findOrFail($id);

        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
        $input = $request->all();
        $permission->fill($input)->save();

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'Permission updated.',
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
        $permission = Permission::findOrFail($id);

        //Make it impossible to delete this specific permission
        if ($permission->name == "Administer roles & permissions") {
            return response()->json([
                'data' => [
                    'error' => true,
                    'message' => 'Cannot delete this Permission.',
                ],
            ], 200);
        }

        $permission->delete();

        return response()->json([
            'data' => [
                'error' => true,
                'message' => 'Permission deleted.',
            ],
        ], 200);
    }
}
