<header class="header">
    
    <!-- Logo -->
    <div class="header__logo">
        <img class="header__img" src="{{asset('assets/images/logo.png')}}" alt="News Logo">
    </div>

    {{--
    <!-- Navigation -->
    <nav class="header__nav">
        <ul>
            <li>Latest</li>
            <li>World</li>
            <li>Business</li>
            <li>Tech</li>
            <li>Sport</li>
        </ul>
    </nav>
    --}}

    <!-- User options / Sign-in, Log-in options -->
    <div class="header__user">
        @guest
            <div class="header__auth">
                <a class="header__link link" href="/login">Sign In</a>
            </div>
        @endguest
        @auth
            <div class="header__dropdown dropdown">
                <div class="dropdown__button dropdown__button--light">
                    {{Auth::user()->fullName()}}
                    <i class="fas fa-sort-down dropdown__caret"></i>
                </div>
                <div class="dropdown__content">
                    {{-- <a class="dropdown__item" href="">My Account</a>
                    <a class="dropdown__item" href="">Editor Hub</a> --}}
                    {!! Form::open(['route' => 'logout']) !!}
                        {!! Form::submit('Log Out', ['class' => 'dropdown__item dropdown__item--btn']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        @endauth
    </div>

</header>