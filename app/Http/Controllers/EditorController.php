<?php

namespace App\Http\Controllers;

use App\Models\Editor;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($uuid, Editor $editor)
    {
        $editorResult = $editor->getByHashId($uuid);

        if ($editorResult->isEmpty()) {

            abort(404);
        }

        $editorResult = $editorResult->first();

        return view('editor.show', ['editor' => $editorResult]);
    }
}
