@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/vending-machine/vending-machine.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h1>Vending Machine List</h1>
                </div>
                <div aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Store list</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vending Machine List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-wrapper">
                        <div class="card-header"><strong>Store Details</strong></div>
                        <div class="card-body card-block">
                            <div class="row my-0">
                                <div class="col-lg-6">
                                    <div class="ct-store__details">
                                        <div class="col-md-3 col-lg-3">
                                            <span class="ct-store__label">Store Name</span>
                                        </div>
                                        <div class="col-md-9 col-lg-9">
                                            <span class="ct-store__data">{{ $store->name }}</span>
                                        </div>

                                        <div class="col-md-3 col-lg-3">
                                            <span class="ct-store__label">Store Location</span>
                                        </div>
                                        <div class="col-md-9 col-lg-9">
                                            <span class="ct-store__data">{{ $store->desc }}</span>
                                        </div>

                                        {{-- <div class="col-md-3 col-lg-3">
                                            <span class="ct-store__label">Location</span>
                                        </div> --}}
                                        <div class="col-md-9 col-lg-9">
                                            <span class="ct-store__data"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="ct-store__details">
                                        <div class="col-md-3 col-lg-3">
                                            <span class="ct-store__label">Last Updated</span>
                                        </div>
                                        <div class="col-md-9 col-lg-9">
                                            <span class="ct-store__label">{{ $store->updated_at }}</span>
                                        </div>
                                    </div>
                                    <div class="ct-store__details">
                                        <div class="col-md-3 col-lg-3">
                                            <span class="ct-store__label">Date Created</span>
                                        </div>
                                        <div class="col-md-9 col-lg-9">
                                            <span class="ct-store__data">{{ $store->created_at }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Vending Machine Names</h4>
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
                                data-target="#modal-add-machine"> <i class="fe fe-plus mr-2"></i> Add Vending Machine
                            </button>
                        @endif
                    </div>
                    <div class="card table-wrapper">
                        <div class="table-stats order-table ov-h">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Vendo Name</th>
                                        <th>Machine Address</th>
                                        <th>Machine Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($machines as $item)
                                        <tr>
                                            <td class="serial">{{ $loop->iteration }}</td>
                                            <td><span class="name">{{ $item->name }}</span></td>
                                            <td><span class="">{{ $item->machine_address_id }}</span></td>
                                            <td><span class="">{{ $item->desc }}</span></td>
                                            <td><span
                                                    class="status {{ $item->isOnline() ? 'status-success' : '' }}">{{ $item->isOnline() ? 'Online' : 'Offline' }}</span>
                                            </td>
                                            <td>
                                                @if (auth()->user()->role_id <= 2)
                                                    <a href="{{ url('store-details.html') }}"
                                                        class="btn btn-round btn-blue tp-tooltip-edit" data-toggle="modal"
                                                        data-target="#modal-edit-machine" data-id="{{ $item->id }}"
                                                        data-machine-id="{{ $item->machine_address_id }}"
                                                        data-machine-name="{{ $item->name }}"
                                                        data-machine-desc="{{ $item->desc }}"><i
                                                            class="fe fe-edit"></i></a>
                                                @endif
                                                <a href="{{ route('machine-slots.index', ['machineSlotId' => $item->machine_address_id]) }}"
                                                    class="btn btn-round btn-light-orange tp-tooltip-manage"><i
                                                        class="fe fe-settings"></i></a>
                                                @if (auth()->user()->role_id <= 2)
                                                    <a href="#" class="btn btn-round btn-red tp-tooltip-delete"
                                                        data-id="{{ $item->id }}" data-toggle="modal"
                                                        data-target="#modal-delete-confirmation"><i
                                                            class="fe fe-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $machines->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modal-add-machine">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Vending Machine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('machine.create', $store->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="store_name" class=" form-control-label">Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter vending machine name"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="store_name" class=" form-control-label">Machine Address ID</label>
                            <input type="text" id="machine_address_id" name="machine_address_id"
                                placeholder="Enter vending machine address id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="store_name" class=" form-control-label">Machine Location</label>
                            <textarea id="name" name="desc" placeholder="Enter vending machine location" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modal-edit-machine">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Vending Machine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('machine.update', $store->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="store_name" class=" form-control-label">Name</label>
                            <input type="text" id="name" name="name"
                                placeholder="Enter new vending machine name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="store_name" class=" form-control-label">Machine Address</label>
                            <input type="text" id="machine_address_id" name="machine_address_id" 
                                placeholder="Enter new vending machine address id" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="store_name" class=" form-control-label">Machine Location</label>
                            <textarea id="desc" name="desc" placeholder="Enter new vending machine location" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id" value="value is">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
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
                <form action="{{ route('machine.delete') }}" method="POST">
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
    <script src="{{ asset('/js/vending-machine/vending-machine.js') }}"></script>
@endsection
