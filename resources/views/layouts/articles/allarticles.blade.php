@extends('fronts.layouts.app')
@section('title')
    Articles
@endsection
@section('content')
    <div class="articles-page py-10 bg-light">
        <div class="container">
            <div class="text-center mb-10">
                <h1 class="text-primary fw-bold mb-3">Health & Dental Articles</h1>
                <p class="text-gray-600 fs-5">Read expert tips, dental guides, and latest medical articles.</p>
            </div>
            
            <div class="row g-6">
                @forelse($articles as $article)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm rounded-15 overflow-hidden border-0">
                            @if($article->thumbnail)
                                <img src="{{ asset($article->thumbnail) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 220px; object-fit: cover;">
                            @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 220px;">
                                    <i class="fa-solid fa-newspaper fs-1 text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column p-6">
                                <h3 class="card-title text-gray-900 mb-3 fs-4 fw-bold">{{ $article->title }}</h3>
                                <p class="card-text text-gray-600 flex-grow-1">
                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                </p>
                                <div class="mt-4 d-flex align-items-center justify-content-between">
                                    <span class="text-muted fs-small">
                                        <i class="fa-regular fa-clock me-1"></i>
                                        {{ $article->created_at ? $article->created_at->format('M d, Y') : '' }}
                                    </span>
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                        Read More <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-10">
                        <i class="fa-solid fa-folder-open fs-1 text-muted mb-4 d-block"></i>
                        <h4 class="text-gray-700">No articles available at the moment.</h4>
                        <p class="text-muted">Check back soon for new updates.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
