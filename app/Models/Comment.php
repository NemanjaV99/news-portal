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
            ->leftJoin('user_comment_votes', $this->table . '.id', '=', 'user_comment_votes.comment_id')
            ->select(
                $this->table . '.id',
                $this->table . '.hash_id',
                $this->table . '.comment',
                $this->table . '.created_at', 
                'users.first_name AS user_fname', 
                'users.last_name AS user_lname',
                DB::raw('count(case when user_comment_votes.vote = 1 then 1 end) AS upvotes'),
                DB::raw('count(case when user_comment_votes.vote = 0 then 1 end) AS downvotes')
            )
            ->where($this->table . '.article_id', $articleId)
            ->groupBy(
                $this->table . '.id', 
                $this->table . '.hash_id',
                $this->table . '.comment',
                $this->table . '.created_at',
                'users.first_name', 
                'users.last_name')
            ->orderBy($this->table . '.created_at', 'desc')
            ->get();

        return $comments;
    }

    public function vote($voteData)
    {
        // Check if the row already exists
        $voteFromDb = DB::table('user_comment_votes')->where(['user_id' => $voteData['user_id'], 'comment_id' => $voteData['comment_id']])->get();

        if ($voteFromDb->isEmpty()) {

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

            $voteFromDb = $voteFromDb->first();

            if ($voteFromDb->vote) {

                // If the vote in the db is an upvote

                if ($voteData['vote']) {

                    // If the vote in the database is an upvote, and the vote from request is also an upvote
                    // THEN: remove the vote from the database
                    $status = $this->removeVote($voteFromDb->user_id, $voteFromDb->comment_id);

                } else {

                    // If the vote from the request is a downvote, and an upvote exists in database
                    // THEN: update the vote changing the upvote to a downvote
                    $status = $this->changeVote($voteFromDb->user_id, $voteFromDb->comment_id, 0);

                }

            } else {

                // If the vote in db is a downvote

                if ($voteData['vote']) {

                    // If the vote in the database is an downvote, and the vote from request is an upvote
                    // THEN: update the vote changing the downvote to an upvote
                    $status = $this->changeVote($voteFromDb->user_id, $voteFromDb->comment_id, 1);

                } else {

                    // If the vote from the request is a downvote, and the vote in the database is also a downvote
                    // THEN: remove the vote from the database
                    $status = $this->removeVote($voteFromDb->user_id, $voteFromDb->comment_id);
                }

            }
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

    private function removeVote($userId, $commentId)
    {
        return DB::table('user_comment_votes')->where(['user_id' => $userId, 'comment_id' => $commentId])->delete();
    }

    private function changeVote($userId, $commentId, $vote)
    {
        return DB::table('user_comment_votes')
            ->where(['user_id' => $userId, 'comment_id' => $commentId])
            ->update(['vote' => $vote]);
    }
}
