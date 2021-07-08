@extends('layouts.app')

@section('page-title', 'Editor')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section editor-info">

            <div class="editor-info__header">

                <div class="editor-info__photo">
                    <img class="image" src="{{asset('assets/images/default-user.jpg')}}" alt="Default user">
                </div>

                <div class="editor-info__name">
                    <h1>
                        {{$editor->first_name . ' ' . $editor->last_name}}
                    </h1>
                </div>

            </div>

            <h2 class="editor-info__section-title">About the Editor</h2>

            <div class="editor-info__main">

                <p class="editor-info__bio">
                    {{$editor->bio}}
                </p>

            </div>

            <h2 class="editor-info__section-title">Editor Stats</h2>

            <div class="editor-info__stats">

                <div class="editor-info__article-no">
                    Number of Articles: {{$totalArticles}}
                </div>
                <div class="editor-info__avg-rating">
                    Average Rating: {{$avgRating->avg}}
                </div>
                
            </div>

            <div class="editor-info__articles">

            </div>

        </section>

    </main>

    @include('layouts.partials.footer')

@endsection
