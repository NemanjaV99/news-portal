@extends('layouts.app')

@section('page-title', 'Sign In')

@section('page-content')

    <main class="main">

        <section class="section user-login">

            <h2 class="section__title section__title--center">Sign In</h2>

            {!! Form::open(['route' => 'login.process', 'class' => 'form login-form']) !!}

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

            {!! Form::submit('Login', ['class' => 'button form__submit']) !!}

            <a class='form__link link' href="{{route('register.form')}}">Don't have an account? Create one now.</a>

            {!! Form::close() !!}


        </section>

    </main>

    {{-- @include('layouts.partials.footer') --}}

@endsection