<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

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
            ->select($this->table . '.*', 'users.first_name AS author_fname', 'users.last_name AS author_lname')
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
}
