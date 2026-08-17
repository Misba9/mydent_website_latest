@extends('fronts.layouts.app')
@section('title')
    {{ $blog->title }}
@endsection
@section('content')
    <div class="blog-details-page py-10 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb" class="mb-5">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('medical') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($blog->title, 30) }}</li>
                        </ol>
                    </nav>

                    <div class="card shadow-sm rounded-15 overflow-hidden border-0 mb-8">
                        @if($blog->thumbnail)
                            <img src="{{ asset($blog->thumbnail) }}" class="card-img-top w-100" alt="{{ $blog->title }}" style="max-height: 450px; object-fit: cover;">
                        @endif
                        <div class="card-body p-8">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="badge bg-primary fs-6">{{ $blog->issue_type }}</span>
                                <span class="badge bg-info fs-6">{{ $blog->issue_level }}</span>
                            </div>
                            <h1 class="text-gray-900 fw-bold mb-4">{{ $blog->title }}</h1>
                            <div class="d-flex align-items-center text-muted mb-6 pb-4 border-bottom gap-4">
                                <span><i class="fa-regular fa-clock me-2"></i>Treatment: {{ $blog->treatment_time }}</span>
                                <span><i class="fa-solid fa-teeth-open me-2"></i>Aligners: {{ $blog->aligner_count }}</span>
                            </div>
                            <div class="blog-content text-gray-800 fs-5 lh-lg">
                                {!! nl2br(e($blog->content)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('blogs.index') }}" class="btn btn-secondary rounded-pill px-6">
                            <i class="fa-solid fa-arrow-left me-2"></i> Back to Blogs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
