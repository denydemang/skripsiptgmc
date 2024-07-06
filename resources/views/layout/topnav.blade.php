<!-- Topnav -->
<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
    <div class="container-fluid">
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-left ml-md-auto w-100">
                <li class="nav-item d-xl-none ml-0">
                    <!-- Sidenav toggler -->
                    <div class="sidenav-toggler sidenav-toggler-dark pr-3" data-action="sidenav-pin"
                        data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="Image placeholder"
                                    src="{{ asset('/') }}assets/favicon_gmj/user_7718888.png" />
                            </span>
                            <div class="media-body d-none d-lg-block ml-2">
                                <span class="font-weight-bold mb-0 text-sm">{{ $users->name }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome {{ $users->username }}! </h6>
                        </div>
                        <button class="dropdown-item btnlogout">
                            <i class="ni ni-user-run"></i>
                            <span>Logout</span>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <script src="{{ asset('/') }}js/dashoard/logout.js" type="module"></script>
</nav>
