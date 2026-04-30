<!-- Dashboard -->
<li class="nav-item">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="nav-icon bi bi-speedometer"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- Users Management -->
<li class="nav-item {{ request()->routeIs('admin.users.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-people-fill"></i>
        <p>
            Users
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>All Users</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Add New</p>
            </a>
        </li>
    </ul>
</li>

<!-- Courses Management -->
<li class="nav-item {{ request()->is('admin/courses*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('admin/courses*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-book-fill"></i>
        <p>
            Courses
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>All Courses</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Categories</p>
            </a>
        </li>
    </ul>
</li>

<!-- Orders -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon bi bi-cart-fill"></i>
        <p>Orders</p>
    </a>
</li>

<!-- Settings -->
<li class="nav-header">SETTINGS</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon bi bi-gear-fill"></i>
        <p>General Settings</p>
    </a>
</li>
