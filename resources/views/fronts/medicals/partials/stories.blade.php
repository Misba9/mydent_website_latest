<section class="md-section md-section--soft md-reveal" aria-labelledby="md-gallery-title">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="md-section__eyebrow">Smile gallery</span>
                <h2 id="md-gallery-title" class="md-section__title mb-2">Real MyDent smile stories</h2>
                <p class="md-section__lead mb-0">
                    Explore approved treatment stories from our community. Faces are shown only when already published with permission.
                </p>
            </div>
            <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary">View all stories</a>
        </div>

        @if(isset($blogs) && $blogs->count())
            <div class="md-gallery" id="mdSmileGallery">
                @foreach($blogs->take(6) as $index => $blog)
                    <article class="md-gallery-item"
                             role="button"
                             tabindex="0"
                             data-bs-toggle="modal"
                             data-bs-target="#mdGalleryModal"
                             data-title="{{ $blog->title }}"
                             data-image="{{ getMediaUrl($blog->thumbnail, 'storage/imgs/logo.png') }}"
                             data-meta="{{ $blog->treatment_time }} · {{ $blog->aligner_count }} aligners">
                        <img src="{{ getMediaUrl($blog->thumbnail, 'storage/imgs/logo.png') }}"
                             alt="Smile story: {{ $blog->title }}"
                             width="480"
                             height="360"
                             loading="lazy"
                             onerror="{!! imgOnErrorFallback('storage/imgs/logo.png') !!}">
                        <div class="md-gallery-item__body">
                            @if($blog->issue_type)
                                <span class="md-gallery-item__tag">{{ strtoupper($blog->issue_type) }}</span>
                            @endif
                            <h3>{{ $blog->title }}</h3>
                            <p>{{ $blog->treatment_time }} · {{ $blog->aligner_count }} aligners</p>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">Smile stories will appear here when published.</p>
        @endif
    </div>
</section>

<div class="modal fade" id="mdGalleryModal" tabindex="-1" aria-labelledby="mdGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: var(--md-radius-lg);">
            <div class="modal-header border-0">
                <h2 class="modal-title h5" id="mdGalleryModalLabel">Smile story</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <img id="mdGalleryModalImage" src="" alt="" class="w-100 rounded mb-3" style="max-height: 420px; object-fit: cover;">
                <p id="mdGalleryModalMeta" class="text-muted mb-0"></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('mdGalleryModal');
        if (!modal) return;
        modal.addEventListener('show.bs.modal', function (event) {
            var trigger = event.relatedTarget;
            if (!trigger) return;
            document.getElementById('mdGalleryModalLabel').textContent = trigger.getAttribute('data-title') || 'Smile story';
            var img = document.getElementById('mdGalleryModalImage');
            img.src = trigger.getAttribute('data-image') || '';
            img.alt = trigger.getAttribute('data-title') || 'Smile story';
            document.getElementById('mdGalleryModalMeta').textContent = trigger.getAttribute('data-meta') || '';
        });
    });
</script>
