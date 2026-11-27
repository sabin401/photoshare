<x-layout>

    <section class="form-container">

        <h1>Edit Image</h1>

        <p>Update your published photograph.</p>

        <div id="response-message" role="status"></div>

        <form id="edit-image-form"
              action="{{ route('images.update', $image) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <label for="title">Image Title</label>

            <input type="text"
                   id="title"
                   name="title"
                   value="{{ $image->title }}"
                   maxlength="100"
                   required>

            <label for="description">Description</label>

            <textarea id="description"
                      name="description"
                      maxlength="1000"
                      rows="4">{{ $image->description }}</textarea>

            <label>Current Image</label>

            <img id="image-preview"
                 src="{{ asset('storage/' . $image->image_path) }}"
                 class="image-preview filter-{{ $image->filter }}"
                 alt="{{ $image->title }}">

            <label for="image">Replace Image (Optional)</label>

            <input type="file"
                   id="image"
                   name="image"
                   accept=".jpg,.jpeg,.png,.webp">

            <label for="filter">Select Filter</label>

            <select id="filter" name="filter">

                @foreach (['none', 'grayscale', 'sepia', 'brightness', 'contrast'] as $filter)

                    <option value="{{ $filter }}"
                        @selected($image->filter === $filter)>

                        {{ ucfirst($filter) }}

                    </option>

                @endforeach

            </select>

            <button type="submit">Save Changes</button>

        </form>

        <a href="{{ route('dashboard') }}">
            Back to Dashboard
        </a>

    </section>

</x-layout>