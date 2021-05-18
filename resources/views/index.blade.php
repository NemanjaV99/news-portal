@extends('layouts.app')

@section('page-title', 'News Portal - Latest news')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section latest-news">

            <h2 class="section__title">Latest News</h2>

            <article class="news-article">
                <h3 class="news-article__title">News Title</h3>
                <p class="news-article__text text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque excepturi magnam, alias temporibus corrupti voluptas iure quo asperiores vitae animi sed esse, incidunt ipsam harum nam quos vero dignissimos odit.</p>
                <div class="news-article__info">
                    <span class="news-article__author text">
                        <i class="fas fa-user"></i>
                        John Doe
                    </span>
                    <span class="news-article__time">
                        <i class="far fa-clock"></i>
                        15m ago
                    </span>
                </div>
            </article>

            <article class="news-article">
                <h3 class="news-article__title">News Title 2</h3>
                <p class="news-article__text text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque excepturi magnam, alias temporibus corrupti voluptas iure quo asperiores vitae animi sed esse, incidunt ipsam harum nam quos vero dignissimos odit.</p>
                <div class="news-article__info">
                    <span class="news-article__author text">
                        <i class="fas fa-user"></i>
                        John Doe
                    </span>
                    <span class="news-article__time">
                        <i class="far fa-clock"></i>
                        40m ago
                    </span>
                </div>
            </article>

            <article class="news-article">
                <h3 class="news-article__title">News Title 3</h3>
                <p class="news-article__text text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque excepturi magnam, alias temporibus corrupti voluptas iure quo asperiores vitae animi sed esse, incidunt ipsam harum nam quos vero dignissimos odit.</p>
                <div class="news-article__info">
                    <span class="news-article__author text">
                        <i class="fas fa-user"></i>
                        John Doe
                    </span>
                    <span class="news-article__time">
                        <i class="far fa-clock"></i>
                        2h ago
                    </span>
                </div>
            </article>

            <article class="news-article">
                <h3 class="news-article__title">News Title 4</h3>
                <p class="news-article__text text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque excepturi magnam, alias temporibus corrupti voluptas iure quo asperiores vitae animi sed esse, incidunt ipsam harum nam quos vero dignissimos odit.</p>
                <div class="news-article__info">
                    <span class="news-article__author text">
                        <i class="fas fa-user"></i>
                        John Doe
                    </span>
                    <span class="news-article__time">
                        <i class="far fa-clock"></i>
                        4h ago
                    </span>
                </div>
            </article>

        </section>

    </main>

    @include('layouts.partials.footer')

@endsection