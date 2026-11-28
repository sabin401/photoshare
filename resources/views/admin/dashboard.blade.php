<x-layout>

    <section class="admin-dashboard">

        <h1>Admin Dashboard</h1>

        <p>Manage PhotoShare users and published images.</p>

        <div class="admin-stats">

            <article class="stat-card">
                <h3>Total Users</h3>
                <h2>{{ $totalUsers }}</h2>
            </article>

            <article class="stat-card">
                <h3>Total Images</h3>
                <h2>{{ $totalImages }}</h2>
            </article>

            <article class="stat-card">
                <h3>Administrators</h3>
                <h2>{{ $totalAdmins }}</h2>
            </article>

        </div>

        <div class="admin-actions">

            <a href="{{ route('admin.users.index') }}"
               class="btn">
                Manage Users
            </a>

            <a href="{{ route('admin.images.index') }}"
               class="btn">
                Manage Images
            </a>

        </div>

    </section>

</x-layout>