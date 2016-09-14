<nav class="light-blue  lighten-1" role="navigation">
    <div class="nav-wrapper ">
        <a id="logo-container" href="{{ route('home') }}" class="brand-logo">Mage2</a>


        <ul class="right hide-on-med-and-down">

            <li><a href="{{ url('/') }}">Home</a></li>
            @include('layouts.category-tree',['categories', $categories])
            <li><a href="{{ route('cart.view') }}">Cart ({{$cart}})</a></li>
            <li><a href="{{ route('checkout.index') }}">Checkout</a></li>
            @if (Auth::guest())
                <li><a href="{{ route('front.login') }}">Login</a></li>
                <li><a href="{{ route('front.register') }}">Register</a></li>
            @else
                <li class="dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->full_name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('my-account.home') }}"><i class="fa fa-btn fa-user"></i> My Account</a></li>
                        <li><a href="{{ route('front.logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
                    </ul>
                </li>
            @endif
        </ul>
        
        <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
</nav>