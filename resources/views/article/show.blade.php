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
                <span class="article__posted">{{$article->created_at}}</span>
            </div>

            <div class="article__text">
                {!! $article->text !!}
            </div>
            
        </section>

    </main>

    @include('layouts.partials.footer')

@endsection