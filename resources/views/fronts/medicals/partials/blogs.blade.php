<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Our Latest Blogs</h2>
        <div class="row">
            @forelse ($articles->take(3) as $article)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if($article->thumbnail)
                            <img src="{{ asset($article->thumbnail) }}" alt="{{ $article->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary mt-auto">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No articles available at the moment.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('articles.index') }}" class="btn btn-primary">View All</a>
        </div>
    </div>
</section>  
