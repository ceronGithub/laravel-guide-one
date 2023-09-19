@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/reports/reports.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-end">
                <div>
                    <div class="page-title">
                        <h1>Reports</h1>
                    </div>
                    <div aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                    </div>
                </div>
                <div>
                    <a
                        href="{{ route('reports.export', [
                            'filter-from' => $from,
                            'filter-to' => $to,
                            'filter-tx-type' => $transactionModeId,
                            'filter-payment-mode' => $paymentMode,
                            'filter-machine-add-id' => Request::get('filter-machine-add-id'),
                        ]) }}">
                        <button type="button" class="btn btn-primary"><i class="fe fe-download-cloud mr-2"></i> Export
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-wrapper">
                        <div class="card-header"><strong>Filters</strong></div>
                        <div class="card-body card-block">
                            <form action="{{ route('reports.index') }}" method="GET">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="filter-from" class="form-control-label">From</label>
                                            <input type="date" id="filter-from" name="filter-from" class="form-control"
                                                value="{{ $from ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="filter-to" class="form-control-label">To</label>
                                            <input type="date" id="filter-to" name="filter-to" class="form-control"
                                                value="{{ $to ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group ct-form-dropdown">
                                            <label for="filter-machine-add-id" class="form-control-label">Vending
                                                Machine</label>
                                            <select class="form-control" name="filter-machine-add-id">
                                                <option value="" disabled selected>Select Items</option>
                                                <option value=""
                                                    {{ Request::get('filter-machine-add-id') == '' ? 'selected' : '' }}>
                                                    {{ 'All Vending Machines' }}</option>
                                                @forelse ($machineList as $i => $machine)
                                                    <option value={{ $machine->machine_address_id }}
                                                        {{ $machine->machine_address_id == Request::get('filter-machine-add-id') ? 'selected' : '' }}>
                                                        {{ $machine->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group ct-form-dropdown">
                                            <label for="filter-tx-type" class="form-control-label">Transaction
                                                Status</label>
                                            <select class="form-control" name="filter-tx-type">
                                                <option value="" disabled selected>Select Items</option>
                                                <option value="" {{ $transactionModeId == '' ? 'selected' : '' }}>
                                                    {{ 'All Transaction Status' }}</option>
                                                <option value={{ '2' }}
                                                    {{ $transactionModeId == '2' ? 'selected' : '' }}>
                                                    {{ 'Payment Collected' }}
                                                </option>
                                                <option value={{ '3' }}
                                                    {{ $transactionModeId == '3' ? 'selected' : '' }}>
                                                    {{ 'Payment Failed' }}
                                                </option>
                                                <option value={{ '1' }}
                                                    {{ $transactionModeId == '1' ? 'selected' : '' }}>
                                                    {{ 'Request Purchase Order' }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group ct-form-dropdown">
                                            <label for="filter-payment-mode" class="form-control-label">Payment Mode</label>
                                            <select class="form-control" name="filter-payment-mode">
                                                <option value="" disabled selected>Select Items</option>
                                                <option value="" {{ $paymentMode == '' ? 'selected' : '' }}>
                                                    {{ 'All Payment Mode' }}</option>
                                                <option value={{ 'GCash' }}
                                                    {{ $paymentMode == 'GCash' ? 'selected' : '' }}>
                                                    {{ 'GCash' }}</option>
                                                <option value={{ 'Mastercard' }}
                                                    {{ $paymentMode == 'Mastercard' ? 'selected' : '' }}>
                                                    {{ 'Mastercard' }}
                                                </option>
                                                <option value={{ 'Visa' }}
                                                    {{ $paymentMode == 'Visa' ? 'selected' : '' }}>
                                                    {{ 'Visa' }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-light-blue ml-auto mr-0 d-block"> <i
                                                class="fe fe-filter mr-2"></i> Filter </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card table-wrapper">
                        <div class="table-stats order-table ov-h">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Reference No.</th>
                                        <th>AR No.</th>
                                        {{-- <th>Terminal TID</th> --}}
                                        <th>Vendo Name</th>
                                        <th>Serial No.</th>
                                        <th>Product</th>
                                        <th>Amount Paid</th>
                                        <th>Transaction Status</th>
                                        <th>Payment Method</th>
                                        <th>Date Paid</th>
                                        <th>Print</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $i => $transaction)
                                        <tr>
                                            <td class="serial">
                                                {{ (Request::get('page') == 0 ? 0 : Request::get('page') - 1) * 15 + $loop->iteration }}
                                            </td>
                                            <td><span class="">{{ $transaction->purchase_order_id }}</span></td>
                                            <td><span class="">{{ $transaction->request_id ?? '-' }}</span></td>
                                            {{-- <td><span class="">{{ $transaction->payment_details->terminal_tid ?? '-'  }}</span></td> --}}
                                            <td><span class="">{{ $transaction->machine->name ?? $transaction->machine_address_id  }}</span></td>
                                            <td><span class="">{{ '-' }}</span></td>
                                            <td><span class="">{{ $transaction->product_name }}</span></td>
                                            <td><span class="">₱{{ $transaction->product_price }}</span></td>
                                            <td><span class="">{{ $transaction->transaction_description ?? '-'  }}</span></td>
                                            <td><span
                                                    class="payment payment-maya">{{ $transaction->payment_details->terminal_payment_mode ?? '-' }}</span>
                                            </td>
                                            <td><span
                                                class="">{{ $transaction->payment_details->created_at ?? '-' }}</span>
                                            </td>
                                            <td><span
                                                    class=""><a href="#" class="btn btn-round btn-violet tp-tooltip-download" ><i class="fe fe-printer"></i></a></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- /.table-stats -->
                    </div>
                </div>

                <!-- pagination -->
                <div class="col-12 d-flex justify-content-end">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    </div><!-- /#right-panel -->
@endsection

@section('custom-scripts')
    <script src="{{ asset('/js/reports/index.js') }}" />
    </script>
@endsection
