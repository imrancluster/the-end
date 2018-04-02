<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function __construct() {
        $this->middleware(['clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Note::orderby('id', 'desc')->paginate(5);
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
        // Validating title and body field
        $this->validate($request, [
            'title'=>'required|max:100',
            'body' =>'required',
        ]);

        $title = $request['title'];
        $body = $request['body'];

        $note = Note::create($request->only('title', 'body'));

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'Note created',
                'data' => $note,
            ],
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
        return Note::findOrFail($id);
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
        $this->validate($request, [
            'title'=>'required|max:100',
            'body'=>'required',
        ]);

        $note = Note::findOrFail($id);
        $note->title = $request->input('title');
        $note->body = $request->input('body');
        $note->save();

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'Note updated',
                'data' => $note,
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
        $note = Note::findOrFail($id);
        $note->delete();

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'Note successfully deleted',
            ],
        ], 200);
    }
}
