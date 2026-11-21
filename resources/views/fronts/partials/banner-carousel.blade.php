@props(['page', 'fallbackImage' => 'images/invisible.jpeg', 'fallbackTitle' => null])

@php
    $banners = \App\Models\MainBanner::where('page', $page)->get();
@endphp

@if($banners->count())
    <section class="md-banner-carousel">
        <div id="mainBannerCarousel-{{ $page }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($banners as $key => $banner)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="d-block w-100 md-banner-carousel__image">
                        @if($banner->title)
                            <div class="carousel-caption">
                                <h2 class="md-banner-carousel__title">{{ $banner->title }}</h2>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @if($banners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#mainBannerCarousel-{{ $page }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainBannerCarousel-{{ $page }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    </section>
@else
    <x-front.page-hero :title="$fallbackTitle ?? 'mydent'" :image="$fallbackImage" />
@endif
