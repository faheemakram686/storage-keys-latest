<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\NoteInterface;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    private $note;

    public function __construct(NoteInterface $note)
    {
        $this->note = $note;

    }
    public function getRelatedNotes(Request $request)
    {
        return $res = $this->note->getRelatedNote($request);
    }
    public function saveNote(Request $request)
    {
        return $res = $this->note->saveNote($request);
    }
    public function editNote(Request $request)
    {
        return $res = $this->note->editNote($request->id);
    }
    public function updateNote(Request $request)
    {
        $res = $this->note->updateNote($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }
    public function deleteNote(Request $request)
    {
        $this->note->deleteNote($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }


}
