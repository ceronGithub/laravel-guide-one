@extends('layouts.app')

@section('content')
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
                    <span class="brand__title">Globe CMS</span>
                </a>
                <ul class="nav navbar-nav">
                    <!-- <li class="">
                        <a href="{{ url("index.html") }}"><i class="menu-icon fe fe-pie-chart"></i>Dashboard</a>
                    </li> -->
                    <li class="active">
                        <a href="#"><i class="menu-icon fe fe-airplay"></i>Store List</a>
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
                            <a class="nav-link" href="{{ url("#") }}"><i class="fa fa-power-off"></i>Logout</a>
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
                        <h1>Store List</h1>
                    </div>
                    <div aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Store List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Title & Breadcrumbs -->
        <div class="content">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- ***** NOTE: placed the title & btn outside of card ***** -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title">List of Stores</h4>
                            <!-- calling StoreListController index func-->
                            @isset($warning)
                                <div class="alert alert-warning">
                                    <strong>{{ $warning }}</strong>
                                </div>
                            @endisset
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal"> <i class="fe fe-plus mr-2"></i> Add New Store </button>
                        </div>
                        <!-- ***** NOTE: added table-wrapper class ***** -->
                        <div class="card table-wrapper">
                            <div class="table-stats order-table ov-h">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <!--
                                            <th class="serial">#</th>
                                            <th>Code</th>
                                            <th>Store Name</th>
                                            <th>Last Updated</th>
                                            <th>Actions</th>
                                            -->
                                            <th class="serial">#</th>
                                            <th>Store Name</th>
                                            <th>Store Description</th>
                                            <th>Last Updated</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stores as $store)
                                            <tr>
                                                <td class="serial">{{$store->id}}</td>
                                                <td>{{$store->name}}</td>
                                                <td>{{$store->desc}}</td>
                                                <td>{{$store->updated_at}}</td>
                                                <td>
                                                    <a href="{{ route("store.show", $store->id) }}" class="btn btn-round btn-light-orange tp-tooltip-manage"><i class="fe fe-settings"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!--
                                        <tr>
                                            <td class="serial">2</td>
                                            <td>
                                                <a href="{{ url("#") }}">
                                                    <span class="name">GV0002</span>
                                                </a>
                                            </td>
                                            <td>SM MANILA</td>
                                            <td>Jul 27, 2020 12:55:13</td>
                                            <td>
                                                <a href="{{ url("store.show-vending-machine") }}" class="btn btn-round btn-light-orange tp-tooltip-manage"><i class="fe fe-settings"></i></a>
                                            </td>
                                        </tr>
                                        -->
                                    </tbody>
                                </table>

                            </div> <!-- $stores->links()
                                /.table-stats -->
                        </div>
                    </div>
                </div>
            </div><!-- .content -->
            <!-- modal -->
            <!-- *****
                NOTE:
                - removed card
                - used modal-header for titles
            ***** -->
            <div class="modal" tabindex="-1" role="dialog" id="exampleModal">                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Store</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!--<form action="https://dev74.htechcorp.net/staging/globe-easyhub-cms/public/new-stores" method="POST" enctype="multipart/form-data"> -->
                        <form action="{{ route('store.index') }}" method="POST">
                            @csrf
                            <!--<input type="hidden" name="_token" value="YKOb8AnX0e6IKM5IH8uuW6s7Qnntrpf7ZNlvRLHE">-->
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="store_code" class=" form-control-label">Description</label>
                                    <input type="text" id="desc" name="desc" placeholder="Enter store description" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="store_name" class=" form-control-label">Name</label>
                                    <input type="text" id="name" name="name" placeholder="Enter store name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <input type="hidden" id="user_id" name="user_id" placeholder="Enter User ID" class="form-control" value="{{ $store->user_id }}">
                                </div>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /modal -->
            <div class="clearfix"></div>
        </div><!-- /#right-panel -->
        <!-- Right Panel -->
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
        <script src="{{ asset("http://localhost/globe-vendo-system/public/assets/js/main.js") }}"></script>
        <!-- Tippy Tooltip: https://atomiks.github.io/tippyjs/ -->
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
        <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
        <!-- Tooltip -->
        <script>
            tippy('.tp-tooltip-download', {
                content: 'Download Zip File',
                theme: 'custom',
                arrow: false,
            });
            tippy('.tp-tooltip-manage', {
                content: 'Manage',
                theme: 'custom',
                arrow: false,
            });
        </script>
</body>

</html>

@endsection
