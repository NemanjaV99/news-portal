<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    public function create($comment)
    {
        $insertData = [
            'user_id' => $comment['user_id'],
            'article_id' => $comment['article_id'],
            'comment' => $comment['comment'],
            'upvotes' => 0, //temp
            'downvotes' => 0, //temp - set default on table
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        return DB::table($this->table)->insert($insertData);
    }

    public function getArticleComments($articleId)
    {
        $comments = DB::table($this->table)
            ->join('users', $this->table . '.user_id', '=', 'users.id')
            ->select($this->table . '.*', 'users.first_name AS user_fname', 'users.last_name AS user_lname')
            ->where($this->table . '.article_id', $articleId)
            ->orderBy($this->table . ".created_at", 'desc')
            ->get();

        return $comments;
    }
}
