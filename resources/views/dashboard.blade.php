<x-layout>

    <section class="dashboard"
             x-data="gallery('/api/images/mine')"
             x-init="loadImages()">

        <div class="dashboard-header">

            <div>

                <h1>

                    Welcome,

                    <span x-text="$store.auth.user?.username"></span>

                </h1>

                <p>Manage your published images.</p>

            </div>

            <a href="{{ route('images.create') }}"
               class="btn">

                Publish Image

            </a>

        </div>

        <div x-show="message"
             x-text="message"
             x-bind:class="success ? 'success' : 'error'"
             role="status"
             x-cloak>
        </div>

        <h2>My Images</h2>

        <p x-show="loading">Loading your images...</p>

        <p x-show="!loading && images.length === 0 && !message"
           x-cloak>

            You have not published any images yet.

        </p>

        <div class="image-grid">

            <template x-for="image in images"
                      x-bind:key="image.id">

                <article class="image-card">

                    <img x-bind:src="image.image_url"
                         x-bind:alt="image.title"
                         x-bind:class="'gallery-image filter-' + image.filter">

                    <div class="image-details">

                        <h3 x-text="image.title"></h3>

                        <p x-text="image.description"></p>

                        <small x-text="image.created_at"></small>

                        <div class="image-actions">

                            <a x-bind:href="image.edit_url"
                               class="edit-btn">

                                Edit

                            </a>

                            <button type="button"
                                    class="delete-btn"
                                    x-on:click="deleteImage(image)">

                                Delete

                            </button>

                        </div>

                    </div>

                </article>

            </template>

        </div>

    </section>

</x-layout>