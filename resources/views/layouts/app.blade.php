<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div {{ Auth::guest() ? '' : 'id="app"' }}>
		@guest
			<div class="quote-background"></div>
		@endguest

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('privacy') }}">Privacy Policy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
							<li class="nav-item dropdown">
								<a id="groupsDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" v-pre>
									Groups <span class="caret"></span>
								</a>
								
								<div class="dropdown-menu dropdown-menu-left">
									<a class="dropdown-item" href="{{ route('groups.index') }}">
										*ALL*
									</a>
									<div class="dropdown-divider"></div>
									@foreach(Auth::user()->groups as $group)
										<a class="dropdown-item" href="{{ route('groups.show', ['group' => $group->id]) }}">
											{{ $group->name }}
										</a>
									@endforeach
                                </div>
							</li>
							<li class="nav-item dropdown with-separator">
								<a class="nav-link" href="{{ route('buckets.index') }}">Buckets</a>
							</li>
							@can('view', App\Models\User::class)
							<li class="nav-item dropdown with-separator">
								<a class="nav-link" href="{{ route('members.index') }}">Users</a>
							</li>
							@endcan
							
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" v-pre>
                                    Logged as {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
					
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
    </div>
</body>
</html>
