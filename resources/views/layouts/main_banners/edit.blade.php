@extends('layouts.app')
@section('title')
    Edit Main Banner
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Edit Main Banner</h1>
            <a href="{{ route('main-banners.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                @include('layouts.errors')
                <form action="{{ route('main-banners.update', $mainBanner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Page</label>
                                <input type="text" name="page" class="form-control" value="{{ old('page', $mainBanner->page) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $mainBanner->title) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Banner Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                @if($mainBanner->image)
                                    <small class="text-muted d-block mt-1">Current: {{ basename($mainBanner->image) }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Update Banner</button>
                            <a href="{{ route('main-banners.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
