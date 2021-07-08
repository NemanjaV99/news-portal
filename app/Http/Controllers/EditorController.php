<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Editor;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($uuid, Editor $editor, Article $article)
    {
        $editorResult = $editor->getByHashId($uuid);

        if ($editorResult->isEmpty()) {

            abort(404);
        }

        $editorResult = $editorResult->first();
        $numberOfArticles = $article->getTotalCountByAuthor($editorResult->id);
        $avgEditorRating = $editor->calculateAvgRating($editorResult->id);

        return view('editor.show', ['editor' => $editorResult, 'totalArticles' => $numberOfArticles, 'avgRating' => $avgEditorRating]);
    }
}
