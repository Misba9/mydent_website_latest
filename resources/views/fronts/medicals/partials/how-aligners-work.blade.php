<section class="md-section md-section--white md-reveal">
    <div class="container">
        <h2 class="md-section__title text-center">What are clear aligners and how do they work?</h2>
        <p class="md-section__lead text-center">
            Clear teeth aligners are transparent plastic trays that gradually straighten misaligned, crooked, and
            crowded teeth for people of all ages (10 to 50). They are comfortable and a removable alternative to painful braces.
        </p>
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="d-flex flex-column gap-4">
                    <div class="text-center">
                        <img src="{{ asset('images/mydent-care.jpeg') }}" alt="Removable and Convenient" class="mb-2 md-feature-thumb" />
                        <h5 class="fw-bold">Removable and Convenient</h5>
                        <p class="text-muted mb-0">You can remove them while eating or brushing.</p>
                    </div>
                    <div class="text-center">
                        <img src="{{ asset('images/invisible.jpeg') }}" alt="Virtually Invisible" class="mb-2 md-feature-thumb" />
                        <h5 class="fw-bold">Virtually Invisible</h5>
                        <p class="text-muted mb-0">No metal brackets or wires, just a confident smile.</p>
                    </div>
                    <div class="text-center">
                        <img src="{{ asset('images/3d-dental-scan.jpg') }}" alt="Custom-Made for You" class="mb-2 md-feature-thumb" />
                        <h5 class="fw-bold">Custom-Made for You</h5>
                        <p class="text-muted mb-0">Each aligner is crafted based on your dental needs.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="ratio ratio-16x9">
                    @if($section2Video)
                        <video class="w-100 h-100 rounded" style="object-fit: cover; box-shadow: var(--md-shadow-soft);" autoplay loop muted playsinline>
                            <source src="{{ getMediaUrl($section2Video->video_path, 'storage/vids/heroandchat.mp4') }}" type="video/mp4">
                        </video>
                    @else
                        <img src="{{ asset('images/invisible.jpeg') }}" alt="Clear aligners" class="w-100 h-100 rounded" style="object-fit: cover;">
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
