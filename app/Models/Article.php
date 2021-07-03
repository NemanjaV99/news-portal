<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use stdClass;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    private $allowedRatings = [1, 2, 3, 4, 5];

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
            ->join('article_categories', $this->table . '.category_id', '=', 'article_categories.id')
            ->select($this->table . '.*', 'users.first_name AS author_fname', 'users.last_name AS author_lname', 'article_categories.name AS category_name')
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
            ->join('article_categories', $this->table . '.category_id', '=', 'article_categories.id')
            ->where($this->table . ".hash_id", $hashId)
            ->select($this->table . '.*', 'users.first_name AS author_fname', 'users.last_name AS author_lname', 'article_categories.name AS category_name')
            ->get();

        return $article;
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
        // Formula for calculating avg rating is: AR = 1 * n of 1-star ratings + 2 * n of 2sr + 3 * n of 3sr + 4 * n of 4sr + 5 * n of 5sr / total number of ratings
        // AR - Average Rating, n of sr = number of given star rating (n of 2sr = number of 2-star ratings)
        $result = new stdClass();

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

        // This will take the first element which is the object with results and convert it into an array
        $numberOfRatings = json_decode(json_encode($numberOfRatings->first()), true);

        $totalRatings = array_sum($numberOfRatings);
        $result->total = $totalRatings;

        // If the number of ratings is 0, so nobody rated the article yet, we just need to return 0
        if ($totalRatings === 0) {

            $result->avg = 0;

        } else {

            $avgRating = (
                (1 * $numberOfRatings['star_1_ratings']) + 
                (2 * $numberOfRatings['star_2_ratings']) + 
                (3 * $numberOfRatings['star_3_ratings']) + 
                (4 * $numberOfRatings['star_4_ratings']) +
                (5 * $numberOfRatings['star_5_ratings'])
             ) 
             / (
                 $numberOfRatings['star_1_ratings'] + 
                 $numberOfRatings['star_2_ratings'] + 
                 $numberOfRatings['star_3_ratings'] + 
                 $numberOfRatings['star_4_ratings'] + 
                 $numberOfRatings['star_5_ratings']
             );

            $result->avg = number_format($avgRating, 2);
        }

        return $result;
    }

    public function allowedRatings()
    {
        return $this->allowedRatings;
    }
}
