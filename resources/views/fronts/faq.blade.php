@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.faqs') }}
@endsection

@section('front-content')
    <div class="our-faqs-page md-faq">
        <x-front.page-hero
            title="{{ __('messages.web.faqs') }}"
            lead="Answers about clear aligners, scans, pricing, and your mydent journey."
            image="images/Supervision.jpeg"
        />

        <section class="md-section md-section--soft">
            <div class="container">
                @if(isset($faqs) && count($faqs))
                    <div class="row justify-content-center g-4">
                        <div class="col-lg-6">
                            <div class="accordion" id="accordionRow1">
                                @foreach($faqs as $faq)
                                    @if($loop->odd)
                                        <div class="accordion-item mb-3">
                                            <h2 class="accordion-header" id="heading{{ $loop->index + 1 }}">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $loop->index + 1 }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse{{ $loop->index + 1 }}">
                                                    {{ $faq->question }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $loop->index + 1 }}" class="accordion-collapse collapse"
                                                 aria-labelledby="heading{{ $loop->index + 1 }}"
                                                 data-bs-parent="#accordionRow1">
                                                <div class="accordion-body text-muted">{{ $faq->answer }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="accordion" id="accordionRow2">
                                @foreach($faqs as $faq)
                                    @if($loop->even)
                                        <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
                                            <h2 class="accordion-header" id="heading{{ $loop->index + 1 }}">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $loop->index + 1 }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse{{ $loop->index + 1 }}">
                                                    {{ $faq->question }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $loop->index + 1 }}" class="accordion-collapse collapse"
                                                 aria-labelledby="heading{{ $loop->index + 1 }}"
                                                 data-bs-parent="#accordionRow2">
                                                <div class="accordion-body text-muted">{{ $faq->answer }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <x-front.empty-state title="No FAQs yet" message="Check back soon for answers to common questions." />
                @endif

                <div class="text-center mt-5">
                    <x-front.button href="{{ route('medicalContact') }}" variant="outline">Still have questions? Contact us</x-front.button>
                </div>
            </div>
        </section>
    </div>
@endsection
