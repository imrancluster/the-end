<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Person;
use Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function __construct() {
        $this->middleware(['clearance']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Person::orderby('id', 'desc')->paginate(5);
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
    public function store(PersonRequest $request)
    {
        $user_id = Auth::user()->id;

        try{
            $person = Person::create([
                'user_id' => $user_id,
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
            ]);
        } catch (QueryException $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Getting query exception.',
                'data' => $ex->getMessage(),
            ], 200);
        }



        return response()->json([
            'error' => false,
            'message' => 'Person has been created.',
            'data' => $person,
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
        $person = Person::find($id);


        if (!$person) {
            return response()->json([
                'data' => [
                    'error' => true,
                    'message' => 'Person not found.',
                ],
            ], 404);
        }

        return $person;

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
    public function update(PersonRequest $request, $id)
    {
        $person = Person::findOrFail($id);

        $person->name = $request->input('name');
        $person->email = $request->input('email');

        if ($request->input('mobile')) {
            $person->body = $request->input('mobile');
        }
        $person->save();

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'Person updated',
                'data' => $person,
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
        $person = Person::find($id);

        if ($person) {
            $person->delete();
        } else {
            return response()->json([
                'data' => [
                    'error' => true,
                    'message' => 'Person not found.',
                ],
            ], 404);
        }

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'Person successfully deleted',
            ],
        ], 200);
    }
}
