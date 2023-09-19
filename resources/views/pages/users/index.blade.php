@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/users/index.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h1>User Management</h1>
                </div>
                <div aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                    @if (auth()->user()->role_id == 1)
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                            data-target="#modal-create-user">
                            <i class="fe fe-plus mr-2"></i> Add User </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success">
            <span>{!! \Session::get('success') !!}</span>
        </div>
    @endif
    @if (Session::has('warning'))
        <div class="alert alert-warning">
            <span>{!! \Session::get('warning') !!}</span>
        </div>
    @endif
    @if (Session::has('missing'))
        <div class="alert alert-danger">
            <span>{!! \Session::get('missing') !!}</span>
        </div>
    @endif
    <div class="content">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-wrapper">
                        <div class="table-stats order-table ov-h">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td class="serial">{{ $users->firstItem() + $key }}</td>
                                            <td><span class="name">{{ $user->first_name }} {{ $user->last_name }}</span>
                                            </td>
                                            <td><span class="ct-data-email data-email">{{ $user->email }}</span></td>
                                            <td><span class="data-role-name">{{ $user->role->name }}</span></td>
                                            @if ($user->active == 1)
                                                <td><span class="data-active-name">Active</span></td>
                                            @else
                                                <td><span class="data-active-name">Not-active</span></td>
                                            @endif
                                            <td>
                                                @if (auth()->user()->role_id == 1)
                                                    <a href="#" class="btn btn-round btn-violet tp-tooltip-view"
                                                        data-toggle="modal" data-target="#modal-product-details"
                                                        data-view-name="#modal-product-details"
                                                        data-first-name="{{ $user->first_name }}"
                                                        data-last-name="{{ $user->last_name }}"><i
                                                            class="fe fe-eye"></i></a>

                                                    <h1 hidden>If user is super admin, store data will under going data
                                                        filtering</h1>
                                                    <a href="#"
                                                        class="btn btn-round btn-blue tp-tooltip-edit-superAdmin"
                                                        {{-- data-toggle="modal" data-target="#modal-edit-user-superAdmin" --}} data-toggle="modal"
                                                        data-target="#modal-edit-user-superAdmin"
                                                        data-pass-id="{{ $user->id }}"
                                                        data-first-name="{{ $user->first_name }}"
                                                        data-last-name="{{ $user->last_name }}"
                                                        data-role-id="{{ $user->role->id }}"
                                                        data-active-id="{{ $user->active }}"><i class="fe fe-edit"></i></a>
                                                    <a href="#" class="btn btn-round btn-red tp-tooltip-delete"
                                                        data-toggle="modal" data-id="{{ $user->id }}"
                                                        data-target="#modal-delete-confirmation" data><i
                                                            class="fe fe-trash"></i></a>
                                                @else
                                                    <a href="#" class="btn btn-round btn-violet tp-tooltip-view"
                                                        data-toggle="modal" data-target="#modal-product-details"
                                                        data-view-name="#modal-product-details"
                                                        data-first-name="{{ $user->first_name }}"
                                                        data-last-name="{{ $user->last_name }}"><i
                                                            class="fe fe-eye"></i></a>

                                                    <h1 hidden>If user is super admin, store data will under going data
                                                        filtering</h1>
                                                    <a href="#" class="btn btn-round btn-blue tp-tooltip-edit-admin"
                                                        data-toggle="modal" data-target="#modal-edit-user-admin"
                                                        data-pass-id="{{ $user->id }}"
                                                        data-first-name="{{ $user->first_name }}"
                                                        data-last-name="{{ $user->last_name }}"
                                                        data-role-id="{{ $user->role->id }}"
                                                        data-active-id="{{ $user->active }}"><i class="fe fe-edit"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->user()->role_id == 1)
                <div class="modal" tabindex="-1" role="dialog" id="modal-create-user">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div id="view-store"
                                    style="visibility: hidden; position: absolute; z-index: 1; width: 460px">
                                    <div class="ct-form__tab" role="tablist">
                                        <input type="radio" value="radio-create-user" id="radio-create-user"
                                            class="input--hidden" name="radio-create-type" checked>
                                        <label for="radio-create-user" class="ct-tab__item"> User </label>

                                        <input type="radio" value="radio-create-store" id="radio-create-store"
                                            class="input--hidden" name="radio-create-type">
                                        <label for="radio-create-store" class="ct-tab__item">Store</label>
                                    </div>
                                </div>
                                <div id="view-vendingmachine" style="visibility: hidden;">
                                    <div class="ct-form__tab" role="tablist">
                                        <input type="radio" value="radio-create-user" id="radio-create-user"
                                            class="input--hidden" name="radio-create-type" checked>
                                        <label for="radio-create-user" class="ct-tab__item"> User </label>

                                        <input type="radio" value="radio-create-vendingmachine"
                                            id="radio-create-vendingmachine" class="input--hidden"
                                            name="radio-create-type">
                                        <label for="radio-create-vendingmachine" class="ct-tab__item">Vending
                                            Machine</label>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('usermanagement.create') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="ct-addUser">
                                    <div class="modal-body ct-modal-form">
                                        <div class="form-group ct-form__group">
                                            <label for="slot_id" class=" form-control-label">First Name</label>
                                            <input type="text" id="name" name="first_name"
                                                placeholder="Enter First Name" class="form-control">
                                        </div>
                                        <div class="form-group ct-form__group">
                                            <label for="slot_id" class=" form-control-label">Last Name</label>
                                            <input type="text" id="name" name="last_name"
                                                placeholder="Enter Last Name" class="form-control">
                                        </div>
                                        <div class="form-group ct-form__group">
                                            <label for="slot_id" class=" form-control-label">User Name</label>
                                            <input type="text" id="name" name="username"
                                                placeholder="Enter Username" class="form-control">
                                        </div>

                                        <div class="form-group ct-form__group ct-form-dropdown">
                                            <label for="category" class="form-control-label">Role</label>
                                            <select class="form-control" name="role_id" id="create-selected-role">
                                                <option value="" disabled selected>Select Role</option>
                                                @forelse ($roles as $role)
                                                    <option value={{ $role->id }}>{{ $role->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            <input type="hidden" id="name" name="active"
                                                placeholder="Enter Product Name" class="form-control" value="1">
                                        </div>
                                        <div class="form-group ct-form__group">
                                            <label for="slot_id" class=" form-control-label">Email</label>
                                            <input type="email" id="email" name="email"
                                                placeholder="Enter Email Address" class="form-control">
                                        </div>
                                        <div class="form-group ct-form__group">
                                            <label for="slot_id" class=" form-control-label">Password</label>
                                            <input type="password" id="name" name="password"
                                                placeholder="*********" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="ct-vendingmachine">
                                    <div class="modal-body ct-modal-form">
                                        <div class="form-group ct-store__search">
                                            <input type="text" id="quantity" name="quantity" placeholder="Search"
                                                class="form-control" value="">
                                        </div>
                                        <h1 style="text-align: center;">Vending-machine</h1>
                                        <hr>
                                        @forelse ($machines as $i => $machine)
                                            <div class="form-group ct-store__list">
                                                <label for="vendingmachine{{ $i }}"
                                                    class="ct-list__container">{{ $machine->name }}
                                                    <input type="checkbox" id="vendingmachine{{ $i }}"
                                                        name="vendingmachine[]" value="{{ json_encode($machine->id) }}"
                                                        checked>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                                <div class="ct-store">
                                    <div class="modal-body ct-modal-form">
                                        <div class="form-group ct-store__search">
                                            <input type="text" id="quantity" name="quantity" placeholder="Search"
                                                class="form-control" value="">
                                        </div>
                                        <h1 style="text-align: center;">Store</h1>
                                        <hr>
                                        @forelse ($createStoreLink as $i => $store)
                                            <div class="form-group ct-store__list">
                                                <label for="store{{ $i }}"
                                                    class="ct-list__container">{{ $store->name }}
                                                    <input type="checkbox" id="store{{ $i }}" name="store[]"
                                                        value="{{ $store->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Create User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="modal" tabindex="-1" role="dialog" id="modal-product-details">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header ct-modal-header">
                            <h5 class="modal-title">Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="modal-body ct-modal-content">
                                <table>
                                    <tr>
                                        <td class="ct-modal-label">First Name:</td>
                                        <td class="ct-modal-data" id="data-first-name"></td>
                                    </tr>
                                    <tr>
                                        <td class="ct-modal-label">Last Name:</td>
                                        <td class="ct-modal-data" id="data-last-name"></td>
                                    </tr>
                                    <tr>
                                        <td class="ct-modal-label">Email Address:</td>
                                        <td class="ct-modal-data" id="data-email"></td>
                                    </tr>
                                    <tr>
                                        <td class="ct-modal-label">Role</td>
                                        <td class="ct-modal-data" id="data-role-name"></td>
                                    </tr>
                                    <tr>
                                        <td class="ct-modal-label">Status</td>
                                        <td class="ct-modal-data" id="data-active-name"><span
                                                class="status-success"></span></td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body ct-modal-content">
                        <table>
                            <tr>
                                <td class="ct-modal-label">Name:</td>
                                <td class="ct-modal-data"> Jane Doe</td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Email Address:</td>
                                <td class="ct-modal-data">sample1@gmail.com</td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Role</td>
                                <td class="ct-modal-data">Dispenser</td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Status</td>
                                <td class="ct-modal-data"><span class="status-success">Active</span></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>

            @if (auth()->user()->role_id == 1)
                <div class="modal" tabindex="-1" role="dialog" id="modal-edit-user-superAdmin">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="ct-form__wrapper">
                                <div id="vendingTabSuperAdmin"
                                    style="visibility: hidden; position: absolute; z-index: 1; width: 460px">
                                    <div class="ct-form__tab" role="tablist">
                                        <input type="radio" value="radio-edit-user" id="radio-edit-user"
                                            class="input--hidden" name="radio-edit-type" checked>
                                        <label for="radio-edit-user" class="ct-tab__item"> User </label>

                                        <input type="radio" value="radio-edit-vending" id="radio-edit-vending"
                                            class="input--hidden" name="radio-edit-type">
                                        <label for="radio-edit-vending" class="ct-tab__item">Vending Machine</label>
                                    </div>
                                </div>
                                <div id="storeTabSuperAdmin" style="visibility: hidden;">
                                    <div class="ct-form__tab" role="tablist">
                                        <input type="radio" value="radio-edit-user" id="radio-edit-user"
                                            class="input--hidden" name="radio-edit-type" checked>
                                        <label for="radio-edit-user" class="ct-tab__item"> User </label>

                                        <input type="radio" value="radio-edit-store" id="radio-edit-store"
                                            class="input--hidden" name="radio-edit-type">
                                        <label for="radio-edit-store" class="ct-tab__item">Store</label>
                                    </div>
                                </div>
                                <form action="{{ route('usermanagement.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="ct-user__container">
                                        <div class="modal-body ct-modal-form">
                                            <input type="hidden" id="superAdmin-ID" name="ID"
                                                placeholder="Enter store code" class="form-control" value="">
                                            <div class="form-group">
                                                <label for="slot_id" class=" form-control-label">First Name</label>
                                                <input type="text" id="superAdmin-data-first-name" name="firstName"
                                                    placeholder="Enter store code" class="form-control" value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="slot_id" class=" form-control-label">Last Name</label>
                                                <input type="text" id="superAdmin-data-last-name" name="lastName"
                                                    placeholder="Enter store code" class="form-control" value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity" class=" form-control-label">Email Address</label>
                                                <input type="text" id="superAdmin-data-email" name="emailAddress"
                                                    placeholder="Enter store name" class="form-control"
                                                    value="sample@gmail.com">
                                            </div>
                                            <div class="form-group ct-form-dropdown">
                                                <label for="assign-item" class="form-control-label">Role</label>
                                                <select class="form-control" name="assignRole"
                                                    id="superAdmin-data-role-id">
                                                    <option value="" disabled selected>Select Items</option>
                                                    @forelse ($roles as $i => $role)
                                                        <option value={{ $role->id }}>{{ $role->name }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="form-group ct-form-dropdown">
                                                <label for="assign-item" class="form-control-label">Status</label>
                                                <select class="form-control" name="assignStatus"
                                                    id="superAdmin-data-active-id">
                                                    <option value="1" selected>Active</option>
                                                    <option value="2">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ct-store__container">
                                        <div class="modal-body ct-modal-form">

                                            <div class="wrapper" style="position: relative;">

                                                <div id="roleAdminDisplayStore" style="visibility: hidden;">
                                                    <div class="form-group ct-store__search">
                                                        <input type="text" id="quantity" name="quantity"
                                                            placeholder="Search" class="form-control" value="">
                                                    </div>
                                                    <h3 style="text-align: center;"> Store List</h3>
                                                    <hr>
                                                    <div id="linkedAndUnlinkedSuperAdminAccount">
                                                    </div>
                                                    <br>
                                                    <div id="displayedLinkedStores">
                                                    </div>
                                                    <hr>
                                                    <p hidden>Note: if filteredstores value is not empty array. display
                                                        this.</p>
                                                    <input type="submit" id="ZeroUserId" form="filter" placeholder=""
                                                        value="0" hidden>
                                                    <label for="ZeroUserId" id="for_Unfilter"></label>

                                                    <p hidden>Note: if filteredstores value is empty array. display this.
                                                    </p>
                                                    <input type="submit" id="getUserId" form="filter" placeholder=""
                                                        value="..." hidden>
                                                    <label for="getUserId" id="for_filter"></label>
                                                </div>

                                                <div id="changeRoleBackToAdminFromSuperAdmin"
                                                    style="visibility: hidden; position: absolute; top: 0; width: 410px;">
                                                    <div class="form-group ct-store__search">
                                                        <input type="text" id="quantity" name="quantity"
                                                            placeholder="Search" class="form-control" value="">
                                                    </div>
                                                    <h1 style="text-align: center;">Store</h1>
                                                    <hr>
                                                    @forelse ($createStoreLink as $i => $store)
                                                        <div class="form-group ct-store__list">
                                                            <label for="reLinkToUser{{ $i }}"
                                                                class="ct-list__container">{{ $store->name }}
                                                                <input type="checkbox"
                                                                    id="reLinkToUser{{ $i }}"
                                                                    name="forLinkToUser[]" value="{{ $store->id }}">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="ct-vending__container">
                                        <div class="modal-body ct-modal-form">
                                            <div id="roleSuperAdminDisplayVending" style="visibility: hidden;">
                                                <div class="form-group ct-store__search">
                                                    <input type="text" id="quantity" name="quantity"
                                                        placeholder="Search" class="form-control" value="">
                                                </div>
                                                <h4 style="text-align: center;">Linked Vending-machine</h4>
                                                <hr>
                                                @forelse ($machines as $i => $machine)
                                                    <div class="form-group ct-store__list">
                                                        <label for="unlinkVendingMachine{{ $i }}"
                                                            class="ct-list__container">{{ $machine->name }}
                                                            <input type="checkbox"
                                                                id="unlinkVendingMachine{{ $i }}"
                                                                name="unlinkVendingMachine[]"
                                                                value="{{ json_encode($machine->id) }}" checked>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="modal" tabindex="-1" role="dialog" id="modal-edit-user-admin">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="ct-form__tab" role="tablist">
                                    <input type="radio" value="radio-edit-user" id="radio-edit-user"
                                        class="input--hidden" name="radio-edit-type" checked>
                                    <label for="radio-edit-user" class="ct-tab__item"> User </label>

                                    <input type="radio" value="radio-edit-store" id="radio-edit-store"
                                        class="input--hidden" name="radio-edit-type">
                                    <label for="radio-edit-store" class="ct-tab__item">Store</label>
                                </div>
                            </div>
                            <form action="{{ route('usermanagement.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="ct-user__container">
                                    <div class="modal-body ct-modal-form">
                                        <input type="hidden" id="admin-ID" name="ID"
                                            placeholder="Enter store code" class="form-control" value="">
                                        <div class="form-group">
                                            <label for="slot_id" class=" form-control-label">First Name</label>
                                            <input type="text" id="admin-data-first-name" name="firstName"
                                                placeholder="Enter store code" class="form-control" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="slot_id" class=" form-control-label">Last Name</label>
                                            <input type="text" id="admin-data-last-name" name="lastName"
                                                placeholder="Enter store code" class="form-control" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity" class=" form-control-label">Email Address</label>
                                            <input type="text" id="admin-data-email" name="emailAddress"
                                                placeholder="Enter store name" class="form-control"
                                                value="sample@gmail.com">
                                        </div>
                                        @if (auth()->user()->role_id == 1)
                                            <div class="form-group ct-form-dropdown">
                                                <label for="assign-item" class="form-control-label">Role</label>
                                                <select class="form-control" name="assignRole" id="admin-data-role-id">
                                                    <option value="" disabled selected>Select Items</option>
                                                    @forelse ($roles as $i => $role)
                                                        <option value={{ $role->id }}>{{ $role->name }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="form-group ct-form-dropdown">
                                                <label for="assign-item" class="form-control-label">Status</label>
                                                <select class="form-control" name="assignStatus"
                                                    id="admin-data-active-id">
                                                    <option value="1" selected>Active</option>
                                                    <option value="2">Inactive</option>
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="ct-store__container">
                                    <div class="modal-body ct-modal-form">
                                        <div class="form-group ct-store__search">
                                            <input type="text" id="quantity" name="quantity" placeholder="Search"
                                                class="form-control" value="">
                                        </div>
                                        <h4 style="text-align: center;">Store list</h4>
                                        <hr>
                                        @forelse ($LinkedStores as $i => $filteredstores)
                                            <div class="form-group ct-store__list">
                                                <label for="unlinkStore{{ $i }}"
                                                    class="ct-list__container">{{ $filteredstores->name }}
                                                    <input type="checkbox" id="unlinkStore{{ $i }}"
                                                        name="unlinkStore[]" value="{{ $filteredstores->id }}" checked>
                                                    <span class="checkmark"></span>
                                                </label>
                                                <input type="text" name="removeStore[]" value=""
                                                    id="UnlinkStore__{{ $filteredstores->id }}" hidden>
                                            </div>
                                        @empty
                                            <p>No Data Exist..</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            <form id="filter" action="" method="GET">
                @csrf
                <input type="hidden" id="getID" name="getID" placeholder="Enter Product Code"
                    class="form-control" value="Product One">
            </form>
            <div class="modal" tabindex="-1" role="dialog" id="modal-delete-confirmation">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal__illus">
                                <lottie-player src="{{ asset('assets/icons/confirmation.json') }}"
                                    background="transparent" speed="1" style="width: 150px; height: 150px;" loop
                                    autoplay></lottie-player>
                            </div>
                            <h5 class="modal-title">Are you sure?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('usermanagement.delete') }}" method="GET">
                            <div class="modal-body mb-0">
                                <p class="text-center m-auto">Do you really want to delete this record? This process cannot
                                    be undone.</p>
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="#" class="btn btn-light mr-2" data-dismiss="modal">Cancel</a>
                                    <input type="hidden" name="id" id="id" value="">
                                    <button type="submit" class="btn btn-danger">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        @endsection

        @section('custom-scripts')
            <script src="{{ asset('/js/users/users.js') }}"></script>
        @endsection
