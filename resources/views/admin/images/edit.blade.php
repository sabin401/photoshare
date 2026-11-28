<x-layout>

    <section class="admin-page">

        <h1>Edit Image</h1>

        <p>Update the selected published image.</p>

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

        <form action="{{ route('admin.images.update', $image) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <label for="user_id">Image Owner</label>

            <select name="user_id" id="user_id" required>

                @foreach ($users as $user)

                    <option value="{{ $user->id }}"
                        @selected(old('user_id', $image->user_id) == $user->id)>

                        {{ $user->username }}

                    </option>

                @endforeach

            </select>

            <label for="title">Image Title</label>

            <input type="text"
                   name="title"
                   id="title"
                   value="{{ old('title', $image->title) }}"
                   maxlength="100"
                   required>

            <label for="description">Description</label>

            <textarea name="description"
                      id="description"
                      rows="5"
                      maxlength="1000">{{ old('description', $image->description) }}</textarea>

            <label>Current Image</label>

            <img src="{{ asset('storage/' . $image->image_path) }}"
                 alt="{{ $image->title }}"
                 class="image-preview filter-{{ $image->filter }}">

            <label for="image">Replace Image</label>

            <input type="file"
                   name="image"
                   id="image"
                   accept=".jpg,.jpeg,.png,.webp">

            <p>Leave empty to keep the current image.</p>

            <label for="filter">Image Filter</label>

            <select name="filter" id="filter" required>

                @foreach (['none', 'grayscale', 'sepia', 'brightness', 'contrast'] as $filter)

                    <option value="{{ $filter }}"
                        @selected(old('filter', $image->filter) == $filter)>

                        {{ ucfirst($filter) }}

                    </option>

                @endforeach

            </select>

            <button type="submit" class="btn">
                Update Image
            </button>

        </form>

    </section>

</x-layout>