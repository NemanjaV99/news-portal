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
    <div class="header__auth">
        @guest
            <div class="header__sign-in">
                <a class="header__link link" href="/login">Sign In</a>
            </div>
        @endguest
        @auth
            <div class="header__profile">
                {{Auth::user()->first_name . ' ' . Auth::user()->last_name}}
            </div>
        @endauth
    </div>

</header>