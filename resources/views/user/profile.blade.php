@extends('layouts.app')

@section('page-title', 'User Profile')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section profile-card user-profile">

          <div class="user-profile__main">

            <div class="user-profile__avatar">
              @if (isset($user->avatar))
                <img class="image" src="{{asset('storage/' . $user->avatar)}}" alt="User">
              @else 
                <img class="image" src="{{asset('assets/images/default-user.jpg')}}" alt="Default user">
              @endif
            </div>

            <div class="user-profile__name">
              <h1>
                {{$user->fullName()}}
              </h1>
            </div>

            <div class="user-profile__joined date-format">
              Joined: {{$user->created_at}}
            </div>

          </div>

          <div class="user__profile__avatar-update">

            <h2 class="profile-card__section-title">Avatar</h2>

            @if(Session::has('profile_updated'))

              <div class="message message--success">{{Session::get('profile_updated')}}</div>

            @endif

            {!! Form::open(['route' => 'profile.update_avatar', 'files' => true, 'class' => 'form']) !!}

            @error('update_error', 'avatar')

              <div class="form__error">
                {{$message}}
              </div>

            @enderror

            <div class="form__group">
                {!! Form::label('avatar', 'Avatar', ['class' => 'form__label']) !!}
                {!! Form::file('avatar', ['class' => 'form__field' . ($errors->has('avatar') ? ' form__field--invalid' : null)]) !!}
                @error('avatar')
                    <div class="form__error">
                        {{$message}}
                    </div>
                @enderror
            </div>

            <div class="form__group">
                {!! Form::submit('Update', ['class' => 'button button--blue form__submit']) !!}
            </div>

            {!! Form::close() !!}

            
          </div>

          <div class="user-profile__update">

            <h2 class="profile-card__section-title">Main info</h2>

            {!! Form::open(['route' => 'profile.update_main', 'class' => 'form']) !!}

            @error('update_error', 'main')

              <div class="form__error">
                {{$message}}
              </div>

            @enderror

            <div class="form__group">
                {!! Form::label('first_name', 'First Name', ['class' => 'form__label']) !!}
                {!! Form::text('first_name', $user->first_name, ['class' => 'form__field' . ($errors->has('first_name') ? ' form__field--invalid' : null)]) !!}
                @error('first_name')
                    <div class="form__error">
                        {{$message}}
                    </div>
                @enderror
            </div>

            <div class="form__group">
                {!! Form::label('last_name', 'Last Name', ['class' => 'form__label']) !!}
                {!! Form::text('last_name', $user->last_name, ['class' => 'form__field' . ($errors->has('last_name') ? ' form__field--invalid' : null)]) !!}
                @error('last_name')
                    <div class="form__error">
                        {{$message}}
                    </div>
                @enderror
            </div>

            <div class="form__group">
                {!! Form::submit('Update', ['class' => 'button button--blue form__submit']) !!}
            </div>

            {!! Form::close() !!}

          </div>

          @if($user->isEditor())

            <h2 class="profile-card__section-title">Editor info</h2>

            <div class="user-profile__editor">

              {!! Form::open(['route' => 'profile.update_editor', 'class' => 'form register-form']) !!}

              @error('update_error', 'editor')

              <div class="form__error">
                {{$message}}
              </div>

            @enderror

              <div class="form__group">
                {!! Form::label('bio', "Bio", ['class' => 'form__label']) !!}
                {!! Form::textarea('bio', $editor->bio, 
                    [
                        'class' => 'form__field form__field--full-width'  . ($errors->has('bio') ? ' form__field--invalid' : null), 
                    ]) !!}    
                @error('bio')
                    <div class="form__error">
                        {{$message}}
                    </div>
                @enderror
              </div>

              <div class="form__group">
                  {!! Form::submit('Update', ['class' => 'button button--blue form__submit']) !!}
              </div>

              {!! Form::close() !!}

            </div>

          @endif

        </section>

    </main>

    @include('layouts.partials.footer')

@endsection
