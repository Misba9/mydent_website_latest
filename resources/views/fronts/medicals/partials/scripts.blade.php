@push('scripts')



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollContainer = document.getElementById('scrollCards');
        const btnLeft = document.getElementById('scrollLeft');
        const btnRight = document.getElementById('scrollRight');

        if (btnLeft && scrollContainer) {
            btnLeft.addEventListener('click', function () {
                scrollContainer.scrollBy({ left: -320, behavior: 'smooth' });
            });
        }

        if (btnRight && scrollContainer) {
            btnRight.addEventListener('click', function () {
                scrollContainer.scrollBy({ left: 320, behavior: 'smooth' });
            });
        }
    });
</script>

<script>
function scrollRight() {
        const container = document.getElementById('scrollCards');
        if (container) {
            container.scrollBy({ left: 300, behavior: 'smooth' });
        }
    }


    // Counter animation for statistics section
    document.addEventListener("DOMContentLoaded", function() {
        const counters = document.querySelectorAll('.counter');
        
        const countUp = () => {
            counters.forEach(counter => {
                const target = parseInt(counter.innerText);
                const count = +counter.innerText.replace(/\+/g, '');
                const speed = 200;
                const inc = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(countUp, 1);
                } else {
                    counter.innerText = target + '+';
                }
            });
        };
        
        // Trigger counter when page loads
        countUp();
        
        // Initialize accordion
        const accordionButtons = document.querySelectorAll('.accordion-button');
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-bs-target');
                if (!target) return;
                const collapse = document.querySelector(target);
                if (!collapse) return;
                
                if (collapse.classList.contains('show')) {
                    collapse.classList.remove('show');
                    this.classList.add('collapsed');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    // Close all other accordion items
                    document.querySelectorAll('.accordion-collapse').forEach(item => {
                        item.classList.remove('show');
                    });
                    document.querySelectorAll('.accordion-button').forEach(btn => {
                        btn.classList.add('collapsed');
                        btn.setAttribute('aria-expanded', 'false');
                    });
                    
                    // Open clicked item
                    collapse.classList.add('show');
                    this.classList.remove('collapsed');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        });
    });



    window.addEventListener('scroll', function () {
        var banner = document.getElementById('sticky-banner');
        if (banner) {
            if (window.scrollY > 300) {
                banner.style.transform = 'translateY(0)';
            } else {
                banner.style.transform = 'translateY(100%)';
            }
        }
    });
</script>
@endpush