@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/store/store.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h1>Category List</h1>
                </div>
                <div aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category List</li>
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
                        <h4 class="card-title">List of Category</h4>
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
                                data-target="#modal-create-category"> <i class="fe fe-plus mr-2"></i> Add New Category </button>
                        @endif
                    </div>
                    <div class="card table-wrapper">
                        <div class="table-stats order-table ov-h">
                            <table class="table ">
                                <thead>
                                    <tr>                                        
                                        <th>Category ID</th>
                                        <th>Category Name</th>
                                        <th>Category Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td class="serial">{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->desc }}</td>                                            
                                            <td>
                                                <a href="#"
                                                    class="btn btn-round btn-light-orange tp-tooltip-manage"><i
                                                        class="fe fe-settings"></i>
                                                </a>
                                                <a href="#"
                                                    class="btn btn-round btn-blue tp-tooltip-edit" data-toggle="modal"
                                                    data-target="#modal-edit-category"
                                                    data-category-id="{{ $category->id }}"
                                                    data-category-name="{{ $category->name }}"
                                                    data-category-desc="{{ $category->desc }}">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                <a href="#"
                                                    class="btn btn-round btn-red tp-tooltip-delete"
                                                    data-toggle="modal"
                                                    data-target="#modal-delete-confirmation"
                                                    data-id="{{ $category->id }}"><i class="fe fe-trash"></i>
                                                </a>                                               
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="modal-create-category">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('category.create') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="category_name" class=" form-control-label">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter category name"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="category_desc" class=" form-control-label">Description</label>
                                <input type="text" id="desc" name="desc" placeholder="Enter category description"
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

        
        <div class="modal" tabindex="-1" role="dialog" id="modal-edit-category">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit category details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('category.update') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="category_name" class=" form-control-label">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter store name"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="category_desc" class=" form-control-label">Description</label>
                                <input type="text" id="desc" name="desc"
                                    placeholder="Enter store description" class="form-control">
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

        <div class="modal" tabindex="-1" role="dialog" id="modal-delete-confirmation">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal__illus">
                            <lottie-player src="{{ asset('assets/icons/confirmation.json') }}" background="transparent"
                                speed="1" style="width: 150px; height: 150px;" loop autoplay></lottie-player>
                        </div>
                        <h5 class="modal-title">Are you sure?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('category.delete') }}" method="GET">
                        <div class="modal-body mb-0">
                            <p class="text-center m-auto">Do you really want to delete this record? This process cannot be
                                undone.</p>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="#" class="btn btn-light mr-2" data-dismiss="modal">Cancel</a>
                                @csrf
                                <input type="hidden" name="id" id="id" value="">
                                <button type="submit" class="btn btn-danger">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        </div>

    @endsection

    @section('custom-scripts')
        <script src="{{ asset('/js/category/category.js') }}"></script>
    @endsection
