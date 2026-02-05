<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('vendor/adminlte/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">{{ config('app.name', 'Admin') }}</span>
        </a>
    </div>

    <!-- Sidebar Wrapper -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false">
                @include('admin::partials.sidebar-menu')
            </ul>
        </nav>
    </div>
</aside>
