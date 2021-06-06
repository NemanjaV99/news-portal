<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Carbon;
use DOMDocument;

class HomeController extends Controller
{
    public function index(Article $article)
    {
        // Get the latest 5 articles from database
        $latestArticles = $article->getLatest();

        return view('index', ['articles' => $latestArticles]);
    }

}
