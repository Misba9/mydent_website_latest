<section class="md-section md-section--white md-reveal" aria-labelledby="md-discover-title">
    <div class="container">
        <div class="md-split">
            <div class="md-split__media">
                @if($section2Video)
                    <video autoplay loop muted playsinline poster="{{ asset('images/invisible.jpeg') }}">
                        <source src="{{ getMediaUrl($section2Video->video_path, 'storage/vids/heroandchat.mp4') }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ asset('images/invisible.jpeg') }}" alt="MyDent clear aligners" width="800" height="600" loading="lazy">
                @endif
            </div>
            <div>
                <span class="md-section__eyebrow">Clear Aligners</span>
                <h2 id="md-discover-title" class="md-section__title">Discover MyDent Clear Aligners</h2>
                <p class="md-section__lead mb-0">
                    Transparent trays that gradually straighten misaligned, crooked, or crowded teeth —
                    a comfortable, removable alternative to traditional braces.
                </p>
                <ul class="md-split__list">
                    <li>Virtually invisible for work, school, and daily life</li>
                    <li>Removable for meals and oral hygiene</li>
                    <li>Custom-made from your dental assessment</li>
                    <li>Supervised by MyDent’s clinical team</li>
                </ul>
                <x-front.button href="{{ route('medicalDoctors') }}">Explore Aligners</x-front.button>
            </div>
        </div>
    </div>
</section>
