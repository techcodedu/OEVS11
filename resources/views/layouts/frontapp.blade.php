<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css" integrity="sha512-mxrUXSjrxl8vm5GwafxcqTrEwO1/oBNU25l20GODsysHReZo4uhVISzAKzaABH6/tTfAxZrY2FprmeAP5UZY8A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand"  href="{{ route('index') }}">GESTAAC</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                        {{ __('Courses') }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @foreach($categories as $category)
                            <a class="dropdown-item" href="{{ route('courses.category', $category->id) }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">{{ __('About') }}</a>
                      </li>
                </ul>
                <ul class="navbar-nav">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('signin') }}">Login</a>
                    </li>
                    @else
                    {{-- @auth
                    <a href="#" class="nav-link">
                        <i class="far fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge badge-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    @endauth --}}
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" v-pre>
                            <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle mr-2" style="width: 30px; height: 30px; object-fit: cover;">
                            Welcome {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#" id="logout-link" onclick="logout()">
                                {{ __('Logout') }}
                            </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#avatarModal">
                                {{ __('Upload Avatar') }}
                            </a>
                            {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form> --}}
                        </div>
                    </li>
                    <!-- Add this inside the <ul class="navbar-nav"> tag -->
                        @auth
                            @if (Auth::user()->role === 'student')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('student.applications') }}">My Applications</a>
                                </li>
                            @endif
                        @endauth
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="avatarModal" tabindex="-1" role="dialog" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Upload Avatar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update-avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="avatar">Choose avatar:</label>
                        <input type="file" class="form-control-file" id="avatar" name="avatar" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js" integrity="sha512-AJUWwfMxFuQLv1iPZOTZX0N/jTCIrLxyZjTRKQostNU71MzZTEPHjajSK20Kj1TwJELpP7gl+ShXw5brpnKwEg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    {{-- <script type="module" src="{{ asset('assets/app.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/adminlte.min.js') }}" defer></script> --}}
    
    @yield('scripts')
    <script>
        function logout() {
            event.preventDefault();

            // Perform an AJAX request to the logout route
            const request = new XMLHttpRequest();
            request.open('POST', '{{ route('logout') }}', true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            request.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            request.onreadystatechange = function() {
                if (request.readyState === 4 && request.status === 200) {
                    // Redirect the user to the desired page after logout (e.g., the homepage)
                    window.location.href = '{{ route('index') }}';
                }
            };
            request.send();
        }
    </script>

</body>
</html>
