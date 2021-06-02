<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Models\ArticleCategory;
use App\Models\Article;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ArticleCategory $articleCat)
    {
        // Only editors can access this page
        if (! Gate::allows('access-editor-pages')) {
            abort(403);
        }

        // Retrieve all article categories so we can create a dropdown select option
        $categories = $articleCat->getAll()->toArray();

        $categorySelect = [];

        foreach ($categories as $category) {

            $categorySelect[$category->id] = $category->name;
        }

        return view('article.create', ['categories' => $categorySelect]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Article $article, StoreArticleRequest $request)
    {
        $data = $request->validated();

        // Get author id
        // The form request authorizes the user as an editor, so we only need the user id ( current user id )
        $data['author_id'] = Auth::id();

        // Generate the hash id. The unique string that will be used as article id in urls ( news-portal.com/article/ab94njnz9j3njna <--)
        $data['hash_id'] = bin2hex(random_bytes(20));

        $status = $article->create($data);

        if ($status) {

            // If article is created, redirect to home page where it should appear in latest articles
            return redirect()->route('home.index');

        } else {

            // We failed to create the article
            return redirect()->route('article.create_form')->withErrors(['create_failed' => 'Something went wrong.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
