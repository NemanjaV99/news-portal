<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
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

    public function upvote(Request $request, Comment $comment)
    {
        // Check if request is ajax
        if (!$request->ajax()) {

            // If the request is not sent by AJAX, return err
            return response('', 405);
        }

        // Check if user is logged in
        if (!Auth::check()) {

            return response('', 401);
        }

        // First, retrieve the comment by the hash id passed in the ajax request
        $commentByHash = $comment->getByHashId($request->get('hash_id'));

        $commentId = $commentByHash->first()->id;
        $userId = Auth::user()->id;

    }
    
}
