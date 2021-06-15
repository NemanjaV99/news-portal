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
                    <div class="article__no-comments">
                        No comments.
                    </div>
                @else

                    @foreach($comments as $comment)

                        <div class="article__comment comment">
                            <div class="comment__user">Posted by {{$comment->user_fname . ' ' . $comment->user_lname}}</div>
                            <div class="comment__content">{{$comment->comment}}</div>
                            <div class="comment__posted date-format">{{$comment->created_at}}</div>
                            <div class="comment__votes">
                                <span class="comment__upvotes"><i class="fas fa-arrow-alt-circle-up comment__vote-btn"></i> {{$comment->upvotes}}</span>
                                <span class="comment__downvotes"><i class="fas fa-arrow-alt-circle-down comment__vote-btn"></i> {{$comment->downvotes}}</span>
                            </div>
                        </div>

                    @endforeach
    
                @endif
                @auth
                    {!! Form::open(['route' => 'comment.store', 'class' => 'form']) !!}

                    {{Form::hidden('article', $article->hash_id)}}

                    @error('article')
                        <div class="form__group">
                            <div class="form__error">
                                {{$message}}
                            </div>
                        </div>
                    @enderror

                    @error('store_error')
                        <div class="form__group">
                            <div class="form__error">
                                {{$message}}
                            </div>
                        </div>
                    @enderror
                    
                    <div class="form__group">
                        {!! Form::label('comment', null, ['class' => 'form__label']) !!}
                        {!! Form::textarea('comment', null, 
                            [
                                'class' => 'form__field'  . ($errors->has('comment') ? ' form__field--invalid' : null), 
                                'placeholder' => 'Add a new comment..'
                            ]) !!}
                        @error('comment')
                            <div class="form__error">
                                {{$message}}
                            </div>
                        @enderror
                    </div>

                    <div class="form__group">
                        {!! Form::submit('Add new', ['class' => 'button button--blue form__submit']) !!}
                    </div>

                    {!! Form::close() !!}
                @endauth
            </div>
            
        </section>

    </main>

    @include('layouts.partials.footer')

@endsection