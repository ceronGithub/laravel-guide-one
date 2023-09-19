<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <a class="navbar-brand" href="{{ route('store.index') }}">
                <img class="brand__logo" src="{{ url('/images/brand-logo.png') }}" alt="">
                <span class="brand__title">Globe Vendo</span>
            </a>
            <ul class="nav navbar-nav">
                <li
                    class="{{ str_contains(Route::currentRouteName(), 'store') || str_contains(Route::currentRouteName(), 'machine') ? 'active' : '' }}">
                    <a href="{{ route('store.index') }}"><i class="menu-icon fe fe-airplay"></i>Store List</a>
                </li>
                <li
                    class="{{ str_contains(Route::currentRouteName(), 'category') || str_contains(Route::currentRouteName(), 'category') ? 'active' : '' }}">
                    <a href="{{ route('category.index') }}"><i class="menu-icon fe fe-book-open"></i>Category List</a>
                </li>
                @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <li class="{{ str_contains(Route::currentRouteName(), 'products') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}"><i class="menu-icon fe fe-shopping-bag"></i>Products</a>
                    </li>
                @endif
                @if (auth()->user()->role_id != 5)
                    <li class="{{ str_contains(Route::currentRouteName(), 'reports') ? 'active' : '' }}">
                        <a href="{{ route('reports.index') }}"><i class="menu-icon fe fe-pie-chart"></i>Reports</a>
                    </li>
                    <li class="{{ str_contains(Route::currentRouteName(), 'analytics') ? 'active' : '' }}">
                        <a href="{{ route('analytics.index') }}"><i class="menu-icon fe fe-bar-chart"></i>Analytics</a>
                    </li>
                    <li class="{{ str_contains(Route::currentRouteName(), 'transactions') ? 'active' : '' }}">
                        <a href="{{ route('transactions.index') }}"><i
                                class="menu-icon fe fe-file-text"></i>Transactions</a>
                    </li>
                @endif
                @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <li class="{{ str_contains(Route::currentRouteName(), 'idle-video') ? 'active' : '' }}">
                        <a href="{{ route('idle-video.index') }}"><i class="menu-icon fe fe-video"></i>Idle Video</a>
                    </li>
                    <li class="{{ str_contains(Route::currentRouteName(), 'usermanagement') ? 'active' : '' }}">
                        <a href="{{ route('usermanagement.index') }}"><i class="menu-icon fe fe-users"></i>User
                            Management</a>
                    </li>
                    <li class="{{ str_contains(Route::currentRouteName(), 'audit') ? 'active' : '' }}">
                        <a href="{{ route('audit.index') }}"><i class="menu-icon fe fe-clipboard"></i>Audit Trail</a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</aside>
<div id="right-panel" class="right-panel">
    <header id="header" class="header">
        <div class="top-right">
            <div class="header-menu">
                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <img class="user-avatar rounded-circle"
                            src="https://dev74.htechcorp.net/staging/globe-easyhub-cms/public/images/admin.jpg"
                            alt="User Avatar">
                    </a>
                    <div class="user-menu dropdown-menu">
                        <a class="nav-link btn-logout">
                            <i class="fa fa-power-off"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <a id="menuToggle" class="menutoggle"><img class="menutoggle__icon"
            src=" {{ asset('images/icons/ic-menu-toggle.svg') }}" alt="Menu Toggle"></a>
