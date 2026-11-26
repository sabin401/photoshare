<x-layout>

    <div class="card">

        <h1>Login</h1>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">

            @csrf

            <label for="username">Username</label>

            <input type="text" id="username" name="username"
                   value="{{ old('username') }}" required>

            <label for="password">Password</label>

            <input type="password" id="password"
                   name="password" required>

            <button type="submit">Login</button>

        </form>

        <p>
            Don't have an account?
            <a href="{{ route('register') }}">Register</a>
        </p>

    </div>

</x-layout>