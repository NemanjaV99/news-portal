<?php

namespace App\Models;

use App\Services\AvgRatingCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Editor extends Model
{
    use HasFactory;

    protected $table = 'editors';

    private $ratingCalc;

    public function __construct()
    {
        $this->ratingCalc = new AvgRatingCalculator();
    }

    public function getByHashId($uuid)
    {
        $editor = DB::table($this->table)
            ->join('users', $this->table . '.user_id', '=', 'users.id')
            ->where($this->table . '.uuid', $uuid)
            ->select($this->table . '.*', 'users.*')
            ->get();
        
        return $editor;
    }

    public function calculateAvgRating($editorId)
    {
        $numberOfRatings = DB::table('articles')
            ->join('user_article_ratings', 'articles.id', '=', 'user_article_ratings.article_id')
            ->select(
                DB::raw('count(case when user_article_ratings.rating = 1 then 1 end) AS star_1_ratings'),
                DB::raw('count(case when user_article_ratings.rating = 2 then 1 end) AS star_2_ratings'),
                DB::raw('count(case when user_article_ratings.rating = 3 then 1 end) AS star_3_ratings'),
                DB::raw('count(case when user_article_ratings.rating = 4 then 1 end) AS star_4_ratings'),
                DB::raw('count(case when user_article_ratings.rating = 5 then 1 end) AS star_5_ratings'),
            )
            ->where('articles.author_id', $editorId)
            ->get();

        $result = $this->ratingCalc->calculate($numberOfRatings->first());
        
        return $result;
    }
}
