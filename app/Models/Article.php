<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\AvgRatingCalculator;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    private $allowedRatings = [1, 2, 3, 4, 5];

    private $ratingCalc;

    public function __construct()
    {
        $this->ratingCalc = new AvgRatingCalculator();
    }

    public function create($article)
    {
        $insertData = [
            'hash_id' => $article['hash_id'],
            'category_id' => $article['category'],
            'author_id' => $article['author_id'],
            'title' => $article['title'],
            'text' => $article['text'],
            'image' => $article['image'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        return DB::table($this->table)->insert($insertData);
    }

    public function getLatest($latestCount = 5)
    {
        $articles = DB::table($this->table)
            ->join('users', $this->table . '.author_id', '=', 'users.id')
            ->join('editors', $this->table . '.author_id', '=', 'editors.user_id')
            ->join('article_categories', $this->table . '.category_id', '=', 'article_categories.id')
            ->select(
                $this->table . '.*', 
                'users.first_name AS author_fname', 
                'users.last_name AS author_lname', 
                'article_categories.name AS category_name',
                'editors.uuid AS editor_id'
                )
            ->orderBy($this->table . ".created_at", 'desc')
            ->take($latestCount)
            ->get();

        foreach ($articles as &$article) {

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

        return $articles;
    }

    public function getByHashId($hashId)
    {
        $article = DB::table($this->table)
            ->join('users', $this->table . '.author_id', '=', 'users.id')
            ->join('editors', $this->table . '.author_id', '=', 'editors.user_id')
            ->join('article_categories', $this->table . '.category_id', '=', 'article_categories.id')
            ->where($this->table . ".hash_id", $hashId)
            ->select(
                $this->table . '.*', 
                'users.first_name AS author_fname', 
                'users.last_name AS author_lname', 
                'article_categories.name AS category_name',
                'editors.uuid AS editor_id'
                )
            ->get();

        return $article;
    }

    public function getTotalCountByAuthor($authorId) 
    {
        $numberOfArticles = DB::table($this->table)
            ->where('author_id', $authorId)
            ->count();

        return $numberOfArticles;
    }

    public function rate($ratingData)
    {
        return DB::table('user_article_ratings')->upsert([
            'user_id' => $ratingData['user_id'],
            'article_id' => $ratingData['article_id'],
            'rating' => $ratingData['rating'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ], ['user_id', 'article_id'], ['rating', 'updated_at']);
    }

    public function calculateAvgRating($articleId)
    {
        // So we need to count number of each for given article
        $numberOfRatings = DB::table('user_article_ratings')
            ->select(
                DB::raw('count(case when user_article_ratings.rating = 1 then 1 end) AS star_1_ratings'),
                DB::raw('count(case when user_article_ratings.rating = 2 then 1 end) AS star_2_ratings'),
                DB::raw('count(case when user_article_ratings.rating = 3 then 1 end) AS star_3_ratings'),
                DB::raw('count(case when user_article_ratings.rating = 4 then 1 end) AS star_4_ratings'),
                DB::raw('count(case when user_article_ratings.rating = 5 then 1 end) AS star_5_ratings'),
            )
            ->where('article_id', $articleId)
            ->get();

        $result = $this->ratingCalc->calculate($numberOfRatings->first());
        
        return $result;
    }

    public function allowedRatings()
    {
        return $this->allowedRatings;
    }
}
