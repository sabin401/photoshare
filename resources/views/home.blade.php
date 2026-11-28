<x-layout>

    <section class="hero">

        <h1>Welcome to PhotoShare</h1>

        <p>Capture moments. Share memories. Explore photography.</p>

        <div x-show="!$store.auth.loggedIn" x-cloak>

            <a href="{{ route('register') }}" class="btn">

                Get Started

            </a>

            <a href="{{ route('login') }}" class="btn">

                Login

            </a>

        </div>

        <div x-show="$store.auth.loggedIn" x-cloak>

            <p>

                Welcome back,

                <span x-text="$store.auth.user?.username"></span>

            </p>

            <a href="{{ route('images.create') }}" class="btn">

                Publish Image

            </a>

        </div>

    </section>

    <section class="dashboard"
             x-data="gallery('/api/images/latest')"
             x-init="loadImages()">

        <h2>Latest Images</h2>

        <p>Explore photographs shared by the PhotoShare community.</p>

        <p x-show="loading">Loading images...</p>

        <div x-show="message"
             x-text="message"
             class="error"
             role="status"
             x-cloak>
        </div>

        <p x-show="!loading && images.length === 0 && !message"
           x-cloak>

            No images have been published yet.

        </p>

        <div class="image-grid">

            <template x-for="image in images" x-bind:key="image.id">

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