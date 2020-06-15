<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title')</title>

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style>
.navbar-collapse {font-size: initial; color: initial; margin: initial;}
body {font-size:16pt; color:#999; margin: 10px;}
h1 {font-size:35pt; text-align:left; color:#999; margin-left:10px; letter-spacing:-4pt;}
hr {margin:20px; border-top: 1px dashed #ddd;}
th {background-color:#999; color:white; padding:5px 10px;}
td {border: solid 1px #aaa; color:#999; padding:5px 10px;}
# ul {font-size:12pt;}
# .menutitle {font-size:14pt; font-weight:bold; margin: 0px;}
.content {margin:10px;}
.footer {text-align:right; font-size:10pt; margin:20px; border-bottom:1px dashed #ddd; color:#ccc;}
</style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
					<a class="navbar-brand" href="{{ route('item') }}">
                        {{ config('app.name', 'Top') }}
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
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
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
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

<h1>@yield('title')</h1>
<hr size="1">
<div class="content">
@yield('content')
</div>
<div class="footer">
@yield('footer')
</div>
	</div>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
