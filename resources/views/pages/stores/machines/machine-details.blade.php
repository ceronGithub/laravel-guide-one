@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/machine-slots/machine-slots.css') }}">
@endsection

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-12">
            <div class="page-title">
                <h1>Machine Slots</h1>
            </div>
            <div aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Store
                            List</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('store.show', $store_id) }}">Vending Machine List</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Machine Slots</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="card table-wrapper">
        <div class="card-header"><strong>Machine Details</strong></div>
        <div class="card-body card-block">
            <div class="row my-0">
                <div class="col-lg-6">
                    <div class="ct-store__details">
                        <div class="col-md-3 col-lg-3">
                            <span class="ct-store__label">Name</span>
                        </div>
                        <div class="col-md-9 col-lg-9">
                            <span class="ct-store__data">{{ $machine->name }}</span>
                        </div>

                        <div class="col-md-3 col-lg-3">
                            <span class="ct-store__label">Machine Location</span>
                        </div>
                        <div class="col-md-9 col-lg-9">
                            <span class="ct-store__data">{{ $machine->desc }}</span>
                        </div>

                        <div class="col-md-3 col-lg-3">
                            <span class="ct-store__label">Machine Address</span>
                        </div>
                        <div class="col-md-9 col-lg-9">
                            <span class="ct-store__data">{{ $machine->machine_address_id }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ct-store__details">
                        <div class="col-md-3 col-lg-3">
                            <span class="ct-store__label">Last Updated</span>
                        </div>
                        <div class="col-md-9 col-lg-9">
                            <span class="ct-store__label">{{ $machine->updated_at }}</span>
                        </div>
                    </div>
                    <div class="ct-store__details">
                        <div class="col-md-3 col-lg-3">
                            <span class="ct-store__label">Date Created</span>
                        </div>
                        <div class="col-md-9 col-lg-9">
                            <span class="ct-store__data">{{ $machine->created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">List of Slots</h4>
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
                    @if (auth()->user()->role_id != 5)
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-add-machine-slot"> <i class="fe fe-plus mr-2"></i>Add Slots</button>
                    @endif
                </div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="card table-wrapper">
                    <div class="table-stats order-table ov-h">
                        <table class="table" id="DataTable">
                            <thead>
                                <tr>
                                    <th class="serial">Slot ID</th>
                                    {{-- <th>Slot ID</th> --}}
                                    <th>Product Name</th>
                                    <th>Current Quantity</th>
                                    <th>Max Quantity</th>
                                    <th>Spare Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($machineSlots as $i => $machineSlot)
                                <tr>
                                    <td class="serial row-id">{{ $i + 1 }}</td>
                                    {{-- <td>
                                        <a href="{{ url('#') }}">
                                            <span class="machine-slot-id">{{ $machineSlot->machine_slots_slot_id }}</span>
                                        </a>
                                    </td> --}}
                                    <td class="product-name">{{ $machineSlot->products_name }}</td>
                                    <td> <span class="current-count tag {{ $machineSlot->machine_slots_current_count <= $machineSlot->machine_slots_stock_alert ? 'tag-danger' : 'tag-info' }}" data-current-count="{{ $machineSlot->machine_slots_current_count }}">
                                            {{ $machineSlot->machine_slots_current_count }} stocks</span></td>
                                    <td class="max-count" data-max-count="{{ $machineSlot->machine_slots_max_count }}">
                                        {{ $machineSlot->machine_slots_max_count }} stocks
                                    </td>
                                    <td class="reserve-count" data-spare-quantity="{{ $machineSlot->machine_slots_reserve_quantity_count }}">
                                        {{ $machineSlot->machine_slots_reserve_quantity_count }} stocks
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-round btn-pink tp-tooltip-edit-spare" data-toggle="modal" data-target="#modal-edit-spare-qty" data-product-id="{{ $machineSlot->products_id }}" data-spare-quantity="{{$machineSlot->machine_slots_reserve_quantity_count}}" data-current-quantity="{{$machineSlot->machine_slots_current_count}}"><i class="fe fe-layers"></i></a>
                                        <a href="#" class="btn btn-round btn-violet tp-tooltip-download" data-toggle="modal" data-target="#modal-slot-details" data-stock-alert="{{ $machineSlot->machine_slots_stock_alert }}" data-spare-quantity="{{ $machineSlot->machine_slots_reserve_quantity_count }}" data-serial="{{ $machineSlot->machine_slots_serial }}"><i class="fe fe-eye"></i></a>
                                        <a href="{{ url('store-details.html') }}" class="btn btn-round btn-blue tp-tooltip-manage" 
                                        data-toggle="modal" 
                                        data-target="#modal-edit-slot" 
                                        data-product-id="{{ $machineSlot->products_id }}"
                                        data-machine-slot-index="{{ $machineSlot->machine_slots_id }}" 
                                        data-stock-alert="{{ $machineSlot->machine_slots_stock_alert }}"
                                        data-spare-quantity="{{ $machineSlot->machine_slots_reserve_quantity_count }}" 
                                        data-serial="{{ $machineSlot->machine_slots_serial }}"><i class="fe fe-edit"></i></a>
                                        @if (auth()->user()->role_id != 5)
                                        <a href="#" class="btn btn-round btn-red tp-tooltip-delete" data-id="{{ $machineSlot->machine_slots_id }}" data-toggle="modal" data-target="#modal-delete-confirmation">
                                            <i class="fe fe-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>

                                @empty
                                <td>No machine slot found. Please add machine slot for this vending machine.</td>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" tabindex="-1" role="dialog" id="modal-add-machine-slot">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Slot Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('machine-slots.create', $machineId) }}" method="POST">
                    <div class="modal-body ct-modal-form">
                        @csrf
                        <input type="hidden" name="store_id" value="{{ $store_id }}">

                        <div class="ct-form__tab mb-2" role="tablist">
                            <input type="radio" value="radio-edit-slot" id="radio-edit-slot" class="input--hidden" name="radio-edit-type" checked>
                            <label for="radio-edit-slot" class="ct-tab__item"> Slots </label>

                            <input type="radio" value="radio-edit-serial" id="radio-edit-serial" class="input--hidden" name="radio-edit-type">
                            <label for="radio-edit-serial" class="ct-tab__item">Serial No. Configuration</label>
                        </div>

                        <div class="ct-slot__container">
                            <div class="form-group">
                                <label for="slot_id" class=" form-control-label">Slot ID</label>
                                <input type="number" id="slot" name="slot_id" placeholder="Enter Slot ID" class="form-control">
                            </div>
                            <div class="form-group ct-form-dropdown">
                                <label for="assign-item" class="form-control-label">Assign Item</label>
                                <select class="form-control" name="product_id">
                                    <option value="" disabled selected>Select Items</option>
                                    @forelse ($products as $i => $product)
                                    <option value={{ $product->id }}>{{ $product->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max-quantity" class=" form-control-label">Max Quantity</label>
                                <input type="number" id="max-quantity" name="max_count" placeholder="Enter Max Quantity" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label for="quantity" class=" form-control-label">Current Quantity</label>
                                <input type="number" id="current_count" name="current_count" placeholder="Enter Current Quantity" placeholder="Current Quantity" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="quantity" class=" form-control-label">Spare Quantity</label>
                                <input type="number" id="reserve_quantity_count" name="reserve_quantity_count" placeholder="Enter Spare Quantity" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label for="max-quantity" class=" form-control-label">Item Stock Alert</label>
                                <input type="number" id="stock_alert" name="stock_alert" placeholder="Item Stock Alert" class="form-control">
                            </div>

                        </div>

                        <div class="ct-serial__container">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="{{ $machineId }}" name="machine_address_id">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modal-edit-spare-qty">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Spare Quantity</h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('machine-slots.updateSpare', $machineId) }}" method="GET" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body ct-modal-form">
                        <div class="form-group">                            
                            <label for="id" hidden>ID</label><input type="hidden" name="id" id="id" value="" class="form-control">
                            <label for="currentQry" hidden>Current Quantity</label><input type="hidden" name="currentQry" id="currentQry" class="form-control" />
                            <label for="totalSpareParts" class="form-control-label">Spare Quantity</label>                            
                            <input type="number" name="totalSpareParts" id="totalSpareParts" placeholder="Enter Spare Quantity" class="form-control" />                                                                             
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
          </div>
        </div>
      </div>
      <!-- /modal -->
      <div class="clearfix"></div>
    </div>


    <div class="modal" tabindex="-1" role="dialog" id="modal-slot-details">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header ct-modal-header">
                    <h5 class="modal-title">Slot Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body ct-modal-content">
                        <table>
                            <tr>
                                <td class="ct-modal-label">Slot ID:</td>
                                <td class="ct-modal-data" id="machine-slot-id"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Product Name:</td>
                                <td class="ct-modal-data" id="product-name"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Max Quantity:</td>
                                <td class="ct-modal-data" id="max-count"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Current Quantity:</td>
                                <td class="ct-modal-data" id="current-count"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Spare Quantity:</td>
                                <td class="ct-modal-data" id="spare-quantity"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Serial Numbers:</td>
                                <td class="ct-modal-data" id="serial"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Stock Alert:</td>
                                <td class="ct-modal-data" id="stock-alert"></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="modal-edit-slot">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Slot Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('machine-slots.update', $machineId) }}" method="GET" enctype="multipart/form-data" id="editForm">
                    @csrf
                    <div class="modal-body ct-modal-form">
                        <div class="ct-form__tab mb-2" role="tablist" id="serial_tab_page">
                            <input type="radio" value="radio-edit-slot" id="radio-edit-slot" class="input--hidden" name="radio-edit-type" checked required>
                            <label for="radio-edit-slot" class="ct-tab__item"> Slots </label>

                            <input type="radio" value="radio-edit-serial" id="radio-edit-serial" class="input--hidden" name="radio-edit-type" required>
                            <label for="radio-edit-serial" class="ct-tab__item">Serial No. Configuration</label>
                        </div>

                        <div class="ct-slot__container">
                            <div class="form-group">
                                <label for="slot_id" class=" form-control-label">Slot ID</label>
                                <input type="text" id="index" name="index" placeholder="Enter store code" class="form-control" readonly>
                            </div>
                            {{-- <div class="form-group">
                                <label for="slot_id" class=" form-control-label">Slot ID</label>
                                <input type="text" id="machine-slot-id" name="machine-slot-id" placeholder="Enter store code" class="form-control" readonly>
                            </div> --}}
                            <div class="form-group ct-form-dropdown">
                                <label for="assign-item" class="form-control-label">Assign Item</label>
                                <select class="form-control" id="product-id" name="product-id">
                                    <option value="" disabled selected>Select Items</option>
                                    @forelse ($products as $i => $product)
                                    <option value={{ $product->id }}>{{ $product->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max-quantity" class=" form-control-label">Max Quantity</label>
                                <input type="number" id="max-count" name="max-count" placeholder="Enter Max Quantity" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="quantity" class=" form-control-label">Current Quantity</label>
                                <input type="number" id="current-count" name="current-count" placeholder="Enter Current Quantity" placeholder="Current Quantity" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="reserve-quantity" class=" form-control-label">Spare Quantity</label>
                                <input type="number" id="spare-quantity" name="spare-quantity" placeholder="Enter Spare Quantity" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="stock-alert" class=" form-control-label">Item Stock Alert</label>
                                <input type="number" id="stock_alert" name="stock_alert" placeholder="Item Stock Alert" class="form-control">
                            </div>
                        </div>

                        <div class="ct-serial__container">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="submit_edit_button" >Update Data</button>
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
                        <lottie-player src="{{ asset('assets/icons/confirmation.json') }}" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></lottie-player>
                    </div>
                    <h5 class="modal-title">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('machine-slots.delete') }}" method="POST">
                    <div class="modal-body mb-0">
                        <p class="text-center m-auto">Do you really want to delete this record? This process cannot be
                            undone.</p>
                        <div class="d-flex justify-content-end mt-4">
                            @csrf
                            <a href="#" class="btn btn-light mr-2" data-dismiss="modal">Cancel</a>
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
<script src="{{ asset('/js/machine-slots/machine-slots.js') }}"></script>
@endsection
