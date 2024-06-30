<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4." />
    <meta name="author" content="Creative Tim" />
    <title>{{ $title }}</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/') }}assets/favicon_gmj/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/') }}assets/favicon_gmj/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/') }}assets/favicon_gmj/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('/') }}assets/favicon_gmj/site.webmanifest">
    <link rel="mask-icon" href="{{ asset('/') }}assets/favicon_gmj/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/nucleo/css/nucleo.css" type="text/css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/@fortawesome/fontawesome-free/css/all.min.css"
        type="text/css" />
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ asset('/') }}assets/css/argon.css?v=1.0.0" type="text/css" />
</head>

<body class="bg-default">

    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-gradient-primary py-lg-6 pt-lg-9 py-7">
            <div class="container">
                <div class="header-body mb-7 text-center">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-4">
                            <h1 class="text-white">PT GENTA MULTI JAYYA</h1>
                            <h3 class="text-white">Accounting Information System</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">

                <div class="col-lg-5 col-md-7">

                    <div class="card bg-secondary mb-0 border-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            @error('msg')
                                <div class="alert alert-danger alert-dismissible fade show d-flex py-3" role="alert">
                                    <span class="alert-text pt-1"><strong>Username/Password Is Invalid !</strong></span>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @enderror
                            <div class="text-muted mb-2 pb-3 text-center">
                                <h3>Please Log In With Your Credential</h3>
                            </div>
                            <form class="mt-3" role="form" action="{{ url('login') }}" method="post">
                                @csrf
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                        </div>
                                        <input class="form-control username" placeholder="Username" type="text"
                                            name="username" value="{{ old('username') }}" />
                                    </div>
                                    @error('username')
                                        <small class="text-danger">Username Cannot Be Blank</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Password" type="password"
                                            name="password" />
                                    </div>
                                    @error('password')
                                        <small class="text-danger">Password Cannot Be Blank</small>
                                    @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary my-4">Log in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="py-5" id="footer-main">
        <div class="container">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-12 col-md-12">
                    <div class="text-center">
                        &copy; 2024 <a href="https://emsregistrars.co.id/sertifikasi-iso/pt-genta-multi-jayya-619"
                            class="font-weight-bold ml-1" target="_blank">PT Genta Multi
                            Jayya</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Argon Scripts -->
    <!-- Core -->
    <script src="{{ asset('/') }}assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/js-cookie/js.cookie.js"></script>
    <script src="{{ asset('/') }}assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/lavalamp/js/jquery.lavalamp.min.js"></script>
    <!-- Argon JS -->
    <script src="{{ asset('/') }}assets/js/argon.js?v=1.0.0"></script>
    <script></script>
</body>


</html>
