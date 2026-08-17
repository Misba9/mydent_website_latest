@extends('layouts.app')
@section('title')
    Add Homepage Video
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Add Homepage Video</h1>
            <a href="{{ route('homepage-videos.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                @include('layouts.errors')
                <form action="{{ route('homepage-videos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Enter Video Title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Section</label>
                                <select name="section" class="form-select" required>
                                    <option value="section-1">Section 1</option>
                                    <option value="section-2">Section 2</option>
                                    <option value="section-3">Section 3</option>
                                    <option value="section-4">Section 4</option>
                                    <option value="section-5">Section 5</option>
                                    <option value="characteristics">Characteristics</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Video File (MP4, WebM)</label>
                                <input type="file" name="video" class="form-control" accept="video/*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Image (For Characteristics)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="order" class="form-control" value="{{ old('order', 1) }}" min="1">
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Save Video</button>
                            <a href="{{ route('homepage-videos.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
