<x-layout>

    <section class="admin-section">

        <h1>Create Image</h1>

        <p>Publish a new image on behalf of a registered user.</p>

        <a href="{{ route('admin.images.index') }}" class="btn">
            Back to Manage Images
        </a>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.images.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="admin-form">

            @csrf

            <label for="user_id">Image Owner</label>

            <select id="user_id" name="user_id" required>

                <option value="">Select a user</option>

                @foreach ($users as $user)

                    <option value="{{ $user->id }}"
                        @selected(old('user_id') == $user->id)>

                        {{ $user->username }}

                    </option>

                @endforeach

            </select>

            <label for="title">Image Title</label>

            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title') }}"
                   maxlength="100"
                   required>

            <label for="description">Description</label>

            <textarea id="description"
                      name="description"
                      maxlength="1000"
                      rows="5">{{ old('description') }}</textarea>

            <label for="image">Upload Image</label>

            <input type="file"
                   id="image"
                   name="image"
                   accept=".jpg,.jpeg,.png,.webp"
                   required>

            <p>Accepted formats: JPG, JPEG, PNG and WEBP.
               Maximum size: 5 MB.</p>

            <label for="filter">Image Filter</label>

            <select id="filter" name="filter" required>

                @foreach (['none', 'grayscale', 'sepia', 'brightness', 'contrast'] as $filter)

                    <option value="{{ $filter }}"
                        @selected(old('filter', 'none') === $filter)>

                        {{ ucfirst($filter) }}

                    </option>

                @endforeach

            </select>

            <button type="submit" class="btn">
                Create Image
            </button>

        </form>

    </section>

</x-layout>