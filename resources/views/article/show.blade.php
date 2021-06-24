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
                @if(count($commentsPaginator->items()) < 1) 
                    <div class="article__no-comments">
                        No comments.
                    </div>
                @else

                    @foreach($commentsPaginator->items() as $comment)

                        <div class="article__comment comment">
                            <div class="comment__user">Posted by {{$comment->user_fname . ' ' . $comment->user_lname}}</div>
                            <div class="comment__content">{{$comment->comment}}</div>
                            <div class="comment__posted date-format">{{$comment->created_at}}</div>
                            @if ($comment->user_id !== Auth::user()->id)
                                <div class="comment__votes">
                                    <span class="comment__upvotes">
                                        @if (
                                            array_key_exists($comment->id, Session::get('voted_items')['comments']) 
                                            && 
                                            Session::get('voted_items')['comments'][$comment->id]->vote === 1
                                            )
                                            <i onclick="vote(this)" data-vote="1" data-comment="{{$comment->hash_id}}" class="fas fa-arrow-alt-circle-up comment__vote-btn"></i>
                                        @else
                                            <i onclick="vote(this)" data-vote="1" data-comment="{{$comment->hash_id}}" class="far fa-arrow-alt-circle-up comment__vote-btn"></i>
                                        @endif
                                        <span class="comment__vote-count">{{$comment->upvotes}}</span>
                                    </span>
                                    <span class="comment__downvotes">
                                        @if (
                                            array_key_exists($comment->id, Session::get('voted_items')['comments']) 
                                            && 
                                            Session::get('voted_items')['comments'][$comment->id]->vote === 0
                                            )
                                            <i onclick="vote(this)" data-vote="0" data-comment="{{$comment->hash_id}}" class="fas fa-arrow-alt-circle-down comment__vote-btn"></i>
                                        @else
                                            <i onclick="vote(this)" data-vote="0" data-comment="{{$comment->hash_id}}" class="far fa-arrow-alt-circle-down comment__vote-btn"></i>
                                        @endif
                                        <span class="comment__vote-count">{{$comment->downvotes}}</span>
                                    </span>
                                </div>
                            @endif
                        </div>

                    @endforeach

                    <div class="article__comment-paginator paginator">

                        @if ($commentsPaginator->onFirstPage())
                            <span class="paginator__link paginator__link--disabled">Previous</span>
                        @else
                            <a class="paginator__link" href="{{$commentsPaginator->previousPageUrl()}}">Previous</a>
                        @endif

                        <span class="paginator__divider">|</span>

                        @if (!$commentsPaginator->hasMorePages())
                            <span class="paginator__link paginator__link--disabled">Next</span>
                        @else
                            <a class="paginator__link" href="{{$commentsPaginator->nextPageUrl()}}">Next</a>
                        @endif

                    </div>

                @endif
                @auth
                    {!! Form::open(['route' => 'comment.store', 'class' => 'form article__comment-form']) !!}

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
                                'class' => 'form__field form__field--full-width'  . ($errors->has('comment') ? ' form__field--invalid' : null), 
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

@section('page-scripts')

    <script>

        function vote(voteBtn) {

            let comment = voteBtn.dataset.comment
            let vote = voteBtn.dataset.vote
            let url = "/comment/vote";
            
            axios.post(url, {
                comment: comment,
                vote: vote,
            })
            .then (function (response) {

                if (response.data.status) {

                    document.querySelector("[data-comment='" + comment + "'][data-vote='1']").nextElementSibling.textContent = response.data.votes.upvotes;
                    document.querySelector("[data-comment='" + comment + "'][data-vote='0']").nextElementSibling.textContent = response.data.votes.downvotes;

                } else {

                    alertify.notify('Something went wrong. Please try again.', 'error')
                }

            })
            .catch (function (error) {
                
                alertify.notify('Something went wrong. Please try again.', 'error')
            })
        }

    </script>

@endsection