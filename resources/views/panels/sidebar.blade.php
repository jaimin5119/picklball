<nav class="side-navbar">
    <div class="side-navbar-wrapper">

        <div class="sidenav-header d-flex">
            <a href="https://www.spotplus.fr/admin/dashboard">
                <div class="sidenav-header-inner text-center">
                    <img src="https://www.spotplus.fr/public/uploads/profile/admin-image.jpg" alt="person" class="img-fluid rounded-circle">
                    <h2 class="h5" style="color: #f4792f;">Pickle Heroes</h2>

                    <!-- <span>Administrator</span> -->
                </div>
            </a>
        </div>

        <div class="main-menu">
            <ul id="side-main-menu" class="side-menu list-unstyled">

                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
    <a href="{{ route('admin.dash') }}">
        <i class="fa fa-home me-2"></i>
        <span>Dashboard</span>
    </a>
</li>

<li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
    <a href="{{ route('admin.usersindex') }}">
        <i class="fa fa-users me-2"></i>
        <span>Manage Users</span>
    </a>
</li>

<li class="{{ Request::is('admin/notifications*') ? 'active' : '' }}">

    <a href="{{ route('admin.notificationsindex') }}">
        <i class="fa fa-bell me-2"></i>
        <span>Manage Notifications</span>
    </a>
</li>

<li class="{{ Request::is('admin/tournaments*') ? 'active' : '' }}">
    <a href="{{ route('admin.tournaments.index') }}">
        <i class="fa fa-trophy me-2"></i> {{-- tournament icon --}}
        <span>Manage Tournaments</span>
    </a>
</li>

<li class="{{ Request::is('admin/matches*') ? 'active' : '' }}">
    <a href="{{ route('admin.matches') }}">
        <i class="fa fa-gamepad me-2"></i> {{-- match/game icon --}}
        <span>Manage Matches</span>
    </a>
</li>


<li class="{{ Request::is('admin/profile*') ? 'active' : '' }}">
    <a href="{{ route('admin.admin_users') }}">
        <i class="fa fa-user-edit me-2"></i>
        <span>Edit Profile</span>
    </a>
</li>


                <!-- Uncomment if needed later
                <li>
                    <a href="{{ route('admin.list_cms_page') }}">
                        <i class="fa fa-file-alt me-2"></i>
                        <span>CMS Pages</span>
                    </a>
                </li>
                -->

            </ul>
        </div>
    </div>
</nav>
