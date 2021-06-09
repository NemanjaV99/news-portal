<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
    public function index(Article $article)
    {
        // Get the latest 5 articles from database
        $latestArticles = $article->getLatest();

        return view('index', ['articles' => $latestArticles]);
    }

}
