@extends('layouts.app')
@section('title')
    Add Blog
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Add New Blog</h1>
            <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                @include('layouts.errors')
                <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label required">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Enter Blog Title">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" required accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Issue Type</label>
                                <input type="text" name="issue_type" class="form-control" value="{{ old('issue_type') }}" required placeholder="e.g. Teeth Crowding">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Issue Level</label>
                                <select name="issue_level" class="form-select" required>
                                    <option value="Simple">Simple</option>
                                    <option value="Moderate">Moderate</option>
                                    <option value="Severe">Severe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Treatment Time</label>
                                <input type="text" name="treatment_time" class="form-control" value="{{ old('treatment_time') }}" required placeholder="e.g. 6 Months">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Aligner Count</label>
                                <input type="number" name="aligner_count" class="form-control" value="{{ old('aligner_count', 10) }}" required min="1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label required">Content</label>
                                <textarea name="content" class="form-control" rows="6" required placeholder="Write blog content here...">{{ old('content') }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Save Blog</button>
                            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
