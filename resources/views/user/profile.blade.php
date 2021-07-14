@extends('layouts.app')

@section('page-title', 'User Profile')

@section('page-content')

    @include('layouts.partials.header')

    <main class="main">

        <section class="section profile-card user-profile">

          <div class="user-profile__main">

            <div class="user-profile__avatar">
              <img class="image" src="{{asset('assets/images/default-user.jpg')}}" alt="Default user">
            </div>

            <div class="user-profile__name">
              {{$user->fullName()}}
            </div>

            <div class="user-profile__joined date-format">
              Joined: {{$user->created_at}}
            </div>

          </div>

        </section>

    </main>

    @include('layouts.partials.footer')

@endsection
