@extends('layouts.app')

@section('page-title', 'News Portal - Latest news')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section latest-news">

            <h2 class="section__title">Latest News</h2>

            @if($articles->count() != 0)

                @foreach($articles as $article)

                        <article class="news-article">
                            <h3 class="news-article__title">{{$article->title}}</h3>
                            <div class="news-article__text text">
                                {!! $article->text !!}
                            </div>
                            <div class="news-article__info">
                                <span class="news-article__author text">
                                    <i class="fas fa-user"></i>
                                    {{$article->author_fname . ' ' . $article->author_lname}}
                                </span>
                                <span class="news-article__time">
                                    <i class="far fa-clock"></i>
                                    {{$article->time_posted}}
                                </span>
                                <a class="news-article__more" href="{{route('article.show', $article->hash_id)}}">Read more..</a>
                            </div>
                        </article>

                @endforeach

            @endif

        </section>

    </main>

    @include('layouts.partials.footer')

@endsection