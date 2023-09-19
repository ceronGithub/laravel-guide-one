@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/idle-videos/idle-videos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/unpkg.com_dropzone@5.9.3_dist_min_dropzone.min.css') }}" type="text/css">
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h1>Welcome Screen</h1>
                </div>
                <div aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Welcome Screen</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('idle-video.publish') }}" method="get">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3" id="dz-submit">
                            <h4>Change Welcome Screen</h4>
                            <button type="submit" class="btn btn-primary"> Publish </button>
                        </div>
                    </form>
                    <div class="card">
                        <form action="{{ route('idle-video.upload') }}" id="welcome-vid-dropzone" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="dz" id="dz">
                                <div class="dz-message">
                                    <i class="dz-upload-icon fe fe-upload-cloud"></i>
                                    <span>Drag and drop video file to upload</span>
                                    <small>OR</small>
                                    <span class="btn btn-primary btn-sm mt-2">Browse File</span>
                                </div>
                            </div>
                        </form>
                        <div class="dz-preview-placeholder" id="dz-preview-placeholder-container">
                            <div class="dz-preview-row" id="dz-preview-template-container">
                                <div class="dz-details">
                                    <div class="dz-thumbnail-wrapper">
                                        <img src="{{ asset('/assets/icons/thumbnail-vid.png') }}" />
                                        <img class="dz-thumbnail" data-dz-thumbnail />
                                    </div>
                                    <div>
                                        <div class="dz-filename"><span>Idle.mp4</span></div>
                                    </div>
                                </div>
                                <span data-dz-remove class="btn btn-danger btn-danger-placeholder btn-sm"> Upload New </span>
                            </div>
                        </div>
                        <div class="dz-preview" id="dz-preview-container">
                            <div class="dz-preview-row" id="dz-preview-template">
                                <div class="dz-details">
                                    <div class="dz-thumbnail-wrapper">
                                        <img src="{{ asset('/assets/icons/thumbnail-vid.png') }}" />
                                        <img class="dz-thumbnail" data-dz-thumbnail />
                                    </div>
                                    <div>
                                        <div class="dz-filename"><span data-dz-name></span></div>
                                        <div class="dz-size" data-dz-size></div>
                                    </div>
                                </div>
                                <span data-dz-remove class="btn btn-danger btn-sm"> Upload New </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('/js/vendor/cdn.jsdelivr.net_npm_jquery@3.7.0_dist_jquery.min.js') }}"></script>
    <script src="{{ asset('/js/vendor/unpkg.com_dropzone@5.9.3_dist_min_dropzone.min.js') }}"></script>

    <script src="{{ asset('/js/idle-videos/idle-videos.js') }}"></script>
@endsection
