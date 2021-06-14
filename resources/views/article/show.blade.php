@extends('layouts.app')

@section('page-title', $article->title)

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section article">

            <h2 class="section__title">{{$article->title}}</h2>

            <div class="article__info">
                <span class="article__author">
                    <i class="fas fa-user"></i>
                    {{$article->author_fname . " " . $article->author_lname}}
                </span>
                <span class="article__category">{{$article->category_name}}</span>
                <span class="article__posted date-format">{{$article->created_at}}</span>
            </div>

            @if(isset($article->image))
                <div class="article__image">
                    <img src="{{asset('storage/' . $article->image)}}" alt="{{$article->title}}">
                </div>
            @endif

            <div class="article__text">
                {!! $article->text !!}
            </div>

            <div class="article__comments">
                <h3>Comments by readers</h3>   
                @if($comments->count() < 1) 
                    <div class="article__no-comment">
                        No comments.
                    </div>
                @else

                    @foreach($comments as $comment)

                        <div class="article__comment">
                            <div class="article__comment-user">{{$comment->user_fname . ' ' . $comment->user_lname}}</div>
                            <div class="article__comment-posted date-format">{{$comment->created_at}}</div>
                            <div class="article__comment-content">{{$comment->comment}}</div>
                        </div>

                    @endforeach
    
                @endif
                @auth
                    <button class="button button--blue">Add a new comment</button>
                @endauth
            </div>
            
        </section>

    </main>

    @include('layouts.partials.footer')

@endsection