<x-layout>

    <section class="dashboard"
             x-data="gallery('/api/images/others')"
             x-init="loadImages()">

        <div class="dashboard-header">

            <div>

                <h1>Community Gallery</h1>

                <p>Discover photographs shared by other users.</p>

            </div>

            <a href="{{ route('dashboard') }}" class="btn">

                My Images

            </a>

        </div>

        <p x-show="loading">Loading community images...</p>

        <div x-show="message"
             x-text="message"
             class="error"
             role="status"
             x-cloak>
        </div>

        <p x-show="!loading && images.length === 0 && !message"
           x-cloak>

            Other users have not published any images yet.

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

                        <p class="image-author">

                            Published by:

                            <strong x-text="image.username"></strong>

                        </p>

                        <small x-text="image.created_at"></small>

                    </div>

                </article>

            </template>

        </div>

    </section>

</x-layout>