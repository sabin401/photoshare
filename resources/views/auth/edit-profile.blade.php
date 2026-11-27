<x-layout>

    <div class="card">

        <h1>Edit Profile</h1>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST"
              action="{{ route('profile.update') }}"
              enctype="multipart/form-data">

            @csrf

            <label for="username">Username</label>

            <input type="text" id="username"
                   name="username"
                   value="{{ old('username', $user->username) }}"
                   maxlength="50" required>

            <label for="email">Email</label>

            <input type="email" id="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required>

            <label for="password">New Password</label>

            <input type="password" id="password"
                   name="password" minlength="8">

            <label for="password_confirmation">
                Confirm Password
            </label>

            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation">

            <label for="avatar">Profile Image</label>

            <input type="file" id="avatar"
                   name="avatar"
                   accept="image/jpeg,image/png,image/gif">

            @if($user->avatar)

                <img src="{{ asset('storage/' . $user->avatar) }}"
                     class="profile-avatar"
                     alt="Profile image">

            @endif

            <button type="submit">Update Profile</button>

        </form>

    </div>

</x-layout>