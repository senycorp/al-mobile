<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Al-Mobile</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('datatables/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('air-datepicker/css/datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css"; media="print" href="{{asset('css/print.css')}}">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Al-Mobile <span class="label label-danger">Development Preview</span>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login <i class="fa fa-sign-in"></i></a></li>
                            <!--<li><a href="{{ route('register') }}">Register</a></li>-->
                        @else
                            <li {!!  Request::is('/') ? 'class="active"' : '' !!}><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li {!!  Request::is('car*') ? 'class="active"' : '' !!}}><a href="{{ route('car_index') }}"><i class="fa fa-car"></i> Autos</a></li>
                            <li {!!  Request::is('carStock') ? 'class="active"' : '' !!}}><a href="{{ route('car_index_stock') }}"><i class="fa fa-bank"></i> Bestand</a></li>
                            <li {!!  Request::is('expense*') ? 'class="active"' : '' !!}}><a href="{{ route('expense_index') }}"><i class="fa fa-euro"></i> Aufwände</a></li>
                            <li {!!  Request::is('report') ? 'class="active"' : '' !!}}><a href="{{ route('report') }}"><i class="fa fa-area-chart"></i> Reports</a></li>
                            <li><a href="javascript:void(0)">|</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-user-circle"></i> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('datatables/datatables.js')}}"></script>
    <script src="{{asset('air-datepicker/js/datepicker.js')}}"></script>
    <script src="{{asset('air-datepicker/js/i18n/datepicker.de.js')}}"></script>
    <script src="{{asset('Chart.bundle.min.js')}}"></script>
    @stack('scripts')
</body>
</html>
