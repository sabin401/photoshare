<x-layout>

    <section class="admin-section">

        <h1>Manage Images</h1>

        <p>View and manage all published PhotoShare images.</p>

        <div class="admin-actions">

            <a href="{{ route('admin.dashboard') }}" class="btn">
                Back to Admin Dashboard
            </a>

            <a href="{{ route('admin.images.create') }}" class="btn">
                Create Image
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
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Owner</th>
                        <th>Filter</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($images as $image)

                        <tr>

                            <td>{{ $image->id }}</td>

                            <td>
                                <img
                                    src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="{{ $image->title }}"
                                    class="admin-thumbnail"
                                    width="100">
                            </td>

                            <td>{{ $image->title }}</td>

                            <td>{{ $image->description }}</td>

                            <td>{{ $image->user->username }}</td>

                            <td>{{ ucfirst($image->filter) }}</td>

                            <td>

                                <a href="{{ route('admin.images.edit', $image) }}"
                                   class="btn">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.images.destroy', $image) }}"
                                    method="POST"
                                    class="inline-form"
                                    onsubmit="return confirm('Are you sure you want to delete this image?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                No published images found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</x-layout>