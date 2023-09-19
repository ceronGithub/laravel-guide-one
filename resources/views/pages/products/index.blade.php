@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/products/products.css') }}">
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-header">
        <div class="row">
            <div class="col-12">
                <div class="page-title ct-product__title">
                    <div>
                        <h1>Products</h1>
                        <div aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Products</li>
                            </ol>
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
                    <div class="ct-product__modal">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                            data-target="#addItems"> <i class="fe fe-plus mr-2"></i> Add Items </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-3">
                    <div class="ct-category__card card">
                        <h4> Product Categories</h4>
                        <button type="submit" class="btn ct-btn__categories for_filter" data-CategoryName="0">All</button>
                        @forelse ($categories as $i => $category)
                            <button type="submit" class="btn ct-btn__categories for_filter"
                                data-CategoryName="{{ $category->id }}">{{ $category->name }}</button>
                        @empty
                        @endforelse
                        <form id="filter" action="" method="GET">
                            @csrf
                            <input type="hidden" id="getCategory" name="getCategory" placeholder="Enter Product Code"
                                class="form-control" value="Product One">
                        </form>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="ct-product-wrapper">
                        @forelse ($products as $i => $product)
                            <div class="ct-product__card card">
                                <div class="ct-product__img">
                                    <a class="ct-product" href="{{ url('#') }}" data-toggle="modal"
                                        data-target="#modal-edit-product" 
                                        data-id = {{$product->id}}
                                        data-product-code="{{ $product->product_code }}"
                                        data-name="{{ $product->name }}" 
                                        data-price="{{ $product->price }}"
                                        data-spec="{{ $product->specification }}" 
                                        data-feature="{{ $product->feature }}"
                                        data-desc="{{ $product->desc }}" 
                                        data-categ="{{ $product->category_id }}"
                                        data-image-count="{{ count($product->image) }}"
                                        data-image="{{ json_encode($product->image) }}">
                                        <img src="{{ asset($product->image[0] ?? '') }}" alt="image"><br>
                                        <span class="ct-product__name">{{ $product->name }}</span></a>
                                </div>
                            </div>
                        @empty
                            <td>No machine slot found. Please add machine slot for this vending machine.</td>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="addItems">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('products.create', auth()->user()->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body ct-modal-form">
                            <div class="form-group ct-form__group">
                                <label for="slot_id" class=" form-control-label">Product Code</label>
                                <input type="number" id="product_code" name="product_code" placeholder="Enter Product Code"
                                    class="form-control">
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="slot_id" class=" form-control-label">Product Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter Product Name"
                                    class="form-control">
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="slot_id" class=" form-control-label">Price</label>
                                <input type="number" id="price" name="price" placeholder="₱" class="form-control">
                            </div>
                            <div class="form-group ct-form__group ct-form-dropdown">
                                <label for="category" class="form-control-label">Category </label>
                                <select class="form-control" name="category_id">
                                    <option value="" disabled selected>Select Category</option>
                                    @forelse ($categories as $category)
                                        <option value={{ $category->id }}>{{ $category->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="specification" class=" form-control-label">Specifications</label>
                                <textarea placeholder="Enter Specifications" id="specification" name="specification" class="form-control"
                                    onkeyup="this.value = this.value.toNormal();"></textarea>
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="feature" class=" form-control-label">Features</label>
                                <textarea placeholder="Enter Features" id="feature" name="feature" class="form-control"
                                    onkeyup="this.value = this.value.toNormal();"></textarea>
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="description" class=" form-control-label">Description</label>
                                <textarea placeholder="Enter Description" id="desc" name="desc" class="form-control"
                                    onkeyup="this.value = this.value.toNormal();"></textarea>
                            </div>
                            <div class="form-group ct-form__group">
                                <label class="frm__label" for="">Upload</label>
                                <input multiple type="file" id="image" name="image[]">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="modal-edit-product">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="updateForm"  action="{{ route('products.update', auth()->user()->id) }}" method="POST" class="updateForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body ct-modal-form">
                            <div class="form-group ct-form__group">

                                <input type="visible" id="product-id" name="product-id"
                                    placeholder="Enter Product Code" class="form-control" value="Product One">

                                <input type="hidden" id="product-code" name="product-code"
                                    placeholder="Enter Product Code" class="form-control" value="Product One">

                                    <input type="hidden" id="userID" name="userID"
                                    placeholder="Enter Product Code" class="form-control" value="{{auth()->user()->id}}">


                                <label for="slot_id" class=" form-control-label">Product Name</label>
                                <input type="text" id="product-name" name="product-name"
                                    placeholder="Enter Product Name" class="form-control" value="Product One">
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="slot_id" class=" form-control-label">Price</label>
                                <input type="number" id="price" name="price" placeholder="₱"
                                    class="form-control" value="2500.00">
                            </div>
                            <div class="form-group ct-form__group ct-form-dropdown">
                                <label for="category" class="form-control-label">Category </label>
                                <select class="form-control" name="category-id" id="category-id">
                                    <option value="" disabled>Select Category</option>
                                    @forelse ($categories as $i => $category)
                                        <option value={{ $category->id }}>{{ $category->name }}</option>
                                    @empty
                                    @endforelse

                                </select>
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="specification" class=" form-control-label">Specifications</label>
                                <textarea placeholder="Enter Specifications" id="specification" name="specification" class="form-control">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</textarea>
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="feature" class=" form-control-label">Features</label>
                                <textarea placeholder="Enter Features" id="feature" name="feature" class="form-control">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</textarea>
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="description" class=" form-control-label">Description</label>
                                <textarea placeholder="Enter Description" id="description" name="description" class="form-control">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</textarea>
                            </div>
                            <div class="form-group ct-form__group" id="addNewImage">
                                <label>Add New Image</label>
                                <input multiple type="file" name="image[]" id="image-data">
                            </div>
                            <div class="form-group ct-form__group">
                                <label for="images" class=" form-control-label ct-product__label">Images</label>
                                </label>
                                <div id="imageContainer"></div>
                            </div>
                        </div>
                        <input type="hidden" id="deleteImage" name="deleteImage" placeholder="Enter Product Code"
                            class="form-control" value="No-Image-Deletion">
                    </form>
                    <div class="modal-footer">
                        <button id="submitUpdateFormBtn" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="clearfix"></div>
    </div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('/js/products/products.js') }}"></script>
@endsection


