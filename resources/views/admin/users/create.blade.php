<x-layout>

    <section class="admin-section">

        <h1>Create User</h1>

        <p>Create a new PhotoShare user account.</p>

        <a href="{{ route('admin.users.index') }}" class="btn">
            Back to Manage Users
        </a>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}"
              method="POST"
              class="admin-form">

            @csrf

            <label for="username">Username</label>

            <input type="text"
                   id="username"
                   name="username"
                   value="{{ old('username') }}"
                   maxlength="50"
                   required>

            <label for="email">Email Address</label>

            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   maxlength="255"
                   required>

            <label for="password">Password</label>

            <input type="password"
                   id="password"
                   name="password"
                   minlength="8"
                   autocomplete="new-password"
                   required>

            <label for="password_confirmation">
                Confirm Password
            </label>

            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   minlength="8"
                   autocomplete="new-password"
                   required>

            <label for="role">User Role</label>

            <select id="role" name="role" required>

                <option value="user"
                    @selected(old('role', 'user') === 'user')>
                    User
                </option>

                <option value="admin"
                    @selected(old('role') === 'admin')>
                    Administrator
                </option>

            </select>

            <button type="submit" class="btn">
                Create User
            </button>

        </form>

    </section>

</x-layout>