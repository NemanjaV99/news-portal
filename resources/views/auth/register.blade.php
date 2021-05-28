@extends('layouts.app')

@section('page-title', 'Create an account')

@section('page-content')

    <main class="main">

        <section class="section user-register">

            <h2 class="section__title section__title--center">Create an account</h2>

            {!! Form::open(['route' => 'register.process', 'class' => 'form register-form']) !!}

            {!! Form::label('first_name', 'First Name', ['class' => 'form__label']) !!}
            {!! Form::text('first_name', null, ['class' => 'form__field' . ($errors->has('first_name') ? ' form__field--invalid' : null)]) !!}
            @error('first_name')
                <div class="form__error">
                    {{$message}}
                </div>
            @enderror

            {!! Form::label('last_name', 'Last Name', ['class' => 'form__label']) !!}
            {!! Form::text('last_name', null, ['class' => 'form__field' . ($errors->has('last_name') ? ' form__field--invalid' : null)]) !!}
            @error('last_name')
                <div class="form__error">
                    {{$message}}
                </div>
            @enderror

            {!! Form::label('email', 'Email Adress', ['class' => 'form__label']) !!}
            {!! Form::email('email', null, ['class' => 'form__field' . ($errors->has('email') ? ' form__field--invalid' : null)]) !!}
            @error('email')
                <div class="form__error">
                    {{$message}}
                </div>
            @enderror

            {!! Form::label('password', 'Password', ['class' => 'form__label']) !!}
            {!! Form::password('password', ['class' => 'form__field' . ($errors->has('password') ? ' form__field--invalid' : null)]) !!}
            @error('password')
                <div class="form__error">
                    {{$message}}
                </div>
            @enderror

            {!! Form::submit('Register', ['class' => 'button form__submit']) !!}

            <a class='form__link link' href="{{route('login.form')}}">Already have an account? Log in.</a>

            {!! Form::close() !!}


        </section>

    </main>


    {{-- @include('layouts.partials.footer') --}}

@endsection