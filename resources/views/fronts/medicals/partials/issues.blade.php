<section class="md-section md-section--accent md-issues-carousel md-reveal" aria-labelledby="md-home-issues-title">
    <div class="container">
        <div class="text-center mb-4">
            <span class="md-section__eyebrow">Treatments</span>
            <h2 id="md-home-issues-title" class="md-section__title">Teeth alignment issues we can fix</h2>
            <p class="md-section__lead mx-auto">Overbites, gaps, crowding, and more — discreetly corrected with MyDent clear aligners.</p>
        </div>

        <div class="md-carousel" data-md-carousel>
            <button type="button" class="md-carousel__btn md-carousel__btn--prev" data-md-carousel-prev aria-label="Scroll left">
                <span aria-hidden="true">←</span>
            </button>
            <button type="button" class="md-carousel__btn md-carousel__btn--next" data-md-carousel-next aria-label="Scroll right">
                <span aria-hidden="true">→</span>
            </button>

            <div class="md-carousel__track" data-md-carousel-track tabindex="0">
                @php
                    $issueCards = [
                        ['slug' => 'teethgaps', 'video' => 'p1c1vi1.mp4', 'title' => 'Overbite', 'desc' => 'When the upper front teeth significantly overlap the lower front teeth while biting.'],
                        ['slug' => 'crookedteeth', 'video' => 'p1c1vi2.mp4', 'title' => 'Underbite', 'desc' => 'Occurs when the lower teeth extend beyond the upper teeth, affecting appearance and bite.'],
                        ['slug' => 'overbite', 'video' => 'p1c1vi4.mp4', 'title' => 'Crossbite', 'desc' => 'Upper and lower teeth do not align correctly, often causing wear or jaw problems.'],
                        ['slug' => 'open-bite', 'video' => 'p1c1vi5.mp4', 'title' => 'Open Bite', 'desc' => 'Front upper and lower teeth do not meet when the mouth is fully closed.'],
                        ['slug' => 'crossbite', 'video' => 'p1c1vi6.mp4', 'title' => 'Crowding', 'desc' => 'Not enough space for teeth to align properly, leading to overlapping or twisting.'],
                        ['slug' => 'underbite', 'video' => 'p1c1vi7.mp4', 'title' => 'Spacing', 'desc' => 'Gaps between teeth that can affect bite, speech, and smile appearance.'],
                    ];
                @endphp

                @foreach ($issueCards as $card)
                    <article class="md-issue-card">
                        <div class="md-issue-card__media">
                            <video autoplay loop muted playsinline>
                                <source src="{{ asset('storage/vids/' . $card['video']) }}" type="video/mp4">
                            </video>
                        </div>
                        <div class="md-issue-card__body">
                            <p class="md-issue-card__desc">{{ $card['desc'] }}</p>
                            <a href="{{ route('issue.show', $card['slug']) }}" class="md-btn md-btn--primary md-btn--block">
                                {{ $card['title'] }} →
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
