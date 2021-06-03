<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index(Article $article)
    {
        // Get the latest 5 articles from database
        $latestArticles = $article->getLatest();

        foreach ($latestArticles as &$article) {

            // Calculate the time when the article was created
            // Get the created_at date of article and compare it with current date
            $currentDate = Carbon::now();

            $difference = $currentDate->diff($article->created_at);

            // If the difference is less than an hour, then we want to show minutes in a message format '46 minutes ago'
            // If the difference is over an hour, then take the hour and display message as '4 hours ago' even though the time is 4h32m45s ...

            $timePosted = '';

            if ($difference->d > 0) {

                // Days
                $timePosted = $difference->d . 'd ago';

            } else if ($difference->h > 0) {

                // Hours
                $timePosted = $difference->h . 'h ago';

            } else if ($difference->i > 0) {

                // Minutes
                $timePosted = $difference->i . 'm ago';

            } else {
                
                // Seconds
                $timePosted = $difference->s . 's ago';
            }

            $article->time_posted = $timePosted;
        }

        return view('index', ['articles' => $latestArticles]);
    }

}
