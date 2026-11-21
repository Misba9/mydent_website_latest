<section class="md-section md-section--white md-reveal">
    <div class="container">
        <h2 class="md-section__title text-center">Real results at mydent</h2>
        <div class="row g-4 justify-content-center">
            @php
                $resultImages = [
                    ['src' => 'images/invisible.jpeg', 'alt' => 'Invisible Clear Aligners', 'label' => 'Invisible Clear Aligners'],
                    ['src' => 'images/mydent-care.jpeg', 'alt' => 'mydent Aligner Care Kit', 'label' => 'Aligner Care Kit'],
                    ['src' => 'images/Supervision.jpeg', 'alt' => 'Expert Dental Supervision', 'label' => 'Expert Supervision'],
                    ['src' => 'images/Metal-braces.png', 'alt' => 'Metal Braces Treatment', 'label' => 'Metal Braces'],
                    ['src' => 'images/Self-ligation-braces.jpeg', 'alt' => 'Self Ligating Braces', 'label' => 'Self-Ligating Braces'],
                    ['src' => 'images/Advance-technology.png', 'alt' => 'Advanced Technology at mydent', 'label' => 'Advanced Technology'],
                    ['src' => 'images/Pay-after-approval.png', 'alt' => 'Pay after approval at mydent', 'label' => 'Pay After Approval'],
                ];
            @endphp
            @foreach($resultImages as $item)
                <div class="col-md-6 col-lg-4 text-center">
                    <img src="{{ asset($item['src']) }}" alt="{{ $item['alt'] }}" class="img-fluid rounded" style="width:100%; height:260px; object-fit:cover; box-shadow: var(--md-shadow-soft);">
                    <p class="mt-3 fw-semibold mb-0">{{ $item['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
