@extends('layouts.app')
@section('title')
    Edit Blog
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Edit Blog</h1>
            <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                @include('layouts.errors')
                <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label required">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                @if($blog->thumbnail)
                                    <small class="text-muted d-block mt-1">Current: {{ basename($blog->thumbnail) }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Issue Type</label>
                                <input type="text" name="issue_type" class="form-control" value="{{ old('issue_type', $blog->issue_type) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Issue Level</label>
                                <select name="issue_level" class="form-select" required>
                                    <option value="Simple" {{ $blog->issue_level == 'Simple' ? 'selected' : '' }}>Simple</option>
                                    <option value="Moderate" {{ $blog->issue_level == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                    <option value="Severe" {{ $blog->issue_level == 'Severe' ? 'selected' : '' }}>Severe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Treatment Time</label>
                                <input type="text" name="treatment_time" class="form-control" value="{{ old('treatment_time', $blog->treatment_time) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Aligner Count</label>
                                <input type="number" name="aligner_count" class="form-control" value="{{ old('aligner_count', $blog->aligner_count) }}" required min="1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label required">Content</label>
                                <textarea name="content" class="form-control" rows="6" required>{{ old('content', $blog->content) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Update Blog</button>
                            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
