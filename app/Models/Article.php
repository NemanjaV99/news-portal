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

        return $articles;
    }
}
