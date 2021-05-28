@extends('layouts.app')

@section('page-title', 'Create new article')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section user-login">

            <h2 class="section__title section__title--center">Create article</h2>

            {!! Form::open(['route' => 'article.store', 'class' => 'form']) !!}

            {!! Form::label('title', 'Title', ['class' => 'form__label']) !!}
            {!! Form::text('title', null, ['class' => 'form__field' . ($errors->has('title') ? ' form__field--invalid' : null)]) !!}
            @error('title')
                <div class="form__error">
                    {{$message}}
                </div>
            @enderror

            {!! Form::label('text', 'Text', ['class' => 'form__label']) !!}
            {!! Form::textarea('text', null, ['class' => 'form__field' . ($errors->has('text') ? ' form__field--invalid' : null)]) !!}
            @error('text')
                <div class="form__error">
                    {{$message}}
                </div>
            @enderror

            {!! Form::label('category', 'Category', ['class' => 'form__label']) !!}
            {!! Form::select('category', $categories, null, ['class' => 'form__field', 'placeholder' => 'Select a category..']) !!}
            @error('category')
                <div class="form__error">
                    {{$message}}
                </div>
            @enderror

            {!! Form::submit('Publish', ['class' => 'button form__submit']) !!}

            {!! Form::close() !!}


        </section>

    </main>

    @include('layouts.partials.footer')

@endsection