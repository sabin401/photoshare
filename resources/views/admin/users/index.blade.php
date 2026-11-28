<x-layout>

    <section class="admin-section">

        <h1>Manage Users</h1>

        <p>View and manage registered PhotoShare users.</p>

        <div class="admin-actions">

            <a href="{{ route('admin.dashboard') }}" class="btn">
                Back to Admin Dashboard
            </a>

            <a href="{{ route('admin.users.create') }}" class="btn">
                Create User
            </a>

        </div>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="table-responsive">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($users as $user)

                        <tr>

                            <td>{{ $user->id }}</td>

                            <td>{{ $user->username }}</td>

                            <td>{{ $user->email }}</td>

                            <td>{{ ucfirst($user->role) }}</td>

                            <td>

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn">
                                    Edit
                                </a>

                                @if ($user->id !== auth()->id())

                                    <form
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        class="inline-form"
                                        onsubmit="return confirm('Delete this user and all their published images?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger">
                                            Delete
                                        </button>

                                    </form>

                                @else

                                    <span>Current Account</span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">
                                No registered users found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</x-layout>