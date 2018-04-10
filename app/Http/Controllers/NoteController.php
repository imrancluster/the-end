<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Resources\NoteResource;
use App\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return NoteResource::collection(Note::orderby('id', 'desc')->paginate(5));
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
            'file' =>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user_id = Auth::user()->id;

        $note = Note::create([
            'user_id' => $user_id,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        // comma separated person IDs
        if ($request['persons']) {
            $note->persons()->attach($request['persons']);
        }

        if ($request->hasFile('file')) {
            $filename = time().'.'.$request->file->getClientOriginalExtension();
            $uri = 'files/'.$filename;
            $original_name = $request->file->getClientOriginalName();
            $filemime = $request->file->getMimeType();
            $filesize = $request->file->getSize();

            $request->file->move(public_path('files'), $filename);

            $fileInserted = File::create(
                [
                    'note_id' => $note->id,
                    'filename' => $original_name,
                    'uri' => $uri,
                    'filemime' => $filemime,
                    'filesize' => $filesize,
                    'status' => 1,
                ]
            );
        }

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
        $note = Note::with(['files'])->find($id);

        return new NoteResource($note);
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
//            'body'=>'required',
            'file' =>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $note = Note::findOrFail($id);
        $note->title = $request->input('title');
        if ($request->input('body')) {
            $note->body = $request->input('body');
        }
        $note->save();

        if ($request->hasFile('file')) {
            $filename = time().'.'.$request->file->getClientOriginalExtension();
            $uri = 'files/'.$filename;
            $original_name = $request->file->getClientOriginalName();
            $filemime = $request->file->getMimeType();
            $filesize = $request->file->getSize();

            $request->file->move(public_path('files'), $filename);

            $fileInserted = File::create(
                [
                    'note_id' => $note->id,
                    'filename' => $original_name,
                    'uri' => $uri,
                    'filemime' => $filemime,
                    'filesize' => $filesize,
                    'status' => 1,
                ]
            );
        }

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'Note updated',
                'data' => new NoteResource($note),
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
        $note = Note::find($id);

        if ($note) {
            $note->delete();
        } else {
            return response()->json([
                'data' => [
                    'error' => true,
                    'message' => 'Note not found.',
                ],
            ], 404);
        }

        return response()->json([
            'data' => [
                'error' => false,
                'message' => 'Note successfully deleted',
            ],
        ], 200);
    }
}
