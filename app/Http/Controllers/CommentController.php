<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\VoteCommentRequest;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use stdClass;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['ajax', 'throttle:4'])->only(['vote']);
    }
    
    public function store(StoreCommentRequest $request, Comment $comment, Article $article)
    {
        $data = $request->validated();

        // Retrieve the article using hash id
        $articleFromHash = $article->getByHashId($data['article']);

        // Add the article id
        $data['article_id'] = $articleFromHash->first()->id;

        // Add the user id 
        $data['user_id'] = Auth::user()->id;
        
        // Generate a random hash for hash_id column
        $data['hash_id'] = bin2hex(random_bytes(20));

        $status = $comment->create($data);

        if ($status) {

            return redirect()->back();

        } else {

             // We failed to create the comment
             return redirect()->back()->withErrors(['store_error' => 'Something went wrong.']);
        }
    }

    public function vote(VoteCommentRequest $request, Comment $comment)
    {
        // The request contains the hash id of the comment, and a vote field which should be boolean ( only values: 1 or 0)
    
        // First, retrieve the comment by the hash id passed in the ajax request
        $commentByHash = $comment->getByHashId($request->get('comment'));

        $voteData['comment_id'] = $commentByHash->first()->id;
        $voteData['vote'] = (bool)$request->get('vote'); 
        $voteData['user_id'] = Auth::user()->id;

        $status = $comment->vote($voteData);
        $countedVotes = $comment->countVotes($voteData['comment_id']);

        // We also need to update the user session with voted comments
        // So every time a new vote is cast, we need to recreate/update the array in session that contains all the voted comments by user
        $votedComments = $comment->getUserVotedComments($voteData['user_id']);

        $votedComments = $votedComments->toArray();

        $comments = [];

        foreach ($votedComments as $votedComment) {

            // We don't need to save the timestamps so remove them
            unset($votedComment->created_at, $votedComment->updated_at);

            $comments[$votedComment->comment_id] = $votedComment;
        }

        Session::put('voted_items.comments', $comments);

        return response(['status' => $status, 'votes' => $countedVotes->first()]);
    }

}
