@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/transactions/index.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h1>Transactions</h1>
                </div>
                <div aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Transactions</li>
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
                        <div class="table-stats order-table ov-h">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Order ID</th>
                                        <th>Product Name</th>
                                        <th>Product Price</th>
                                        <th>Machine Address ID</th>
                                        <th>Status</th>
                                        <th>Terminal Payment Mode</th>
                                        <th>Terminal Reference ID</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $i => $transaction)
                                        <tr>
                                            <td class="serial">{{ $i + 1 }}</td>
                                            <td class="data-order-id">{{ $transaction->purchase_order_id }}</td>
                                            <td class="data-product-name">{{ $transaction->product_name }}</td>
                                            <td class="data-product-price">P{{ $transaction->product_price }}</td>
                                            <td class="data-address-id">{{ $transaction->machine_address_id }}</td>
                                            <td class="data-transaction_type">{{ ($transaction->isOrderExpired()) ? "Request Order Expired" : $transaction->transaction_description}}</td>
                                            <td class="data-terminal-payment-mode">{{ $transaction->payment_details->terminal_payment_mode ?? "-" }}</td>
                                            <td class="data-terminal-ref">{{ $transaction->payment_details->terminal_ref_num ?? "-" }}</td>
                                            <td class="data-created-at">{{ $transaction->created_at }}</td>
                                            <td>
                                                <a href="#" class="btn btn-round btn-violet tp-tooltip-view"
                                                    data-toggle="modal" data-target="#modal-transaction-details"
                                                    data-payment-id="{{ $transaction->payment_details_id ?? "-"}}"
                                                    data-terminal-mid="{{ $transaction->payment_details->terminal_mid ?? "-" }}"
                                                    data-terminal-appr="{{ $transaction->payment_details->terminal_appr_code ?? "-" }}"
                                                    data-terminal-tid="{{ $transaction->payment_details->terminal_tid ?? "-" }}"
                                                    data-transaction-id="{{ $transaction->transaction_id ?? "-"}}">
                                                    <i class="fe fe-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="modal-transaction-details">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header ct-modal-header">
                    <h5 class="modal-title">Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data">
                    <div class="modal-body ct-modal-content">
                        <table>
                            <tr>
                                <td class="ct-modal-label">Order ID:</td>
                                <td class="ct-modal-data" id="data-order-id"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Product Name:</td>
                                <td class="ct-modal-data" id="data-product-name"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Product Price:</td>
                                <td class="ct-modal-data" id="data-product-price"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Machine Address ID:</td>
                                <td class="ct-modal-data" id="data-address-id"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Purchase ID:</td>
                                <td class="ct-modal-data" id="data-payment-id"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Payment Mode:</td>
                                <td class="ct-modal-data" id="data-payment-mode"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Terminal Reference ID:</td>
                                <td class="ct-modal-data" id="data-terminal-ref"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Terminal MID:</td>
                                <td class="ct-modal-data" id="data-terminal-mid"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Terminal TID:</td>
                                <td class="ct-modal-data" id="data-terminal-tid"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Terminal Appr Code:</td>
                                <td class="ct-modal-data" id="data-terminal-appr"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Transaction ID:</td>
                                <td class="ct-modal-data" id="data-transaction-id"></td>
                            </tr>
                            <tr>
                                <td class="ct-modal-label">Created At:</td>
                                <td class="ct-modal-data" id="data-created-at"></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    </div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('/js/transactions/index.js') }}" />
    </script>
@endsection
