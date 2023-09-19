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
                        <h1>Analytics</h1>
                    </div>
                    <div aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                        </ol>
                    </div>
                </div>
                <div>
                    <a href="{{ route('analytics.export',  ['order' => Request::get('order'), 'sort' => Request::get('sort'), 'filter-from' => Request::get('filter-from')]) }}">
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
                            <form action="{{ route('analytics.index') }}" method="GET">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="filter-from" class="form-control-label">From</label>
                                            <input type="date" id="filter-from" name="filter-from" class="form-control"
                                                value="{{ Request::get('filter-from') ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-light-blue ml-auto mr-0 d-block"> <i
                                                    class="fe fe-filter mr-2"></i> Date Filter </button>
                                        </div>
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
                                        <th class="th-clickable" aria-sort="{{(Request::get('sort') != 'vending' ? 'none' : (Request::get('order') == 'asc' ? 'ascending' : 'descending') )}}" valign="middle"><a href="{{ route('analytics.index', ['order' => (Request::get('order') == 'asc' ? 'desc' : 'asc'), 'sort' => 'vending', 'filter-from' => Request::get('filter-from')]) }}">Vending Machine<span class="sort-icon">
                                                <i class="fa fa-sort-asc" aria-hidden="true"></i>
                                                <i class="fa fa-sort-desc" aria-hidden="true"></i>
                                            </span>
                                        </th>
                                        <th class="th-clickable" aria-sort="{{(Request::get('sort') != 'stores' ? 'none' : (Request::get('order') == 'asc' ? 'ascending' : 'descending') )}}" valign="middle"><a href="{{ route('analytics.index', ['order' => (Request::get('order') == 'asc' ? 'desc' : 'asc'), 'sort' => 'stores', 'filter-from' => Request::get('filter-from')]) }}">Store Name<span class="sort-icon">
                                                <i class="fa fa-sort-asc" aria-hidden="true"></i>
                                                <i class="fa fa-sort-desc" aria-hidden="true"></i>
                                            </span>
                                        </th>
                                        <th class="th-clickable" aria-sort="{{(Request::get('sort') != 'sales' ? 'none' : (Request::get('order') == 'asc' ? 'ascending' : 'descending') )}}" valign="middle"><a href="{{ route('analytics.index', ['order' => (Request::get('order') == 'asc' ? 'desc' : 'asc'), 'sort' => 'sales', 'filter-from' => Request::get('filter-from')]) }}">Total Sales<span class="sort-icon">
                                                <i class="fa fa-sort-asc" aria-hidden="true"></i>
                                                <i class="fa fa-sort-desc" aria-hidden="true"></i>
                                            </span>
                                        </th>
                                        <th class="th-clickable" aria-sort="{{(Request::get('sort') != 'time' ? 'none' : (Request::get('order') == 'asc' ? 'ascending' : 'descending') )}}" valign="middle"><a href="{{ route('analytics.index', ['order' => (Request::get('order') == 'asc' ? 'desc' : 'asc'), 'sort' => 'time', 'filter-from' => Request::get('filter-from')]) }}">Peak Time<span class="sort-icon">
                                                <i class="fa fa-sort-asc" aria-hidden="true"></i>
                                                <i class="fa fa-sort-desc" aria-hidden="true"></i>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($analytics as $i => $data)
                                        <tr>
                                            <td class="serial">
                                                {{ (Request::get('page') == 0 ? 0 : Request::get('page') - 1) * 15 + $loop->iteration }}
                                            </td>
                                            <td><span class="">{{ $data->name }}</span></td>
                                            <td><span class="">{{ $data->store->name }}</span></td>
                                            <td><span class="">{{ 'P' . ($data->total_sale ?? '0') }}</span></td>
                                            <td><span class="">{{ $data->peak_hrs ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    </div>
@endsection

@section('custom-scripts')
    {{-- <script src="{{ asset('/js/analytics/index.js') }}" />
    </script> --}}
@endsection
