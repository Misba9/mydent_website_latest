@extends('fronts.layouts.app')
@section('title')
    {{ $article->title }}
@endsection
@section('content')
    <div class="article-details-page py-10 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb" class="mb-5">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('medical') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 30) }}</li>
                        </ol>
                    </nav>

                    <div class="card shadow-sm rounded-15 overflow-hidden border-0 mb-8">
                        @if($article->thumbnail)
                            <img src="{{ asset($article->thumbnail) }}" class="card-img-top w-100" alt="{{ $article->title }}" style="max-height: 450px; object-fit: cover;">
                        @endif
                        <div class="card-body p-8">
                            <h1 class="text-gray-900 fw-bold mb-4">{{ $article->title }}</h1>
                            <div class="d-flex align-items-center text-muted mb-6 pb-4 border-bottom">
                                <span class="me-4"><i class="fa-regular fa-calendar me-2"></i>{{ $article->created_at ? $article->created_at->format('F d, Y') : '' }}</span>
                            </div>
                            <div class="article-content text-gray-800 fs-5 lh-lg">
                                {!! nl2br(e($article->content)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary rounded-pill px-6">
                            <i class="fa-solid fa-arrow-left me-2"></i> Back to Articles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
