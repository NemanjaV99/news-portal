@extends('layouts.app')

@section('page-title', 'Editor')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section editor-info">

            <div class="editor-info__main">

                <div class="editor-info__photo">
                    <img class="image" src="{{asset('assets/images/default-user.jpg')}}" alt="Default user">
                </div>

                <div class="editor-info__name">
                    <h1>
                        {{$editor->first_name . ' ' . $editor->last_name}}
                    </h1>
                </div>

                <div class="editor-info__stats">
                    <div>Articles<br> {{$totalArticles}}</div>
                    <div>Avg. Rating<br> {{$avgRating->avg}}</div>
                </div>

            </div>

            <h2 class="editor-info__section-title">About the Editor</h2>

            <div class="editor-info__about">

                <p class="editor-info__bio">
                    {{$editor->bio}}
                </p>

            </div>

        </section>

    </main>

    @include('layouts.partials.footer')

@endsection
