@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/audit/audit.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h1>Audit Trail</h1>
                </div>
                <div aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Audit Trail</li>
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
                                        <th>User</th>
                                        <th>Activity</th>
                                        <th>IP Address</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activities as $i => $data)
                                        <tr>
                                            <td class="serial">{{ (Request::get('page') == 0 ? 0 : Request::get('page') - 1) * 30 + $loop->iteration }}</td>
                                            <td><span class="name">{{ $data->causer->email ?? "" }}</span></td>
                                            <td><span class="">{{ $data->description }}</span></td>
                                            <td><span class="">{{ $data->properties["IP"] ?? "" }}</span></td>
                                            <td><span class="">{{ $data->created_at }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('/js/audit/audit.js') }}"></script>
@endsection
