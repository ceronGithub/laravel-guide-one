@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/store/store.css') }}">
@endsection

@section('content')
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
    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">List of Stores</h4>
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
                        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                                data-target="#modal-create-store"> <i class="fe fe-plus mr-2"></i> Add New Store </button>
                        @endif
                    </div>
                    <div class="card table-wrapper">
                        <div class="table-stats order-table ov-h">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Store Name</th>
                                        <th>Store Location</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userStores as $userStore)
                                        <tr>
                                            <td class="serial">{{ $userStore->id }}</td>
                                            <td>{{ $userStore->name }}</td>
                                            <td>{{ $userStore->desc }}</td>
                                            <td>{{ $userStore->updated_at }}</td>
                                            <td>
                                                <a href="{{ route('store.show', $userStore->id) }}"
                                                    class="btn btn-round btn-light-orange tp-tooltip-manage"><i
                                                        class="fe fe-settings"></i></a>
                                                @if (auth()->user()->role_id <= 1)
                                                    <a href="{{ url('store.html') }}"
                                                        class="btn btn-round btn-blue tp-tooltip-edit" data-toggle="modal"
                                                        data-target="#modal-edit-store"
                                                        data-store-id="{{ $userStore->id }}"
                                                        data-store-name="{{ $userStore->name }}"
                                                        data-store-desc="{{ $userStore->desc }}"><i class="fe fe-edit"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $userStores->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="modal-create-store">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Store</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('store.create') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="store_name" class=" form-control-label">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter store name"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="store_code" class=" form-control-label">Store Location</label>
                                <input type="text" id="desc" name="desc" placeholder="Enter store location"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="modal-edit-store">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit store details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('store.update') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="store_name" class=" form-control-label">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter store name"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="store_code" class=" form-control-label">Store Location</label>
                                <input type="text" id="desc" name="desc"
                                    placeholder="Enter store location" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="store-id" id="store-id">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    @endsection

    @section('custom-scripts')
        <script src="{{ asset('/js/store/store.js') }}"></script>
    @endsection
