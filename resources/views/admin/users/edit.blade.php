<x-layout>

    <section class="admin-section">

        <h1>Edit User</h1>

        <p>Update the selected user's account information.</p>

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

        <form action="{{ route('admin.users.update', $user) }}"
              method="POST"
              class="admin-form">

            @csrf
            @method('PUT')

            <label for="username">Username</label>

            <input type="text"
                   id="username"
                   name="username"
                   value="{{ old('username', $user->username) }}"
                   maxlength="50"
                   required>

            <label for="email">Email Address</label>

            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   maxlength="255"
                   required>

            <label for="password">New Password</label>

            <input type="password"
                   id="password"
                   name="password"
                   minlength="8"
                   autocomplete="new-password">

            <p>Leave blank to keep the current password.</p>

            <label for="password_confirmation">
                Confirm New Password
            </label>

            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   minlength="8"
                   autocomplete="new-password">

            <label for="role">User Role</label>

            <select id="role" name="role" required>

                <option value="user"
                    @selected(old('role', $user->role) === 'user')>
                    User
                </option>

                <option value="admin"
                    @selected(old('role', $user->role) === 'admin')>
                    Administrator
                </option>

            </select>

            <button type="submit" class="btn">
                Update User
            </button>

        </form>

    </section>

</x-layout>