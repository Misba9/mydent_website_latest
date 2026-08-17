@extends('layouts.app')
@section('title')
    Add Main Banner
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Add Main Banner</h1>
            <a href="{{ route('main-banners.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                @include('layouts.errors')
                <form action="{{ route('main-banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Page</label>
                                <input type="text" name="page" class="form-control" value="{{ old('page', 'home') }}" required placeholder="e.g. home, about, services">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Banner Title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Banner Image</label>
                                <input type="file" name="image" class="form-control" required accept="image/*">
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Save Banner</button>
                            <a href="{{ route('main-banners.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
