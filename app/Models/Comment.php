<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

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
