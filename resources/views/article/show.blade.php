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
                    <a class='link' href="{{route('editor.show', $article->editor_id)}}">
                        {{$article->author_fname . " " . $article->author_lname}}
                    </a>
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

            <div class="article__rating rating">
                @can('vote-for-article', $article)
                    <div class="rating__label">
                        Rate the article
                    </div>
                @endcan
                <div id="rater" class="rating__rater"></div>
                <div id="avg_rating" class="rating__avg">
                    0
                </div>
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
                                <div class="comment__votes">
                                    <span class="comment__upvotes">
                                        @if (
                                            Session::get('voted_items') !== null
                                            &&
                                                (  
                                                    array_key_exists($comment->id, Session::get('voted_items')['comments']) 
                                                    && 
                                                    Session::get('voted_items')['comments'][$comment->id]->vote === 1 
                                                )
                                            )
                                            <i onclick="vote(this)" data-vote="1" data-comment="{{$comment->hash_id}}" class="fas fa-arrow-alt-circle-up comment__upvote-btn comment__vote-btn"></i>
                                        @else
                                            <i onclick="vote(this)" data-vote="1" data-comment="{{$comment->hash_id}}" class="far fa-arrow-alt-circle-up comment__upvote-btn comment__vote-btn"></i>
                                        @endif
                                        <span class="comment__vote-count">{{$comment->upvotes}}</span>
                                    </span>
                                    <span class="comment__downvotes">
                                        @if (
                                            Session::get('voted_items') !== null
                                            &&
                                                (  
                                                    array_key_exists($comment->id, Session::get('voted_items')['comments']) 
                                                    && 
                                                    Session::get('voted_items')['comments'][$comment->id]->vote === 0 
                                                )
                                            )
                                            <i onclick="vote(this)" data-vote="0" data-comment="{{$comment->hash_id}}" class="fas fa-arrow-alt-circle-down comment__downvote-btn comment__vote-btn"></i>
                                        @else
                                            <i onclick="vote(this)" data-vote="0" data-comment="{{$comment->hash_id}}" class="far fa-arrow-alt-circle-down comment__downvote-btn comment__vote-btn"></i>
                                        @endif
                                        <span class="comment__vote-count">{{$comment->downvotes}}</span>
                                    </span>
                                </div>
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

                    let upvote =  document.querySelector("[data-comment='" + comment + "'][data-vote='1']");
                    let downvote = document.querySelector("[data-comment='" + comment + "'][data-vote='0']");

                    upvote.nextElementSibling.textContent = response.data.votes.upvotes;
                    downvote.nextElementSibling.textContent = response.data.votes.downvotes;

                    // Change the style of the button to show user he has voted for the comment
                    voteBtn.classList.toggle('far');
                    voteBtn.classList.toggle('fas');

                    
                    if (voteBtn.classList.contains('fas') && voteBtn.classList.contains('comment__upvote-btn')) {

                        // If the currently pressed vote btn has already been pressed (has class .fas) and is an upvote, 
                        // remove the .fas class from downvote button

                        downvote.classList.remove('fas');
                        downvote.classList.add('far')

                    } else if (voteBtn.classList.contains('fas') && voteBtn.classList.contains('comment__downvote-btn')) {

                        // If the currently pressed vote btn has already been pressed (has class .fas) and is an downvote, 
                        // remove the .fas class from upvote button

                        upvote.classList.remove('fas');
                        upvote.classList.add('far')
                    }

                } else {

                    alertify.notify('Something went wrong. Please try again.', 'error')
                }

            })
            .catch (function (error) {

                if (error.response.status === 429) {

                    alertify.notify('Please wait before voting for a comment again.', 'error')

                } else {

                    alertify.notify('Something went wrong. Please try again.', 'error');
                }

                console.log(error);
            })
        }

    </script>

    <script>

        let rtr = document.getElementById('rater');
        let avgRating = document.getElementById('avg_rating')

        let rater = raterJs({
            element: rtr,
            starSize: 20,
            disableText: 'Please wait before rating the article again.',
            rateCallback: (rating, done) => {

                let url = window.location.href + "/rate";

                axios.post(url, {
                    rating: rating,
                })
                .then (function (response) {

                    if (response.data.status) {

                        let avg = parseFloat(response.data.avg);
                        let total = response.data.total;

                        rater.setRating(avg);

                        rater.disable();

                        avgRating.textContent = avg + " from " + total + " rating/s.";
                    }

                    done();
                })
                .catch (function (error) {

                    if (error.response.status == 401) {

                        alertify.notify('You are not authorized to rate the article.', 'error')

                    } else {

                        alertify.notify('Something went wrong. Please try again.', 'error')
                    }

                    done();

                    console.log(error);
                })
            }
        });

        rater.setRating(parseFloat({{$rating->avg}}))

        avgRating.textContent = parseFloat({{$rating->avg}}) + " from " + {{$rating->total}} + " rating/s.";

    </script>
   
@endsection