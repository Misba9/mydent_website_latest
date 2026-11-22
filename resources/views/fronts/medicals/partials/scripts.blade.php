@push('scripts')
<script>
    (function () {
        function initMdCarousels() {
            document.querySelectorAll('[data-md-carousel]').forEach(function (root) {
                if (root.dataset.mdCarouselReady === '1') return;
                root.dataset.mdCarouselReady = '1';

                var track = root.querySelector('[data-md-carousel-track]');
                var prev = root.querySelector('[data-md-carousel-prev]');
                var next = root.querySelector('[data-md-carousel-next]');
                if (!track) return;

                var step = function () {
                    var card = track.querySelector('.md-issue-card');
                    return card ? card.getBoundingClientRect().width + 16 : 320;
                };

                if (prev) {
                    prev.addEventListener('click', function (e) {
                        e.preventDefault();
                        track.scrollBy({ left: -step(), behavior: 'smooth' });
                    });
                }
                if (next) {
                    next.addEventListener('click', function (e) {
                        e.preventDefault();
                        track.scrollBy({ left: step(), behavior: 'smooth' });
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initMdCarousels);
        document.addEventListener('turbo:load', initMdCarousels);
    })();
</script>
@endpush
