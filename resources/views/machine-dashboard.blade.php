<!doctype html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Globe Vending Machine CMS</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="https://dev74.htechcorp.net/staging/globe-easyhub-cms/public/images/favicon.png">
    <link rel="shortcut icon" href="https://dev74.htechcorp.net/staging/globe-easyhub-cms/public/images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ asset("http://localhost/globe-vendo-system/public/assets/css/cs-skin-elastic.css") }}">
    <link rel="stylesheet" href="{{ asset("http://localhost/globe-vendo-system/public/assets/css/style.css") }}">
    <!-- custom css by CRTV -->
    <link rel="stylesheet" href="{{ asset("http://localhost/globe-vendo-system/public/assets/css/custom.css") }}">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
        input[type=file]::-webkit-file-upload-button {
            visibility: hidden;
        }

        #image_src {
            max-width: 500px;
            max-height: 500px;
        }
    </style>
</head>

<body>
    <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <a class="navbar-brand" href="{{ url("#") }}">
                    <img class="brand__logo" src="{{ asset("http://localhost/globe-vendo-system/public/images/brand-logo.png") }}" alt="">
                    <span class="brand__title">Globe Vendo Management System</span>
                </a>
                <ul class="nav navbar-nav">
                    <!-- <li class="active">
                        <a href="{{ url("index.html") }}"><i class="menu-icon fe fe-pie-chart"></i>Dashboard</a>
                    </li> -->
                    <li class="">
                        <a href="{{ url("store.index") }}"><i class="menu-icon fe fe-airplay"></i>Stores </a>
                    </li>
                    <li>
                        <a href="{{ url("products.index") }}"><i class="menu-icon fe fe-shopping-bag"></i>Products</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->
    <!-- Left Panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <header id="header" class="header">
            <div class="top-right">
                <div class="header-menu">
                    <div class="user-area dropdown float-right">
                        <a href="{{ url("#") }}" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="https://dev74.htechcorp.net/staging/globe-easyhub-cms/public/images/admin.jpg" alt="User Avatar">
                        </a>
                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="{{ url("#") }}"><i class="fa fa-user"></i>My Profile</a>
                            <a class="nav-link" href="{{ url("#") }}"><i class="fa fa-bell-o"></i>Notifications <span class="count">13</span></a>
                            <a class="nav-link" href="{{ url("#") }}"><i class="fa fa-cog"></i>Settings</a>
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i>Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header> <!-- /header -->
        <!-- Menu Toggle -->
        <a id="menuToggle" class="menutoggle"><img class="menutoggle__icon" src="{{ asset("http://localhost/globe-vendo-system/public/images/icons/ic-menu-toggle.svg") }}" alt="Menu Toggle"></a>
        <!-- /Menu Toggle -->
        <!-- Page Title & Breadcrumbs -->
        <div class="page-header">
            <div class="row">
                <div class="col-12">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                    <div aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href={{ route('store.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Title & Breadcrumbs -->
        <div class="content">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        <a href="{{ url('store.index') }}" class="card text-white bg-flat-color-1">
                            <div class="card-body">
                                <div class="card-left pt-1 float-left">
                                    <h3 class="mb-0 fw-r">
                                        <span>{{ $stores->count() }}</span>
                                    </h3>
                                    <p class="text-light mt-1 m-0">Stores</p>
                                </div><!-- /.card-left -->
                                <div class="card-right float-right text-right">
                                    <i class="icon fade-5 icon-lg pe-7s-cart"></i>
                                </div><!-- /.card-right -->
                            </div>
                        </a>
                    </div>
                    <!--/.col-->
                    <div class="col-sm-6 col-lg-3">
                        <a href="#" class="card text-white bg-flat-color-6">
                            <div class="card-body">
                                <div class="card-left pt-1 float-left">
                                    <h3 class="mb-0 fw-r">
                                        <span class="count float-left">100</span>
                                        <span>%</span>
                                    </h3>
                                    <p class="text-light mt-1 m-0">Coming Soon...</p>
                                </div><!-- /.card-left -->
                                <div class="card-right float-right text-right">
                                    <i class="icon fade-5 icon-lg pe-7s-users"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!--/.col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card text-white bg-flat-color-3">
                            <div class="card-body">
                                <div class="card-left pt-1 float-left">
                                    <h3 class="mb-0 fw-r">
                                        <span class="count">0</span>
                                    </h3>
                                    <p class="text-light mt-1 m-0">Coming Soon...</p>
                                </div><!-- /.card-left -->
                                <div class="card-right float-right text-right">
                                    <i class="icon fade-5 icon-lg pe-7s-users"></i>
                                </div><!-- /.card-right -->
                            </div>
                        </div>
                    </div>
                    <!--/.col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card text-white bg-flat-color-2">
                            <div class="card-body">
                                <div class="card-left pt-1 float-left">
                                    <h3 class="mb-0 fw-r">
                                        <span class="count">0</span>
                                    </h3>
                                    <p class="text-light mt-1 m-0">Coming Soon...</p>
                                </div><!-- /.card-left -->
                                <div class="card-right float-right text-right">
                                    <i class="icon fade-5 icon-lg pe-7s-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/.col-->
                </div>
            </div>
        </div><!-- .content -->
        <div class="clearfix"></div>
    </div><!-- /#right-panel -->
    <!-- Right Panel -->
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="{{ asset("http://localhost/globe-vendo-system/public/assets/js/main.js") }}"></script>
</body>

</html>
