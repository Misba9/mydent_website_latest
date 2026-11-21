<!-- start dental issues section -->
@if($characteristicCards->count())
<section class="p-t-50 p-b-100" style="background-color: #D8F1F8;">
    <div class="container text-center">
        <h3 class="mb-5" style="padding-top: 50px;">Find out how mydent aligners help you achieve the smile you deserve.</h3>
        <div class="position-relative">
            <div class="md-scroll-row justify-content-center" id="characteristicScroll">
                @foreach($characteristicCards as $card)
                    <div class="md-char-card text-center">
                        <img src="{{ getMediaUrl($card->image_path, 'storage/imgs/logo.png') }}" alt="{{ $card->title }}" class="img-fluid" />
                        <h5 class="fw-bold mt-3">{{ $card->title }}</h5>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
@endif
