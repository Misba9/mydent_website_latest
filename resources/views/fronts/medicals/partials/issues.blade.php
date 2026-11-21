<section class="md-section md-section--mint md-reveal">
    <div class="container position-relative">
        <h2 class="md-section__title text-center">Teeth alignment issues we can fix</h2>
        <p class="md-section__lead text-center">Overbites, gaps, crowding, and more — discreetly corrected with mydent clear aligners.</p>

        <button id="scrollLeft" type="button" class="btn btn-light position-absolute top-50 start-0 translate-middle-y d-none d-md-flex align-items-center justify-content-center"
            style="z-index: 10; border-radius: 50%; width: 48px; height: 48px; box-shadow: var(--md-shadow-soft);"
            aria-label="Scroll left">←</button>

        <button id="scrollRight" type="button" class="btn btn-light position-absolute top-50 end-0 translate-middle-y d-none d-md-flex align-items-center justify-content-center"
            style="z-index: 10; border-radius: 50%; width: 48px; height: 48px; box-shadow: var(--md-shadow-soft);"
            aria-label="Scroll right">→</button>

        <div id="scrollCards" class="md-scroll-row px-md-5">
            @php
                $issueCards = [
                    ['slug' => 'teethgaps', 'video' => 'p1c1vi1.mp4', 'title' => 'Overbite'],
                    ['slug' => 'crookedteeth', 'video' => 'p1c1vi2.mp4', 'title' => 'Underbite'],
                    ['slug' => 'overbite', 'video' => 'p1c1vi4.mp4', 'title' => 'Crossbite'],
                    ['slug' => 'open-bite', 'video' => 'p1c1vi5.mp4', 'title' => 'Open Bite'],
                    ['slug' => 'crossbite', 'video' => 'p1c1vi6.mp4', 'title' => 'Crowding'],
                    ['slug' => 'underbite', 'video' => 'p1c1vi7.mp4', 'title' => 'Spacing'],
                ];
            @endphp

            @foreach ($issueCards as $card)
                <a href="{{ route('issue.show', $card['slug']) }}" class="text-decoration-none text-dark">
                    <div class="md-media-tile">
                        <video autoplay loop muted playsinline>
                            <source src="{{ asset('storage/vids/' . $card['video']) }}" type="video/mp4">
                        </video>
                    </div>
                    <p class="text-center fw-semibold mt-2 mb-0">{{ $card['title'] }}</p>
                </a>
            @endforeach

            <div class="md-media-tile">
                <img src="{{ asset('images/invisible.jpeg') }}" alt="Invisible clear aligners">
            </div>
            <div class="md-media-tile">
                <img src="{{ asset('images/mydent-care.jpeg') }}" alt="mydent aligner care kit">
            </div>
            <div class="md-media-tile">
                <img src="{{ asset('images/confident-smile.jpg') }}" alt="Confident smile results">
            </div>
        </div>
    </div>
</section>
