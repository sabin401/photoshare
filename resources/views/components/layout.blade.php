<!DOCTYPE html>
<html lang="en">

<head>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    @auth

        @php
            if (
                !session()->has('image_api_token') ||
                session('image_api_owner') !== auth()->id()
            ) {
                session([
                    'image_api_token' => bin2hex(random_bytes(32)),
                    'image_api_owner' => auth()->id()
                ]);
            }
        @endphp

        <meta name="api-token"
              content="{{ session('image_api_token') }}">

    @endauth

    <title>PhotoShare</title>

    <link rel="stylesheet"
          href="{{ asset('css/app.css') }}">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="{{ asset('js/alpine.min.js') }}" defer></script>

</head>

<body x-data>

    <nav>

        <a href="{{ route('home') }}">PhotoShare</a>

        <div>

            @auth

                <a href="{{ route('dashboard') }}">Dashboard</a>

                <a href="{{ route('images.create') }}">Publish Image</a>

                <a href="{{ route('images.community') }}">Community</a>

                <a href="{{ route('profile.edit') }}">Edit Profile</a>

                @if(auth()->user()->avatar)

                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                         class="nav-avatar"
                         alt="Profile image">

                @endif

                <form action="{{ route('logout') }}" method="POST">

                    @csrf

                    <button type="submit">Logout</button>

                </form>

            @else

                <a href="{{ route('login') }}">Login</a>

                <a href="{{ route('register') }}">Register</a>

            @endauth

        </div>

    </nav>

    <main>

        {{ $slot }}

    </main>

    <footer>

        <p>&copy; {{ date('Y') }} PhotoShare</p>

    </footer>

</body>

</html>