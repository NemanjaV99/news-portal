<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    public function create($comment)
    {
        $insertData = [
            'hash_id' => $comment['hash_id'],
            'user_id' => $comment['user_id'],
            'article_id' => $comment['article_id'],
            'comment' => $comment['comment'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        return DB::table($this->table)->insert($insertData);
    }

    public function getByHashId($hashId)
    {
        $comment = DB::table($this->table)->where('hash_id', $hashId)->get();

        return $comment;
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

    public function vote($voteData)
    {
        // Check if the row already exists
        $vote = DB::table('user_comment_votes')->where(['user_id' => $voteData['user_id'], 'comment_id' => $voteData['comment_id']])->get();

        if ($vote->isEmpty()) {

            // This is the first user vote for this specific comment
            $status = DB::table('user_comment_votes')->insert([
                'user_id' => $voteData['user_id'],
                'comment_id' => $voteData['comment_id'],
                'vote' => $voteData['vote'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        } else {

            // If it's not the first time
        }

        return $status;
    }

    public function countVotes($comment)
    {
        $commentVoteCount = DB::table('user_comment_votes')
            ->select(DB::raw('count(case when vote = 1 then 1 end) AS upvotes, count(case when vote = 0 then 1 end) AS downvotes'))
            ->where(['comment_id' => $comment])
            ->get();

        return $commentVoteCount;
    }
}
