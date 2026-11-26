<x-layout>

    <div class="card">

        <h1>Create Account</h1>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">

            @csrf

            <label for="username">Username</label>

            <input type="text" id="username" name="username"
                   value="{{ old('username') }}"
                   maxlength="50" required>

            <label for="email">Email</label>

            <input type="email" id="email" name="email"
                   value="{{ old('email') }}" required>

            <label for="password">Password</label>

            <input type="password" id="password"
                   name="password" minlength="8" required>

            <label for="password_confirmation">
                Confirm Password
            </label>

            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation" required>

            <button type="submit">Register</button>

        </form>

        <p>
            Already have an account?
            <a href="{{ route('login') }}">Login</a>
        </p>

    </div>

</x-layout>