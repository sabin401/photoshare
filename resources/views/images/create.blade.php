<x-layout>

    <section class="form-container"
             x-data="publishImage">

        <h1>Publish Image</h1>

        <p>Upload and share your favourite photographs.</p>

        <div x-show="message"
             x-text="message"
             x-bind:class="success ? 'success' : 'error'"
             role="status"
             x-cloak>
        </div>

        <form action="{{ route('images.store') }}"
              method="POST"
              enctype="multipart/form-data"
              x-on:submit.prevent="publish($event)">

            @csrf

            <label for="title">Image Title</label>

            <input type="text"
                   id="title"
                   name="title"
                   x-model="title"
                   maxlength="100"
                   required>

            <label for="description">Description</label>

            <textarea id="description"
                      name="description"
                      x-model="description"
                      maxlength="1000"
                      rows="4"></textarea>

            <label for="image">Select Image</label>

            <input type="file"
                   id="image"
                   name="image"
                   accept=".jpg,.jpeg,.png,.webp"
                   x-on:change="previewImage($event)"
                   required>

            <template x-if="preview">

                <img x-bind:src="preview"
                     x-bind:class="'image-preview filter-' + filter"
                     alt="Image preview">

            </template>

            <label for="filter">Select Filter</label>

            <select id="filter"
                    name="filter"
                    x-model="filter">

                <option value="none">Original</option>

                <option value="grayscale">Grayscale</option>

                <option value="sepia">Sepia</option>

                <option value="brightness">Brightness</option>

                <option value="contrast">Contrast</option>

            </select>

            <button type="submit"
                    x-bind:disabled="submitting"
                    x-text="submitting ? 'Publishing...' : 'Publish Image'">

                Publish Image

            </button>

        </form>

        <a href="{{ route('dashboard') }}">
            Back to Dashboard
        </a>

    </section>

</x-layout>