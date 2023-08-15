<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{'Customer Loyalty Program'}}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- public/css/styles.css -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f4f1de;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
        }

        .footer {
            background-color: #f4f1de;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .card {
            background-color: #fff2cc;
            border-color: #ffb570;
            border-width: 2px;
        }

        .card-header {
            font-size: large;
            font-weight: bold;
        }
    </style>

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light fixed-top shadow-sm">
            <div class="container">
                <!-- Brand Link -->
                @if (Route::has('login') && !Auth::user())
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Customer Relationship System') }}
                </a>
                @else
                <a class="navbar-brand" href="{{ url('/customer') }}">
                    {{ config('app.name', 'Customer Relationship System') }}
                </a>
                @endif

                <!-- Toggler Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Content -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @if (Auth::user())
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Centered Tabs -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/customer/membership') }}">Membership</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/customer/coupons') }}">Coupons</a>
                        </li>
                        <li class="nav-item dropdown"> <!-- Modified Support tab to a dropdown -->
                            <a class="nav-link dropdown-toggle" href="#" id="supportDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Support
                            </a>
                            <div class="dropdown-menu" aria-labelledby="supportDropdown">
                                <a class="dropdown-item" href="{{ url('/customer/support') }}">Help & Support</a>
                                <a class="dropdown-item" href="{{ url('/customer/support/contactUs') }}">Contact Us</a>
                            </div>
                        </li>
                    </ul>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login') && !Auth::user())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login.customer') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register') && !Auth::user())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register.customer') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @elseif(Auth::user())
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->username }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('customer.profile') }}">Profile</a>
                                <a class="dropdown-item" href="{{ route('customer.checkout.history') }}">Checkout History</a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


        <main class="py-4">
            @yield('content')
        </main>

        <footer class="footer py-3">
            &copy; {{ date('Y') }} {{ 'Customer Relationship System for Retail Store' }}. All rights reserved.
        </footer>
    </div>
</body>

</html>