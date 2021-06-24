@extends('layouts.app')

@section('page-title', 'Create new article')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section user-login">

            <h2 class="section__title section__title--center">Create article</h2>

            {!! Form::open(['route' => 'article.store', 'enctype' => 'multipart/form-data', 'class' => 'form']) !!}

            @error('store_error')
                <div class="form__error">
                    {{$message}}
                </div>
            @enderror

            <div class="form__group">
                {!! Form::label('title', 'Title', ['class' => 'form__label']) !!}
                {!! Form::text('title', null, ['class' => 'form__field' . ($errors->has('title') ? ' form__field--invalid' : null)]) !!}
                @error('title')
                    <div class="form__error">
                        {{$message}}
                    </div>
                @enderror
            </div>

            <div class="form__group">
                {!! Form::label('text', 'Text', ['class' => 'form__label']) !!}
                {!! Form::textarea('text', null, ['id' => 'editor','class' => 'form__field']) !!}
                @error('text')
                    <div class="form__error">
                        {{$message}}
                    </div>
                @enderror
            </div>

            <div class="form__group">
                {!! Form::label('category', 'Category', ['class' => 'form__label']) !!}
                {!! Form::select('category', $categories, null, ['class' => 'form__field' . ($errors->has('title') ? ' form__field--invalid' : null), 'placeholder' => 'Select a category..']) !!}
                @error('category')
                    <div class="form__error">
                        {{$message}}
                    </div>
                @enderror
            </div>

            <div class="form__group">
                {!! Form::label('file', 'Image ( Maximum file size: 2MB )', ['class' => 'form__label']) !!}
                {!! Form::file('image', ['class' => 'form__field' . ($errors->has('image') ? ' form__field--invalid' : null)]) !!}
                @error('image')
                    <div class="form__error">
                        {{$message}}
                    </div>
                @enderror
            </div>

            <div class="form__group">
                {!! Form::submit('Publish', ['class' => 'button form__submit']) !!}
            </div>

            {!! Form::close() !!}


        </section>

    </main>

    @include('layouts.partials.footer')

@endsection
@section('page-scripts')

    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script>

            ClassicEditor
            .create( document.querySelector('#editor'), {
                toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
            })
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );

    </script>

@endsection