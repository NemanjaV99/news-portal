@extends('layouts.app')

@section('page-title', 'News Portal - Latest news')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section latest-news">

            <h2 class="section__title">Latest News</h2>

            @if($articles->count() != 0)

                @foreach($articles as $article)

                        <article class="article-box">
                            <a class="link" href="{{route('article.show', $article->hash_id)}}"><h3 class="article-box__title">{{$article->title}}</h3></a>
                            <div class="article-box__info">
                                <span class="article-box__author text">
                                    <i class="fas fa-user"></i>
                                    {{$article->author_fname . ' ' . $article->author_lname}}
                                </span>
                                <span class="article-box__time">
                                    <i class="far fa-clock"></i>
                                    {{$article->time_posted}}
                                </span>
                            </div>
                        </article>

                @endforeach

            @else 

                    <div class="no-articles text">
                        <i class="fas fa-search no-articles__icon"></i>
                        No articles to show.
                    </div>

            @endif

        </section>

    </main>

    @include('layouts.partials.footer')

@endsection